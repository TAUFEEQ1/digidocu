<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\PublicationBuyer
 * @property-read \App\Publication $publication
 * @property-read \App\User $buyer
 */
class PublicationBuyer extends Model
{
    use HasFactory;
    protected $table = 'publication_buyers';

    protected $fillable = [
        'publication_id',
        'payment_ref',
        'status',
        'buyer_id',
        'purchased_at',
        'mobile_network',
        'mobile_no',
        'payment_method'
    ];

    public function publication(){
        return $this->belongsTo(Publication::class,'publication_id','id');
    }

    public function buyer(){
        return $this->belongsTo(User::class,'buyer_id','id');
    }
}
