<?php

namespace App\Services;

use App\Models\BloodRequest;
use App\Models\DonationSchedule;
use App\Notifications\BloodRequestStatusNotification;
use App\Notifications\DonorScheduleNotification;
use Illuminate\Support\Facades\Notification;

class NotificationService
{
    public function notifySchedulePublished(iterable $recipients, DonationSchedule $schedule): void
    {
        Notification::send($recipients, new DonorScheduleNotification(
            title: 'Jadwal Donor Baru Dipublikasikan',
            message: $schedule->judul . ' telah dipublikasikan. Segera lakukan persiapan.',
            actionUrl: route('jadwal-donor.show', $schedule->slug),
        ));
    }

    public function notifyBloodRequestStatus(BloodRequest $request): void
    {
        if (! $request->relationLoaded('processor')) {
            $request->load('processor');
        }

        if (! $request->processor) {
            return;
        }

        $request->processor->notify(new BloodRequestStatusNotification(
            hospitalName: $request->hospital_name,
            bloodType: $request->blood_type . ' ' . $request->rhesus,
            status: $request->status,
            quantity: $request->quantity,
        ));
    }
}
