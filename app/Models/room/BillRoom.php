<?php

namespace App\Models\room;

use App\Models\user\Customer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillRoom extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bill_rooms';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'total_amount',
        'total_room',
        'total_people',
        'payment_method',
        'pay_time',
        'checkin_time',
        'checkout_time',
        'cancel_time',
        'tax',
        'discount',
        'customer_id',
        'employee_id',
    ];
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
  
}
