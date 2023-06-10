<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Carbon\Carbon;
use App\Models\room\BillRoom;
class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    // protected function schedule(Schedule $schedule): void
    // {
    //     // $schedule->command('inspire')->hourly();
    // }
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            // Lấy danh sách các bill_room cần xóa
            $billRooms = BillRoom::whereNull('pay_time')
                ->where('created_at', '<=', Carbon::now()->subMinutes(30))
                ->get();
    
            foreach ($billRooms as $billRoom) {
                // Xóa bill_room và các reservation_room liên quan
                $billRoom->reservationRooms()->delete();
                $billRoom->delete();
            }
        })->everyMinute();
    }
    /**
     * Register the commands for the application.
     */
    // protected function commands(): void
    // {
    //     $this->load(__DIR__.'/Commands');

    //     require base_path('routes/console.php');
    // }
}
