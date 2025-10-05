<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BloodRequestStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected string $hospitalName,
        protected string $bloodType,
        protected string $status,
        protected int $quantity,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Status Permintaan Darah Diperbarui')
            ->line("Permintaan darah untuk {$this->hospitalName} telah diperbarui.")
            ->line("Golongan: {$this->bloodType}")
            ->line("Jumlah: {$this->quantity} kantong")
            ->line("Status terbaru: " . ucfirst($this->status));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'hospital_name' => $this->hospitalName,
            'blood_type' => $this->bloodType,
            'quantity' => $this->quantity,
            'status' => $this->status,
        ];
    }
}
