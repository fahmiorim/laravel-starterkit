<?php

namespace App\Notifications;

use App\Models\DonationSchedule;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DonationSchedulePublished extends Notification implements ShouldQueue
{
    use Queueable;

    protected $schedule;

    public function __construct(DonationSchedule $schedule)
    {
        $this->schedule = $schedule;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Jadwal Donor Baru: ' . $this->schedule->judul)
            ->greeting('Halo ' . $notifiable->name . ',')
            ->line('Jadwal donor darah baru telah dipublikasikan:')
            ->line('Judul: ' . $this->schedule->judul)
            ->line('Tanggal: ' . $this->schedule->tanggal_mulai->format('d M Y'))
            ->line('Lokasi: ' . $this->schedule->lokasi)
            ->action('Lihat Detail', route('jadwal-donor.show', $this->schedule->id))
            ->line('Terima kasih telah menjadi bagian dari pendonor kami!');
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Jadwal Donor Baru',
            'message' => 'Jadwal donor baru: ' . $this->schedule->judul . ' pada ' . $this->schedule->tanggal_mulai->format('d M Y'),
            'url' => route('jadwal-donor.show', $this->schedule->id),
            'schedule_id' => $this->schedule->id,
        ];
    }
}
