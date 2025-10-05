<?php

namespace App\Services;

use App\Models\Donor;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class KtaService
{
    public function ensureKtaNumber(Donor $donor): Donor
    {
        if (! $donor->kta_number) {
            $donor->kta_number = $this->generateKtaNumber();
            $donor->kta_issued_at = now();
            $donor->save();

            return $donor->refresh();
        }

        if (! $donor->kta_issued_at) {
            $donor->kta_issued_at = now();
            $donor->save();
        }

        return $donor;
    }

    public function generate(Donor $donor)
    {
        $donor = $this->ensureKtaNumber($donor);

        $qrCodePath = 'qrcodes/' . $donor->id . '-' . time() . '.svg';
        $qrContent = route('donors.show', $donor);
        Storage::disk('public')->put($qrCodePath, QrCode::size(200)->generate($qrContent));
        $donor->qr_code_path = $qrCodePath;

        $donor->save();

        $pdf = Pdf::loadView('templates.kta', compact('donor'));
        $pdf->setPaper('a7', 'landscape');

        return $pdf;
    }

    private function generateKtaNumber(): string
    {
        $year = date('Y');
        $randomNumber = str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
        $ktaNumber = 'KTA-' . $year . '-' . $randomNumber;

        while (Donor::where('kta_number', $ktaNumber)->exists()) {
            $randomNumber = str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
            $ktaNumber = 'KTA-' . $year . '-' . $randomNumber;
        }

        return $ktaNumber;
    }
}
