<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Tanda Anggota PMI</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
        }
        .card {
            width: 100mm; /* A7 landscape width */
            height: 69mm; /* A7 landscape height */
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            padding: 10px;
            position: relative;
            background-image: url('{{ public_path("assets/images/logo.png") }}');
            background-repeat: no-repeat;
            background-position: center;
            background-size: 50%;
            background-color: rgba(255, 255, 255, 0.8);
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
        }
        .header img {
            width: 50px;
        }
        .header h1 {
            font-size: 14px;
            margin: 0;
            color: #c8102e;
        }
        .content {
            display: flex;
        }
        .photo {
            width: 30%;
            text-align: center;
        }
        .photo img {
            width: 80%;
            border: 2px solid #c8102e;
            border-radius: 4px;
        }
        .details {
            width: 70%;
            padding-left: 10px;
        }
        .details p {
            margin: 2px 0;
        }
        .details .label {
            font-weight: bold;
        }
        .qr-code {
            position: absolute;
            bottom: 10px;
            right: 10px;
        }
        .qr-code img {
            width: 50px;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <img src="{{ public_path('assets/images/logo.png') }}" alt="Logo PMI">
            <h1>Palang Merah Indonesia</h1>
            <p style="font-size: 8px; margin:0;">Kabupaten Batu Bara</p>
        </div>
        <div class="content">
            <div class="photo">
                <img src="{{ public_path('assets/images/avatar.png') }}" alt="Foto Pendonor">
            </div>
            <div class="details">
                <p><span class="label">Nama:</span> {{ $donor->name }}</p>
                <p><span class="label">No. KTA:</span> {{ $donor->kta_number }}</p>
                <p><span class="label">Gol. Darah:</span> {{ $donor->blood_type }} {{ $donor->rhesus }}</p>
                <p><span class="label">Alamat:</span> {{ $donor->address }}</p>
                <p><span class="label">Berlaku Hingga:</span> {{ $donor->kta_issued_at ? date('d-m-Y', strtotime('+2 years', strtotime($donor->kta_issued_at))) : '-' }}</p>
            </div>
        </div>
        <div class="qr-code">
            <img src="data:image/svg+xml;base64,{{ base64_encode(file_get_contents(storage_path('app/public/' . $donor->qr_code_path))) }}" alt="QR Code">
        </div>
    </div>
</body>
</html>
