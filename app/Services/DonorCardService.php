<?php

namespace App\Services;

use App\Events\DonorCardCreated;
use App\Models\DonorCard;
use App\Repositories\Contracts\DonorCardRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class DonorCardService
{
    protected $donorCardRepository;

    public function __construct(DonorCardRepositoryInterface $donorCardRepository)
    {
        $this->donorCardRepository = $donorCardRepository;
    }
    /**
     * Generate a unique card number
     *
     * @return string
     */
    public function generateCardNumber(): string
    {
        do {
            $number = 'KDP-' . date('Ymd') . '-' . strtoupper(Str::random(6));
        } while ($this->donorCardRepository->findByCardNumber($number) !== null);

        return $number;
    }

    /**
     * Generate and save QR code
     *
     * @param string $cardNumber
     * @return string
     */
    public function generateQrCode(string $cardNumber): string
    {
        $qrContent = route('donor-cards.show', ['donor_card' => $cardNumber]);
        $qrCode = QrCode::format('png')
            ->size(300)
            ->generate($qrContent);
        
        $qrPath = 'donor-cards/qrcodes/' . Str::slug($cardNumber) . '.png';
        Storage::disk('public')->put($qrPath, $qrCode);
        
        return $qrPath;
    }

    /**
     * Handle photo upload
     *
     * @param mixed $photo
     * @return string
     */
    public function uploadPhoto($photo): string
    {
        return $photo->store('donor-cards/photos', 'public');
    }

    /**
     * Create a new donor card
     *
     * @param array $data
     * @return DonorCard
     */
    public function createDonorCard(array $data): DonorCard
    {
        $data['card_number'] = $this->generateCardNumber();
        $data['status'] = 'active';
        
        if (isset($data['photo'])) {
            $data['card_photo_path'] = $this->uploadPhoto($data['photo']);
            unset($data['photo']);
        }
        
        $data['qr_code_path'] = $this->generateQrCode($data['card_number']);
        
$donorCard = $this->donorCardRepository->create($data);
        
        // Trigger event
        event(new DonorCardCreated($donorCard));
        
        return $donorCard;
    }

    /**
     * Update an existing donor card
     *
     * @param DonorCard $donorCard
     * @param array $data
     * @return DonorCard
     */
    public function updateDonorCard(DonorCard $donorCard, array $data): DonorCard
    {
        if (isset($data['photo'])) {
            // Delete old photo if exists
            if ($donorCard->card_photo_path) {
                Storage::disk('public')->delete($donorCard->card_photo_path);
            }
            $data['card_photo_path'] = $this->uploadPhoto($data['photo']);
            unset($data['photo']);
        }
        
$this->donorCardRepository->update($donorCard, $data);
        
        return $this->donorCardRepository->find($donorCard->id);
    }
}
