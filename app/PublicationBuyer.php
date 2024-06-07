<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'mobile_no'
    ];

    public function publication(){
        return $this->belongsTo(Publication::class,'publication_id','id');
    }

    public function buyer(){
        return $this->belongsTo(User::class,'buyer_id','id');
    }
}
