<?php

namespace Database\Seeders\room;

use App\Models\room\BillRoom;
use App\Models\room\ReservationRoom;
use App\Models\room\Room;
use DateInterval;
use Illuminate\Database\Seeder;

class ReservationRoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bill_room_model = new BillRoom();
        $room_model = new Room();

        $bill_rooms = $bill_room_model->newQuery()->get();
        $rooms = $room_model->newQuery()->get('id');

        foreach ($bill_rooms as $bill_room) {
            $checkin_time = fake()->dateTimeBetween('-12 days', '+10 days', 'Asia/Ho_Chi_Minh');
            $checkout_time = clone $checkin_time;
            $checkout_time->add(new DateInterval('P2D'));

            $numReservations = fake()->randomElement([3, 4]);

            for ($i = 0; $i < $numReservations; $i++) {
                ReservationRoom::factory()->create([
                    'time_start' => $checkin_time,
                    'time_end' => $checkout_time,
                    'status' => 1,
                    'room_id' => fake()->randomElement($rooms),
                    'customer_id'=>$bill_room->customer_id,
                    'bill_room_id' => $bill_room->id,

                ]);
            }
        }
    }
}
