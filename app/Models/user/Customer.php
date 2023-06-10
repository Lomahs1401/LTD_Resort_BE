<?php

namespace App\Models\user;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'customers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'full_name',
        'gender',
        'birthday',
        'CMND',
        'address',  
        'phone',
        'ranking_point',
        'account_id',
        'ranking_id'
    ];
    
    public function account()
    {
        return $this->belongsTo(Account::class, 'id');
    }

    public function billroom()
    {
        return $this->hasMany(Customer::class);
    }
}
