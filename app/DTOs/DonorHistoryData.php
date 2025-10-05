<?php

namespace App\DTOs;

use Carbon\Carbon;
use Illuminate\Http\Request;

class DonorHistoryData
{
    public function __construct(
        public readonly int $donor_id,
        public readonly ?int $blood_donation_schedule_id,
        public readonly Carbon $tanggal_donor,
        public readonly string $lokasi,
        public readonly int $jumlah_kantong,
        public readonly string $status,
        public readonly ?string $note = null
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            donor_id: (int) $data['donor_id'],
            blood_donation_schedule_id: array_key_exists('blood_donation_schedule_id', $data) && $data['blood_donation_schedule_id'] !== null
                ? (int) $data['blood_donation_schedule_id']
                : null,
            tanggal_donor: Carbon::parse($data['tanggal_donor']),
            lokasi: $data['lokasi'],
            jumlah_kantong: (int) $data['jumlah_kantong'],
            status: $data['status'],
            note: $data['note'] ?? null
        );
    }

    public static function fromRequest(Request $request): self
    {
        $validated = $request->validate([
            'donor_id' => 'required|exists:donors,id',
            'blood_donation_schedule_id' => 'nullable|exists:blood_donation_schedules,id',
            'tanggal_donor' => 'required|date',
            'lokasi' => 'required|string|max:255',
            'jumlah_kantong' => 'required|integer|min:1',
            'status' => 'required|in:pending,valid,ditolak',
            'note' => 'nullable|string',
        ]);

        return self::fromArray($validated);
    }

    public function toArray(): array
    {
        return [
            'donor_id' => $this->donor_id,
            'blood_donation_schedule_id' => $this->blood_donation_schedule_id,
            'tanggal_donor' => $this->tanggal_donor,
            'lokasi' => $this->lokasi,
            'jumlah_kantong' => $this->jumlah_kantong,
            'status' => $this->status,
            'note' => $this->note,
        ];
    }
}
