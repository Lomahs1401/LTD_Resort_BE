<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\room\BillRoom;

class ClearExpiredBillRooms extends Command
{
    protected $signature = 'bill_rooms:clear_expired';
    
    protected $description = 'Clear expired bill rooms';
    
    public function handle()
    {
        $expirationTime = Carbon::now()->subMinutes(30);
        
        $expiredBillRooms = BillRoom::whereNull('pay_time')
            ->where('created_at', '<=', $expirationTime)
            ->get();
        
        foreach ($expiredBillRooms as $billRoom) {
            // Xóa các dữ liệu liên quan (ReservationRooms, ...) trước khi xóa bill_room
            $reservation_room = DB::table('reservation_rooms')->where('bill_room_id', '=', $bill_room->id)->delete();
            // Xóa bill_room
            $billRoom->delete();
        }
        
        $this->info('Expired bill rooms cleared successfully.');
    }
}
