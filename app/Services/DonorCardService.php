<?php

namespace App\Services;

use App\Events\DonorCardCreated;
use App\Models\Donor;
use App\Models\DonorCard;
use App\Repositories\Contracts\DonorCardRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class DonorCardService
{
    public function __construct(
        protected DonorCardRepositoryInterface $donorCardRepository
    ) {}

    public function getPaginatedCards(int $perPage = 15): LengthAwarePaginator
    {
        return $this->donorCardRepository->paginate($perPage);
    }

    public function getDonorsWithoutCard(): Collection
    {
        return Donor::doesntHave('donorCard')
            ->orderBy('name')
            ->get();
    }

    public function getDonorCardById(int $id): ?DonorCard
    {
        return $this->donorCardRepository->find($id);
    }

    public function generateCardNumber(): string
    {
        do {
            $number = 'KDP-' . date('Ymd') . '-' . strtoupper(Str::random(6));
        } while ($this->donorCardRepository->findByCardNumber($number) !== null);

        return $number;
    }

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

    public function uploadPhoto($photo): string
    {
        return $photo->store('donor-cards/photos', 'public');
    }

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

        event(new DonorCardCreated($donorCard));

        return $donorCard;
    }

    public function updateDonorCard(DonorCard $donorCard, array $data): DonorCard
    {
        if (isset($data['photo'])) {
            if ($donorCard->card_photo_path) {
                Storage::disk('public')->delete($donorCard->card_photo_path);
            }
            $data['card_photo_path'] = $this->uploadPhoto($data['photo']);
            unset($data['photo']);
        }

        $this->donorCardRepository->update($donorCard, $data);

        return $this->donorCardRepository->find($donorCard->id);
    }

    public function deleteDonorCard(DonorCard $donorCard): bool
    {
        if ($donorCard->qr_code_path) {
            Storage::disk('public')->delete($donorCard->qr_code_path);
        }

        if ($donorCard->card_photo_path) {
            Storage::disk('public')->delete($donorCard->card_photo_path);
        }

        return $this->donorCardRepository->delete($donorCard);
    }
}
