<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Donor - {{ $donorCard->card_number }}</title>
    <style>
        @page {
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }
        .card-container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            background: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }
        .card-header {
            background-color: #d32f2f;
            color: white;
            padding: 15px 20px;
            text-align: center;
            position: relative;
        }
        .card-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }
        .card-header p {
            margin: 5px 0 0;
            font-size: 14px;
        }
        .card-body {
            padding: 20px;
            display: flex;
        }
        .photo-section {
            width: 30%;
            padding-right: 20px;
            border-right: 1px solid #eee;
            text-align: center;
        }
        .photo-placeholder {
            width: 100%;
            height: 180px;
            background-color: #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
            border: 1px solid #ddd;
        }
        .photo-placeholder i {
            font-size: 48px;
            color: #999;
        }
        .photo-section img {
            max-width: 100%;
            max-height: 180px;
            object-fit: cover;
            border: 1px solid #ddd;
        }
        .qr-code {
            width: 100px;
            height: 100px;
            margin: 10px auto;
        }
        .qr-code img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        .info-section {
            width: 70%;
            padding-left: 20px;
        }
        .info-row {
            display: flex;
            margin-bottom: 10px;
        }
        .info-label {
            width: 150px;
            font-weight: bold;
        }
        .info-value {
            flex: 1;
        }
        .card-footer {
            background-color: #f5f5f5;
            padding: 10px 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #eee;
        }
        .card-number {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: #d32f2f;
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 14px;
        }
        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-active {
            background-color: #4caf50;
            color: white;
        }
        .status-inactive {
            background-color: #f44336;
            color: white;
        }
        .signature {
            margin-top: 30px;
            text-align: right;
        }
        .signature-line {
            width: 200px;
            border-top: 1px solid #333;
            margin: 40px 0 5px auto;
            text-align: center;
        }
        .signature-text {
            font-size: 12px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="card-container">
        <div class="card-header">
            <h1>KARTU TANDA ANGGOTA</h1>
            <p>PALANG MERAH INDONESIA</p>
            <div class="card-number">{{ $donorCard->card_number }}</div>
        </div>
        
        <div class="card-body">
            <div class="photo-section">
                @if($donorCard->card_photo_path)
                    <img src="{{ storage_path('app/public/' . $donorCard->card_photo_path) }}" alt="Foto Pendonor">
                @else
                    <div class="photo-placeholder">
                        <i class="fas fa-user"></i>
                    </div>
                @endif
                
                <div class="qr-code">
                    <img src="{{ storage_path('app/public/' . $donorCard->qr_code_path) }}" alt="QR Code">
                </div>
                
                <div>
                    <span class="status-badge {{ $donorCard->isActive() ? 'status-active' : 'status-inactive' }}">
                        {{ $donorCard->status }}
                    </span>
                </div>
            </div>
            
            <div class="info-section">
                <div class="info-row">
                    <div class="info-label">Nama Lengkap</div>
                    <div class="info-value">: {{ $donorCard->donor->name }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">NIK</div>
                    <div class="info-value">: {{ $donorCard->donor->nik }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Jenis Kelamin</div>
                    <div class="info-value">: {{ $donorCard->donor->gender }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Tempat/Tgl. Lahir</div>
                    <div class="info-value">: {{ $donorCard->donor->birth_place ?? '-' }}, {{ $donorCard->donor->birth_date->format('d/m/Y') }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Golongan Darah</div>
                    <div class="info-value">: {{ $donorCard->donor->blood_type }}{{ $donorCard->donor->rhesus }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Alamat</div>
                    <div class="info-value">: {{ $donorCard->donor->address }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">No. Telepon</div>
                    <div class="info-value">: {{ $donorCard->donor->phone }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Total Donor</div>
                    <div class="info-value">: {{ $donorCard->donor->total_donations }} kali</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Terakhir Donor</div>
                    <div class="info-value">: 
                        @if($donorCard->donor->last_donation_date)
                            {{ $donorCard->donor->last_donation_date->format('d/m/Y') }}
                        @else
                            Belum pernah donor
                        @endif
                    </div>
                </div>
                
                <div class="signature">
                    <div class="signature-line"></div>
                    <div class="signature-text">
                        <p>Pemegang Kartu</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card-footer">
            <p>Kartu ini sah dan berlaku hingga {{ $donorCard->expiry_date->format('d F Y') }}. 
            Jika ditemukan, harap kembalikan ke kantor PMI terdekat.</p>
        </div>
    </div>
</body>
</html>
