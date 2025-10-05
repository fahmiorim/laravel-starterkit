<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BloodStockExpiryNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $bloodStock;
    public $daysRemaining;

    /**
     * Create a new notification instance.
     *
     * @param \App\Models\BloodStock $bloodStock
     * @param int $daysRemaining
     * @return void
     */
    public function __construct($bloodStock, $daysRemaining)
    {
        $this->bloodStock = $bloodStock;
        $this->daysRemaining = $daysRemaining;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $bloodType = $this->bloodStock->blood_type . $this->bloodStock->rhesus;
        $expiryDate = $this->bloodStock->expiry_date->format('d M Y');
        
        return (new MailMessage)
            ->subject('Peringatan: Stok Darah ' . $bloodType . ' Akan Segera Kadaluarsa')
            ->line('Stok darah dengan golongan ' . $bloodType . ' akan kadaluarsa dalam ' . $this->daysRemaining . ' hari.')
            ->line('Tanggal Kadaluarsa: ' . $expiryDate)
            ->line('Jumlah Stok: ' . $this->bloodStock->quantity . ' kantong')
            ->action('Lihat Stok Darah', route('admin.blood-stocks.index'))
            ->line('Segera lakukan pengecekan dan tindakan yang diperlukan.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $bloodType = $this->bloodStock->blood_type . $this->bloodStock->rhesus;
        
        return [
            'title' => 'Stok Darah Akan Kadaluarsa',
            'message' => 'Stok darah ' . $bloodType . ' akan kadaluarsa dalam ' . $this->daysRemaining . ' hari.',
            'url' => route('admin.blood-stocks.index'),
            'type' => 'warning',
            'blood_stock_id' => $this->bloodStock->id,
        ];
    }
}
