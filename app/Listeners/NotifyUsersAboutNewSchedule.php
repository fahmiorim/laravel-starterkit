<?php

namespace App\Listeners;

use App\Events\DonationScheduleCreated;
use App\Events\DonationScheduleStatusChanged;
use App\Notifications\DonationSchedulePublished;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class NotifyUsersAboutNewSchedule implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle($event)
    {
        try {
            if ($event instanceof DonationScheduleCreated) {
                $this->handleCreated($event);
            } elseif ($event instanceof DonationScheduleStatusChanged) {
                $this->handleStatusChanged($event);
            }
        } catch (\Exception $e) {
            Log::error('Error in NotifyUsersAboutNewSchedule: ' . $e->getMessage());
            throw $e;
        }
    }

    protected function handleCreated(DonationScheduleCreated $event)
    {
        // Notify admins about new schedule
        $admins = User::role('admin')->get();
        Notification::send($admins, new \App\Notifications\DonationSchedulePublished($event->schedule));
    }

    protected function handleStatusChanged(DonationScheduleStatusChanged $event)
    {
        // Only notify users when status changes to 'published'
        if ($event->newStatus === 'published') {
            $users = User::role('donor')->get();
            Notification::send($users, new \App\Notifications\DonationSchedulePublished($event->schedule));
        }
    }

    public function failed($event, $exception)
    {
        Log::error('Failed to process schedule notification: ' . $exception->getMessage(), [
            'event' => get_class($event),
            'schedule_id' => $event->schedule->id ?? null,
            'exception' => $exception->getTraceAsString()
        ]);
    }
}
