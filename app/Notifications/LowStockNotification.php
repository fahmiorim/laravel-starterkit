<?php

namespace App\Notifications;

use App\Models\BloodStock;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LowStockNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $bloodStock;

    /**
     * Create a new notification instance.
     *
     * @param BloodStock $bloodStock
     * @return void
     */
    public function __construct(BloodStock $bloodStock)
    {
        $this->bloodStock = $bloodStock;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $bloodType = $this->bloodStock->blood_type . ' ' . $this->bloodStock->rhesus;
        
        return (new MailMessage)
            ->subject('Peringatan: Stok Darah ' . $bloodType . ' Menipis')
            ->greeting('Halo ' . $notifiable->name . ',')
            ->line('Stok darah ' . $bloodType . ' saat ini tersisa ' . $this->bloodStock->quantity . ' kantong.')
            ->line('Mohon segera lakukan pengisian ulang stok darah.')
            ->action('Lihat Stok Darah', route('admin.blood-stocks.index'))
            ->line('Terima kasih telah menggunakan sistem manajemen stok darah.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'blood_stock_id' => $this->bloodStock->id,
            'blood_type' => $this->bloodStock->blood_type,
            'rhesus' => $this->bloodStock->rhesus,
            'quantity' => $this->bloodStock->quantity,
            'message' => 'Stok darah ' . $this->bloodStock->blood_type . ' ' . $this->bloodStock->rhesus . ' menipis. Sisa: ' . $this->bloodStock->quantity,
            'url' => route('admin.blood-stocks.index')
        ];
    }
}
