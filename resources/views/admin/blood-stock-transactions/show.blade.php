@extends('admin.layouts.admin')

@section('title', 'Detail Transaksi Stok Darah')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Detail Transaksi Stok Darah</h1>
            <div>
                @can('update', $transaction)
                    <a href="{{ route('admin.blood-stock-transactions.edit', $transaction->id) }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                @endcan
                <a href="{{ route('admin.blood-stock-transactions.index') }}" class="btn btn-sm btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Informasi Transaksi</h6>
                        <div>
                            @if ($transaction->status === 'approved')
                                <span class="badge badge-success">Disetujui</span>
                            @elseif($transaction->status === 'rejected')
                                <span class="badge badge-danger">Ditolak</span>
                            @else
                                <span class="badge badge-warning">Menunggu Persetujuan</span>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th width="40%">ID Transaksi</th>
                                <td>#{{ str_pad($transaction->id, 6, '0', STR_PAD_LEFT) }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Transaksi</th>
                                <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Jenis Transaksi</th>
                                <td>
                                    @if ($transaction->type == 'in')
                                        <span class="badge badge-success">Stok Masuk</span>
                                    @else
                                        <span class="badge badge-danger">Stok Keluar</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Golongan Darah</th>
                                <td>
                                    {{ $transaction->bloodStock->blood_type }}{{ $transaction->bloodStock->rhesus }}
                                    <span class="text-muted">(ID: {{ $transaction->blood_stock_id }})</span>
                                </td>
                            </tr>
                            <tr>
                                <th>Jumlah</th>
                                <td>{{ $transaction->quantity }} kantong</td>
                            </tr>
                            <tr>
                                <th>{{ $transaction->type == 'in' ? 'Sumber' : 'Tujuan' }} Stok</th>
                                <td>{{ $transaction->source_destination }}</td>
                            </tr>
                            @if ($transaction->reference_number)
                                <tr>
                                    <th>Nomor Referensi</th>
                                    <td>{{ $transaction->reference_number }}</td>
                                </tr>
                            @endif
                            @if ($transaction->notes)
                                <tr>
                                    <th>Catatan</th>
                                    <td>{{ $transaction->notes }}</td>
                                </tr>
                            @endif
                            @if ($transaction->status === 'rejected' && $transaction->rejection_reason)
                                <tr class="table-danger">
                                    <th>Alasan Penolakan</th>
                                    <td>{{ $transaction->rejection_reason }}</td>
                                </tr>
                            @endif
                            <tr>
                                <th>Dibuat Oleh</th>
                                <td>
                                    {{ $transaction->creator->name }}
                                    <span class="text-muted">({{ $transaction->created_at->diffForHumans() }})</span>
                                </td>
                            </tr>
                            @if ($transaction->status !== 'pending')
                                <tr>
                                    <th>{{ $transaction->status === 'approved' ? 'Disetujui' : 'Ditolak' }} Oleh</th>
                                    <td>
                                        {{ $transaction->approver->name ?? 'Sistem' }}
                                        @if ($transaction->approved_at)
                                            <span
                                                class="text-muted">({{ $transaction->approved_at->diffForHumans() }})</span>
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        </table>

                        <div class="mt-4">
                            @can('approve', $transaction)
                                @if ($transaction->isPending())
                                    <button type="button" class="btn btn-success approve-btn" data-toggle="modal"
                                        data-target="#approveModal">
                                        <i class="fas fa-check"></i> Setujui
                                    </button>
                                    <button type="button" class="btn btn-danger reject-btn" data-toggle="modal"
                                        data-target="#rejectModal">
                                        <i class="fas fa-times"></i> Tolak
                                    </button>
                                @endif
                            @endcan

                            @can('delete', $transaction)
                                <form action="{{ route('admin.blood-stock-transactions.destroy', $transaction->id) }}"
                                    method="POST" class="d-inline"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Informasi Stok</h6>
                    </div>
                    <div class="card-body">
                        <div class="text-center">
                            <div class="h5 mb-3">
                                <strong>Golongan Darah:</strong>
                                {{ $transaction->bloodStock->blood_type }}{{ $transaction->bloodStock->rhesus }}
                            </div>
                            <div class="h2 mb-3">
                                @if ($transaction->type == 'in')
                                    <span class="text-success">+{{ $transaction->quantity }}</span>
                                @else
                                    <span class="text-danger">-{{ $transaction->quantity }}</span>
                                @endif
                                <small class="text-muted">kantong</small>
                            </div>
                            <div class="mb-3">
                                @if ($transaction->isApproved())
                                    <span class="badge badge-success">Telah Memengaruhi Stok</span>
                                @elseif($transaction->isRejected())
                                    <span class="badge badge-danger">Tidak Mempengaruhi Stok</span>
                                @else
                                    <span class="badge badge-warning">Belum Mempengaruhi Stok</span>
                                @endif
                            </div>
                            <hr>
                            <div class="mt-3">
                                <div>Stok Saat Ini:</div>
                                <div class="h3">{{ $transaction->bloodStock->quantity }} <small>kantong</small></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Aksi Cepat</h6>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            @if ($transaction->isPending() && $transaction->type == 'in')
                                <a href="{{ route('admin.blood-stock-transactions.create', ['type' => 'in', 'blood_stock_id' => $transaction->blood_stock_id]) }}"
                                    class="list-group-item list-group-item-action">
                                    <i class="fas fa-plus-circle text-success"></i> Buat Transaksi Masuk Lainnya
                                </a>
                            @elseif($transaction->isPending() && $transaction->type == 'out')
                                <a href="{{ route('admin.blood-stock-transactions.create', ['type' => 'out', 'blood_stock_id' => $transaction->blood_stock_id]) }}"
                                    class="list-group-item list-group-item-action">
                                    <i class="fas fa-minus-circle text-danger"></i> Buat Transaksi Keluar Lainnya
                                </a>
                            @endif

                            @if ($transaction->blood_stock_id)
                                <a href="{{ route('admin.blood-stocks.show', $transaction->blood_stock_id) }}"
                                    class="list-group-item list-group-item-action">
                                    <i class="fas fa-tint"></i> Lihat Detail Stok Darah
                                </a>
                            @endif

                            @if ($transaction->reference_number)
                                <a href="#" class="list-group-item list-group-item-action"
                                    onclick="event.preventDefault(); document.getElementById('searchForm').submit();">
                                    <i class="fas fa-search"></i> Cari Referensi Serupa
                                </a>
                                <form id="searchForm" action="{{ route('admin.blood-stock-transactions.index') }}"
                                    method="GET" class="d-none">
                                    <input type="hidden" name="reference_number"
                                        value="{{ $transaction->reference_number }}">
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel">Tolak Transaksi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.blood-stock-transactions.reject', $transaction->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="rejection_reason">Alasan Penolakan <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Tolak Transaksi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Approve Modal -->
    <div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="approveModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="approveModalLabel">Setujui Transaksi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.blood-stock-transactions.approve', $transaction->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menyetujui transaksi ini?</p>
                        <p class="font-weight-bold">Stok akan otomatis diperbarui setelah transaksi disetujui.</p>

                        <div class="alert alert-info">
                            <strong>Detail Transaksi:</strong><br>
                            Golongan Darah:
                            {{ $transaction->bloodStock->blood_type }}{{ $transaction->bloodStock->rhesus }}<br>
                            Jumlah: {{ $transaction->quantity }} kantong<br>
                            Jenis: {{ $transaction->type == 'in' ? 'Stok Masuk' : 'Stok Keluar' }}
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Setujui</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .badge {
            font-size: 0.9em;
            font-weight: 500;
            padding: 0.4em 0.8em;
        }

        .table th {
            width: 35%;
        }

        .list-group-item {
            border-left: none;
            border-right: none;
        }

        .list-group-item:first-child {
            border-top: none;
        }

        .list-group-item:last-child {
            border-bottom: none;
        }
    </style>
@endpush
