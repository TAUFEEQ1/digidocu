<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Document;
/**
 * Class Advert
 * @mixin \Eloquent
 * @property string $status
 * @property string $pub_title
 * @property int $pub_fees
 * @property string $pub_author
 * 
 * **/
class Publication extends Document
{
    use HasFactory;

    public $table = 'documents';

    public $fillable = [
        'pub_title',
        'pub_fees',
        'pub_author',
        "name",
        "created_by",
        "status",
        "category"
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('category', function ($builder) {
            $builder->where('category', config('constants.DOC_TYPES.PUBLICATION'));
        });
    }

    public function buyers(){
        return $this->hasMany(PublicationBuyer::class, 'publication_id', 'id');
    }

    public function getIsBoughtAttribute()
    {
        return $this->buyers()
        ->where("buyer_id",auth()->id())
        ->where("status",config("constants.ADVERT_STATES.PAID"))
        ->exists();
    }
    
    public function getBeingBoughtAttribute()
    {
        $latestBuyer = $this->buyers()
        ->where("buyer_id",auth()->id())
        ->latest();
        return $latestBuyer ? $latestBuyer->status === config('constants.ADVERT_STATES.PENDING PAYMENT') : false;
    }
}
