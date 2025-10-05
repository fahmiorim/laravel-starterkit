@extends('admin.layouts.admin')

@section('title', 'Edit Transaksi Stok Darah')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Edit Transaksi Stok Darah</h1>
            <a href="{{ route('admin.blood-stock-transactions.index') }}"
                class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
            </a>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Form Edit Transaksi Stok Darah</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.blood-stock-transactions.update', $transaction->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="type">Jenis Transaksi <span class="text-danger">*</span></label>
                        <select name="type" id="type" class="form-control @error('type') is-invalid @enderror"
                            required {{ $transaction->status !== 'pending' ? 'disabled' : '' }}>
                            <option value="in" {{ old('type', $transaction->type) == 'in' ? 'selected' : '' }}>Stok
                                Masuk</option>
                            <option value="out" {{ old('type', $transaction->type) == 'out' ? 'selected' : '' }}>Stok
                                Keluar</option>
                        </select>
                        @error('type')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="blood_stock_id">Golongan Darah <span class="text-danger">*</span></label>
                        <select name="blood_stock_id" id="blood_stock_id"
                            class="form-control @error('blood_stock_id') is-invalid @enderror" required
                            {{ $transaction->status !== 'pending' ? 'disabled' : '' }}>
                            @foreach ($bloodStocks as $stock)
                                <option value="{{ $stock->id }}"
                                    {{ old('blood_stock_id', $transaction->blood_stock_id) == $stock->id ? 'selected' : '' }}>
                                    {{ $stock->blood_type }}{{ $stock->rhesus }}
                                    @if ($transaction->status === 'pending')
                                        (Tersedia: {{ $stock->quantity }} kantong)
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @if ($transaction->status !== 'pending')
                            <input type="hidden" name="blood_stock_id" value="{{ $transaction->blood_stock_id }}">
                        @endif
                        @error('blood_stock_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="quantity">Jumlah (kantong) <span class="text-danger">*</span></label>
                        <input type="number" name="quantity" id="quantity"
                            class="form-control @error('quantity') is-invalid @enderror"
                            value="{{ old('quantity', $transaction->quantity) }}" min="1" required
                            {{ $transaction->status !== 'pending' ? 'readonly' : '' }}>
                        @error('quantity')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="source_destination" id="source_destination_label">
                            {{ $transaction->type == 'in' ? 'Sumber' : 'Tujuan' }} Stok <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="source_destination" id="source_destination"
                            class="form-control @error('source_destination') is-invalid @enderror"
                            value="{{ old('source_destination', $transaction->source_destination) }}" required
                            {{ $transaction->status !== 'pending' ? 'readonly' : '' }}>
                        @error('source_destination')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="reference_number">Nomor Referensi</label>
                        <input type="text" name="reference_number" id="reference_number"
                            class="form-control @error('reference_number') is-invalid @enderror"
                            value="{{ old('reference_number', $transaction->reference_number) }}"
                            {{ $transaction->status !== 'pending' ? 'readonly' : '' }}>
                        @error('reference_number')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="notes">Catatan</label>
                        <textarea name="notes" id="notes" rows="3" class="form-control @error('notes') is-invalid @enderror"
                            {{ $transaction->status !== 'pending' ? 'readonly' : '' }}>{{ old('notes', $transaction->notes) }}</textarea>
                        @error('notes')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    @if ($transaction->status === 'rejected' && $transaction->rejection_reason)
                        <div class="alert alert-danger">
                            <strong>Alasan Penolakan:</strong> {{ $transaction->rejection_reason }}
                        </div>
                    @endif

                    <div class="form-group">
                        @if ($transaction->status === 'pending')
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Perbarui
                            </button>
                        @endif
                        <a href="{{ route('admin.blood-stock-transactions.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const typeSelect = document.getElementById('type');
            const sourceDestinationLabel = document.getElementById('source_destination_label');

            // Update label based on transaction type
            function updateSourceDestinationLabel() {
                if (typeSelect.value === 'in') {
                    sourceDestinationLabel.textContent = 'Sumber Stok *';
                } else {
                    sourceDestinationLabel.textContent = 'Tujuan Stok *';
                }
            }

            // Initial update
            updateSourceDestinationLabel();

            // Update on change (only if not disabled)
            if (!typeSelect.disabled) {
                typeSelect.addEventListener('change', updateSourceDestinationLabel);
            }
        });
    </script>
@endpush
