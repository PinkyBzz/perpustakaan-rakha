<?php

namespace App\Console\Commands;

use App\Models\BookReservation;
use App\Http\Controllers\BookReservationController;
use Illuminate\Console\Command;

class ExpireReservations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reservations:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire reservations that have passed the 48-hour window and notify next users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for expired reservations...');

        // Cari reservasi yang sudah notified tapi lewat expires_at
        $expiredReservations = BookReservation::where('status', BookReservation::STATUS_NOTIFIED)
            ->where('expires_at', '<', now())
            ->get();

        if ($expiredReservations->isEmpty()) {
            $this->info('No expired reservations found.');
            return 0;
        }

        $count = 0;
        foreach ($expiredReservations as $reservation) {
            // Update status jadi expired
            $reservation->update(['status' => BookReservation::STATUS_EXPIRED]);
            
            // Notify user berikutnya dalam antrian untuk buku yang sama
            BookReservationController::notifyWaitingUsers($reservation->book);
            
            $count++;
            $this->line("Expired reservation #{$reservation->id} for book: {$reservation->book->title}");
        }

        $this->info("Successfully expired {$count} reservation(s) and notified next users.");
        
        return 0;
    }
}

