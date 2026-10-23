<?php

namespace App\Console\Commands;

use App\Models\Reservation;
use App\Models\Product;
use Illuminate\Console\Command;
use Carbon\Carbon;

class ExpireReservations extends Command
{
    protected $signature = 'reservations:expire';
    protected $description = 'Expire pending reservations older than 24 hours and restore stock';

    public function handle()
    {
        $expiredReservations = Reservation::where('status', 'pending')
            ->where('expires_at', '<', Carbon::now())
            ->get();

        $count = 0;

        foreach ($expiredReservations as $reservation) {
            // Mark as expired
            $reservation->update(['status' => 'expired']);

            // Restore stock
            $product = Product::find($reservation->product_id);
            if ($product) {
                $product->stock = $product->stock + ($reservation->quantity ?? 1);
                $product->save();
            }

            $count++;
        }

        $this->info("Expired {$count} reservations and restored stock.");

        return Command::SUCCESS;
    }
}
