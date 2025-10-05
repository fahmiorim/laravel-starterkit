@extends('admin.layouts.admin')

@section('title', 'Manajemen Transaksi Stok Darah')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Manajemen Transaksi Stok Darah</h1>
            <a href="{{ route('admin.blood-stock-transactions.create') }}"
                class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50"></i> Transaksi Baru
            </a>
        </div>

        <!-- Filter Section -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Filter</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.blood-stock-transactions.index') }}" method="GET" class="form-inline">
                    <div class="form-group mr-2 mb-2">
                        <label for="type" class="sr-only">Tipe</label>
                        <select name="type" id="type" class="form-control">
                            <option value="">Semua Tipe</option>
                            <option value="in" {{ request('type') == 'in' ? 'selected' : '' }}>Stok Masuk</option>
                            <option value="out" {{ request('type') == 'out' ? 'selected' : '' }}>Stok Keluar</option>
                        </select>
                    </div>
                    <div class="form-group mr-2 mb-2">
                        <label for="status" class="sr-only">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui
                            </option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak
                            </option>
                        </select>
                    </div>
                    <div class="form-group mr-2 mb-2">
                        <label for="start_date" class="sr-only">Dari Tanggal</label>
                        <input type="date" name="start_date" id="start_date" class="form-control"
                            value="{{ request('start_date') }}">
                    </div>
                    <div class="form-group mr-2 mb-2">
                        <label for="end_date" class="sr-only">Sampai Tanggal</label>
                        <input type="date" name="end_date" id="end_date" class="form-control"
                            value="{{ request('end_date') }}">
                    </div>
                    <button type="submit" class="btn btn-primary mb-2">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <a href="{{ route('admin.blood-stock-transactions.index') }}" class="btn btn-secondary mb-2 ml-1">
                        <i class="fas fa-sync-alt"></i> Reset
                    </a>
                </form>
            </div>
        </div>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Transaksi</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Tanggal</th>
                                <th>Tipe</th>
                                <th>Golongan Darah</th>
                                <th>Jumlah</th>
                                <th>Sumber/Tujuan</th>
                                <th>Status</th>
                                <th>Dibuat Oleh</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $transaction)
                                <tr>
                                    <td>{{ $loop->iteration + ($transactions->currentPage() - 1) * $transactions->perPage() }}
                                    </td>
                                    <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        @if ($transaction->type == 'in')
                                            <span class="badge badge-success">Stok Masuk</span>
                                        @else
                                            <span class="badge badge-danger">Stok Keluar</span>
                                        @endif
                                    </td>
                                    <td>{{ $transaction->bloodStock->blood_type }}{{ $transaction->bloodStock->rhesus }}
                                    </td>
                                    <td>{{ $transaction->quantity }} kantong</td>
                                    <td>{{ $transaction->source_destination }}</td>
                                    <td>
                                        @if ($transaction->status == 'approved')
                                            <span class="badge badge-success">Disetujui</span>
                                        @elseif($transaction->status == 'rejected')
                                            <span class="badge badge-danger">Ditolak</span>
                                        @else
                                            <span class="badge badge-warning">Menunggu</span>
                                        @endif
                                    </td>
                                    <td>{{ $transaction->creator->name }}</td>
                                    <td>
                                        <a href="{{ route('admin.blood-stock-transactions.show', $transaction->id) }}"
                                            class="btn btn-sm btn-info" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @can('update', $transaction)
                                            <a href="{{ route('admin.blood-stock-transactions.edit', $transaction->id) }}"
                                                class="btn btn-sm btn-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endcan
                                        @can('approve', $transaction)
                                            @if ($transaction->isPending())
                                                <button class="btn btn-sm btn-success approve-btn"
                                                    data-id="{{ $transaction->id }}" title="Setujui">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger reject-btn"
                                                    data-id="{{ $transaction->id }}" title="Tolak">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            @endif
                                        @endcan
                                        @can('delete', $transaction)
                                            <form
                                                action="{{ route('admin.blood-stock-transactions.destroy', $transaction->id) }}"
                                                method="POST" class="d-inline"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">Tidak ada data transaksi</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $transactions->withQueryString()->links() }}
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
                <form id="rejectForm" method="POST" action="">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="rejection_reason">Alasan Penolakan</label>
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
                <form id="approveForm" method="POST" action="">
                    @csrf
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menyetujui transaksi ini?</p>
                        <p class="font-weight-bold">Stok akan otomatis diperbarui setelah transaksi disetujui.</p>
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
    <link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <style>
        .badge {
            font-size: 0.8em;
            font-weight: 500;
            padding: 0.4em 0.6em;
        }
    </style>
@endpush

@push('scripts')
    <!-- Page level plugins -->
    <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Handle reject button click
            $('.reject-btn').on('click', function() {
                var id = $(this).data('id');
                var url = '{{ route('admin.blood-stock-transactions.reject', ':id') }}';
                url = url.replace(':id', id);

                $('#rejectForm').attr('action', url);
                $('#rejectModal').modal('show');
            });

            // Handle approve button click
            $('.approve-btn').on('click', function() {
                var id = $(this).data('id');
                var url = '{{ route('admin.blood-stock-transactions.approve', ':id') }}';
                url = url.replace(':id', id);

                $('#approveForm').attr('action', url);
                $('#approveModal').modal('show');
            });

            // Initialize DataTable
            $('#dataTable').DataTable({
                "paging": false,
                "searching": false,
                "info": false,
                "ordering": false,
                "responsive": true,
            });
        });
    </script>
@endpush
