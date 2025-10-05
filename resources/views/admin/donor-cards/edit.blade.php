@extends('admin.layouts.app')

@section('title', 'Edit Kartu Donor: ' . $donorCard->card_number)

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold text-gray-800">Edit Kartu Donor</h2>
                    </div>
                    
                    <form action="{{ route('admin.donor-cards.update', $donorCard) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                        <label>No. KTA</label>
                                        <input type="text" class="form-control" value="{{ $donorCard->card_number }}"
                                            readonly>
                                        <small class="form-text text-muted">Nomor KTA tidak dapat diubah</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nama Pendonor</label>
                                        <input type="text" class="form-control" value="{{ $donorCard->donor->name }}"
                                            readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="issue_date">Tanggal Terbit <span class="text-danger">*</span></label>
                                        <input type="date" name="issue_date" id="issue_date"
                                            class="form-control @error('issue_date') is-invalid @enderror"
                                            value="{{ old('issue_date', $donorCard->issue_date->format('Y-m-d')) }}"
                                            required>
                                        @error('issue_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="expiry_date">Masa Berlaku <span class="text-danger">*</span></label>
                                        <input type="date" name="expiry_date" id="expiry_date"
                                            class="form-control @error('expiry_date') is-invalid @enderror"
                                            value="{{ old('expiry_date', $donorCard->expiry_date->format('Y-m-d')) }}"
                                            required>
                                        @error('expiry_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="status">Status <span class="text-danger">*</span></label>
                                        <select name="status" id="status"
                                            class="form-control @error('status') is-invalid @enderror" required>
                                            <option value="active"
                                                {{ old('status', $donorCard->status) == 'active' ? 'selected' : '' }}>Aktif
                                            </option>
                                            <option value="inactive"
                                                {{ old('status', $donorCard->status) == 'inactive' ? 'selected' : '' }}>
                                                Tidak Aktif</option>
                                            <option value="expired"
                                                {{ old('status', $donorCard->status) == 'expired' ? 'selected' : '' }}>
                                                Kadaluarsa</option>
                                            <option value="revoked"
                                                {{ old('status', $donorCard->status) == 'revoked' ? 'selected' : '' }}>
                                                Dicabut</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="photo">Foto KTA</label>
                                <div class="custom-file">
                                    <input type="file" name="photo" id="photo"
                                        class="custom-file-input @error('photo') is-invalid @enderror" accept="image/*">
                                    <label class="custom-file-label" for="photo">
                                        {{ $donorCard->card_photo_path ? 'Ganti foto...' : 'Pilih file...' }}
                                    </label>
                                    @error('photo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah foto. Ukuran
                                    maksimal 2MB. Format: JPG, PNG, JPEG</small>

                                @if ($donorCard->card_photo_path)
                                    <div class="mt-2">
                                        <p>Foto saat ini:</p>
                                        <img src="{{ asset('storage/' . $donorCard->card_photo_path) }}" alt="Foto KTA"
                                            class="img-thumbnail" style="max-height: 150px;">
                                    </div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="notes">Catatan</label>
                                <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes', $donorCard->notes) }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="alert alert-info">
                                <strong>Informasi:</strong> QR Code akan tetap sama meskipun data diubah.
                                QR Code hanya digenerate saat pembuatan KTA baru.
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Perubahan
                            </button>
                            <a href="{{ route('admin.donor-cards.show', $donorCard) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Show file name when file is selected
            $('.custom-file-input').on('change', function() {
                let fileName = $(this).val().split('\\').pop();
                $(this).next('.custom-file-label').addClass("selected").html(fileName || 'Pilih file...');
            });
        });
    </script>
@endpush
