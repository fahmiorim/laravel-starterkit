@extends('layouts.app')

@section('title', 'Kartu Donor - ' . $donorCard->card_number)

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0">Verifikasi Kartu Donor</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> 
                        Scan QR Code atau masukkan nomor KTA untuk memverifikasi keaslian kartu donor.
                    </div>

                    <div class="text-center mb-4">
                        <img src="{{ asset('storage/' . $donorCard->qr_code_path) }}" 
                             alt="QR Code" 
                             class="img-fluid" 
                             style="max-width: 200px;">
                        <p class="mt-2 text-muted">Scan kode QR di atas untuk verifikasi</p>
                    </div>

                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Informasi Kartu Donor</h5>
                            <hr>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>No. KTA:</strong> {{ $donorCard->card_number }}</p>
                                    <p><strong>Nama:</strong> {{ $donorCard->donor->name }}</p>
                                    <p><strong>Gol. Darah:</strong> {{ $donorCard->donor->blood_type }}{{ $donorCard->donor->rhesus }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Status:</strong> 
                                        @if($donorCard->isActive())
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-danger">Tidak Aktif</span>
                                        @endif
                                    </p>
                                    <p><strong>Masa Berlaku:</strong> {{ $donorCard->expiry_date->format('d/m/Y') }}</p>
                                    <p><strong>Total Donor:</strong> {{ $donorCard->donor->total_donations }} kali</p>
                                </div>
                            </div>

                            @if($donorCard->donor->last_donation_date)
                                <div class="alert alert-warning mt-3">
                                    <i class="fas fa-info-circle"></i>
                                    Terakhir donor darah pada {{ $donorCard->donor->last_donation_date->format('d F Y') }}.
                                    Donor darah berikutnya dapat dilakukan setelah {{ $donorCard->donor->last_donation_date->addDays(90)->format('d F Y') }}.
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Informasi Pendonor</h5>
                            <hr>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>NIK:</strong> {{ $donorCard->donor->nik }}</p>
                                    <p><strong>Jenis Kelamin:</strong> {{ $donorCard->donor->gender }}</p>
                                    <p><strong>TTL:</strong> {{ $donorCard->donor->birth_place ?? '-' }}, {{ $donorCard->donor->birth_date->format('d/m/Y') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>No. Telepon:</strong> {{ $donorCard->donor->phone }}</p>
                                    <p><strong>Alamat:</strong> {{ $donorCard->donor->address }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-warning mt-4">
                        <h5><i class="fas fa-exclamation-triangle"></i> Penting!</h5>
                        <p class="mb-0">
                            Kartu ini adalah bukti keanggotaan resmi dari Palang Merah Indonesia. 
                            Jika menemukan kartu ini dalam keadaan hilang, harap segera menghubungi kantor PMI terdekat.
                        </p>
                    </div>

                    <div class="text-center mt-4">
                        <a href="#" class="btn btn-primary" onclick="window.print()">
                            <i class="fas fa-print"></i> Cetak Halaman
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-secondary">
                            <i class="fas fa-home"></i> Kembali ke Beranda
                        </a>
                    </div>
                </div>
                <div class="card-footer text-muted text-center">
                    <small>
                        Dikeluarkan oleh: {{ config('app.name') }} - {{ config('app.url') }}
                        <br>
                        Dicetak pada: {{ now()->format('d F Y H:i:s') }}
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    @media print {
        .no-print {
            display: none !important;
        }
        body {
            background: white !important;
            font-size: 12px !important;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
        .card-header {
            background-color: #d32f2f !important;
            -webkit-print-color-adjust: exact;
        }
        .alert {
            display: none !important;
        }
    }
</style>
@endpush
@endsection
