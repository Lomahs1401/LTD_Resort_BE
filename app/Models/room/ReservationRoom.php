<?php

namespace App\Models\room;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationRoom extends Model
{
    use HasFactory;

    /**s
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'reservation_rooms';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'time_start',
        'time_end',
        'status',
        'room_id',
        'bill_room_id',
    ];
}
