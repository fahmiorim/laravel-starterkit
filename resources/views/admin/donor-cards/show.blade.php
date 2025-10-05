@extends('admin.layouts.app')

@section('title', 'Detail Kartu Donor: ' . $donorCard->card_number)

@section('content')
<div class="min-h-screen bg-gray-50 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-800">Detail Kartu Donor</h2>
            <div class="flex space-x-3">
                <a href="{{ route('admin.donor-cards.edit', $donorCard) }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                    </svg>
                    Edit
                </a>
                <a href="{{ route('admin.donor-cards.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Kembali
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Informasi Kartu -->
            <div class="lg:col-span-1">
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:px-6 bg-gray-50 border-b border-gray-200">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Informasi Kartu</h3>
                    </div>
                    <div class="px-4 py-5 sm:p-6 text-center">
                        <div class="mb-4">
                            @if ($donorCard->card_photo_path)
                                <img src="{{ asset('storage/' . $donorCard->card_photo_path) }}" 
                                     alt="Foto KTA" 
                                     class="mx-auto rounded-lg shadow-md"
                                     style="max-height: 200px;">
                            @else
                                <div class="bg-gray-100 p-8 rounded-lg text-center">
                                    <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                    <p class="mt-2">Tidak ada foto</p>
                                </div>
                            @endif
                        </div>

                        <h4 class="mb-3">{{ $donorCard->card_number }}</h4>

                        <div class="text-left">
                            <p><strong>Status:</strong>
                                @if ($donorCard->isActive())
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-danger">Tidak Aktif</span>
                                @endif
                            </p>
                            <p><strong>Tanggal Terbit:</strong> {{ $donorCard->issue_date->format('d/m/Y') }}</p>
                            <p><strong>Masa Berlaku:</strong> {{ $donorCard->expiry_date->format('d/m/Y') }}</p>
                            <p><strong>Dibuat:</strong> {{ $donorCard->created_at->format('d/m/Y H:i') }}</p>

                            @if ($donorCard->notes)
                                <div class="mt-3">
                                    <h5>Catatan:</h5>
                                    <p class="text-muted">{{ $donorCard->notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="card-footer text-center
                ">
                        <a href="{{ route('admin.donor-cards.download-pdf', $donorCard) }}" class="btn btn-warning"
                            target="_blank">
                            <i class="fas fa-file-pdf"></i> Unduh PDF
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Informasi Pendonor</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Nama Lengkap:</strong> {{ $donorCard->donor->name }}</p>
                                <p><strong>NIK:</strong> {{ $donorCard->donor->nik }}</p>
                                <p><strong>Jenis Kelamin:</strong> {{ $donorCard->donor->gender }}</p>
                                <p><strong>Tanggal Lahir:</strong> {{ $donorCard->donor->birth_date->format('d/m/Y') }}</p>
                                <p><strong>Umur:</strong> {{ $donorCard->donor->birth_date->age }} tahun</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Golongan Darah:</strong>
                                    {{ $donorCard->donor->blood_type }}{{ $donorCard->donor->rhesus }}</p>
                                <p><strong>No. Telepon:</strong> {{ $donorCard->donor->phone }}</p>
                                <p><strong>Total Donor:</strong> {{ $donorCard->donor->total_donations }} kali</p>
                                <p><strong>Terakhir Donor:</strong>
                                    @if ($donorCard->donor->last_donation_date)
                                        {{ $donorCard->donor->last_donation_date->format('d/m/Y') }}
                                    @else
                                        Belum pernah donor
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="mt-3">
                            <h5>Alamat:</h5>
                            <p class="text-muted">{{ $donorCard->donor->address }}</p>
                        </div>

                        <div class="mt-4">
                            <h5>QR Code</h5>
                            <div class="text-center">
                                <img src="{{ asset('storage/' . $donorCard->qr_code_path) }}" alt="QR Code"
                                    class="img-fluid" style="max-width: 200px;">
                                <p class="text-muted mt-2">Scan QR Code untuk memverifikasi keaslian KTA</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header">
                        <h3 class="card-title">Riwayat Donor</h3>
                    </div>
                    <div class="card-body">
                        @if ($donorCard->donor->histories->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Lokasi</th>
                                            <th>Petugas</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($donorCard->donor->histories as $history)
                                            <tr>
                                                <td>{{ $history->donation_date->format('d/m/Y') }}</td>
                                                <td>{{ $history->location ?? '-' }}</td>
                                                <td>{{ $history->officer_name ?? '-' }}</td>
                                                <td>
                                                    @if ($history->status == 'success')
                                                        <span class="badge bg-success">Berhasil</span>
                                                    @elseif($history->status == 'rejected')
                                                        <span class="badge bg-danger">Ditolak</span>
                                                    @else
                                                        <span class="badge bg-warning">Menunggu</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted">Belum ada riwayat donor</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-footer">
                        <a href="{{ route('admin.donor-cards.edit', $donorCard) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('admin.donor-cards.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali ke Daftar
                        </a>
                        <form action="{{ route('admin.donor-cards.destroy', $donorCard) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus KTA ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger float-right">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
