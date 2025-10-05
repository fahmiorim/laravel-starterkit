<?php

namespace App\Console\Commands;

use App\Models\BloodStock;
use App\Models\User;
use App\Notifications\BloodStockExpiryNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class CheckExpiringBloodStocks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blood-stocks:check-expiring';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Memeriksa dan mengirim notifikasi untuk stok darah yang akan segera kadaluarsa';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memeriksa stok darah yang akan segera kadaluarsa...');
        
        // Ambil stok yang akan kadaluarsa dalam 7 hari ke depan
        $expiringStocks = BloodStock::where('expiry_date', '<=', Carbon::now()->addDays(7))
            ->where('expiry_date', '>', Carbon::now())
            ->where('quantity', '>', 0)
            ->get();

        $expiredStocks = BloodStock::where('expiry_date', '<=', Carbon::now())
            ->where('quantity', '>', 0)
            ->get();

        $totalNotified = 0;
        $adminUsers = User::role('admin')->get();

        // Kirim notifikasi untuk stok yang akan kadaluarsa
        foreach ($expiringStocks as $stock) {
            $daysRemaining = Carbon::now()->diffInDays($stock->expiry_date);
            
            // Kirim notifikasi ke semua admin
            Notification::send($adminUsers, new BloodStockExpiryNotification($stock, $daysRemaining));
            $totalNotified++;
            
            $this->line(sprintf(
                'Notifikasi dikirim untuk stok %s%s yang akan kadaluarsa dalam %d hari',
                $stock->blood_type,
                $stock->rhesus,
                $daysRemaining
            ));
        }

        // Update status stok yang sudah kadaluarsa
        if ($expiredStocks->isNotEmpty()) {
            $expiredStocks->each(function ($stock) {
                $stock->update(['quantity' => 0]);
                $this->warn(sprintf(
                    'Stok %s%s telah kadaluarsa dan diatur ke 0',
                    $stock->blood_type,
                    $stock->rhesus
                ));
            });
        }

        if ($totalNotified === 0) {
            $this->info('Tidak ada stok yang akan kadaluarsa dalam 7 hari ke depan.');
        } else {
            $this->info(sprintf('Total %d notifikasi telah dikirim.', $totalNotified));
        }
        
        return Command::SUCCESS;
    }
}
