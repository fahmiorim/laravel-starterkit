<?php

namespace App\Notifications;

use App\Models\DonorCard;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DonorCardIssuedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The donor card instance.
     *
     * @var DonorCard
     */
    public $donorCard;

    /**
     * Create a new notification instance.
     *
     * @param DonorCard $donorCard
     * @return void
     */
    public function __construct(DonorCard $donorCard)
    {
        $this->donorCard = $donorCard;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array<int, string>
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Kartu Donor Anda Telah Diterbitkan')
            ->greeting('Halo ' . $this->donorCard->donor->name . ',')
            ->line('Kami dengan senang hati memberitahukan bahwa kartu donor Anda telah berhasil diterbitkan.')
            ->line('Berikut adalah detail kartu donor Anda:')
            ->line('Nomor Kartu: ' . $this->donorCard->card_number)
            ->line('Tanggal Terbit: ' . $this->donorCard->issue_date->format('d F Y'))
            ->line('Masa Berlaku Sampai: ' . $this->donorCard->expiry_date->format('d F Y'))
            ->action('Lihat Detail Kartu', route('donor-cards.show', $this->donorCard->card_number))
            ->line('Harap simpan kartu ini dengan baik dan bawa saat akan melakukan donor darah berikutnya.')
            ->line('Terima kasih telah menjadi bagian dari pendonor darah.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array<string, mixed>
     */
    public function toArray($notifiable): array
    {
        return [
            'card_number' => $this->donorCard->card_number,
            'donor_name' => $this->donorCard->donor->name,
            'issue_date' => $this->donorCard->issue_date,
            'expiry_date' => $this->donorCard->expiry_date,
        ];
    }
}
