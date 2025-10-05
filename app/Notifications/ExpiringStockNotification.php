<?php

namespace App\Notifications;

use App\Models\BloodStock;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExpiringStockNotification extends Notification implements ShouldQueue
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
        $expiryDate = Carbon::parse($this->bloodStock->expiry_date)->format('d F Y');
        $daysLeft = Carbon::now()->diffInDays($this->bloodStock->expiry_date);
        
        return (new MailMessage)
            ->subject('Peringatan: Stok Darah ' . $bloodType . ' Akan Segera Kadaluarsa')
            ->greeting('Halo ' . $notifiable->name . ',')
            ->line('Stok darah ' . $bloodType . ' akan kadaluarsa pada ' . $expiryDate . ' (' . $daysLeft . ' hari lagi).')
            ->line('Jumlah stok: ' . $this->bloodStock->quantity . ' kantong')
            ->line('Mohon segera lakukan pengecekan dan penanganan lebih lanjut.')
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
        $expiryDate = Carbon::parse($this->bloodStock->expiry_date)->format('d F Y');
        $daysLeft = Carbon::now()->diffInDays($this->bloodStock->expiry_date);
        
        return [
            'blood_stock_id' => $this->bloodStock->id,
            'blood_type' => $this->bloodStock->blood_type,
            'rhesus' => $this->bloodStock->rhesus,
            'quantity' => $this->bloodStock->quantity,
            'expiry_date' => $this->bloodStock->expiry_date,
            'message' => 'Stok darah ' . $this->bloodStock->blood_type . ' ' . $this->bloodStock->rhesus . ' akan kadaluarsa pada ' . $expiryDate . ' (' . $daysLeft . ' hari lagi)',
            'url' => route('admin.blood-stocks.index')
        ];
    }
}
