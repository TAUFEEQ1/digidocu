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
        'purchased_at'
    ];


}
