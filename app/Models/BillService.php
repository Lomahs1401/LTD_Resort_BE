<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillService extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bill_services';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'quantity',
        'total_amount',
        'book_time',
        'payment_method',
        'pay_time',
        'checkin_time',
        'cancel_time',
        'tax',
        'discount',
        'bill_code',
        'service_id',
        'customer_id',
        'employee_id',
    ];
}
