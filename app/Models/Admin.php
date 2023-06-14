<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admins';

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
        'image',
        'account_id',
    ];

    // /**
    //  * Define a one-to-one relationship.
    //  *
    //  * @param  string  $related
    //  * @param  string|null  $foreignKey
    //  * @param  string|null  $localKey
    //  * @return \Illuminate\Database\Eloquent\Relations\HasOne
    //  * 
    //  * Get the account associated with the admin.
    //  */
    // public function account()
    // {
    //     /**
    //      * $related là model sẽ được link với model hiện tại với mỗi quan hệ 1-1.
    //      * 
    //      * $foreignKey là khóa ngoại của table $related ở trên dùng để liên kết giũa 2 bảng với nhau.
    //      * Mặc định thì $foreignKey sẽ là tên bảng của model hiện tại cộng với '_id', ví dụ model User thì sẽ là users_id.
    //      * 
    //      * $localKey là cột chứa dữ liệu để liên kết với bẳng của $related. 
    //      * Mặc định thì $localKey sẽ là primary key của model hiện tại.
    //      */

    //     return $this->hasOne(Account::class, 'account_id', 'admin_id');
    // }

    /**
     * Define an inverse one-to-one or many relationship.
     *
     * @param  string  $related
     * @param  string|null  $foreignKey
     * @param  string|null  $ownerKey
     * @param  string|null  $relation
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * 
     * Get the account that owns the admin.
     */
    public function account()
    {
        /**
         * $related là model muốn liên kết.
         * 
         * $foreignKey là column của bảng hiện tại sẽ dùng để liên kết. 
         * Mặc định $foreignKey sẽ là tên của phương thức cộng với primary key của $relatedModel.
         * 
         * $localKey là column của bảng $relatedModel sẽ dùng để liên kết. 
         * Mặc định $ownerKey là khóa chính của $relatedModel.
         * 
         * $foreignKey là khóa ngoại của bảng hiện tại
         */

        return $this->belongsTo(Account::class);
    }
}
