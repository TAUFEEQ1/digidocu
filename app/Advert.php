<?php

namespace App;

use App\Document;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * Class Advert
 * @mixin \Eloquent
 * @property string $status
 * @property string $ad_subtitle
 * @property string $ad_category
 * @property string $ad_payment_mobile_no
 * @property string $description
 * @property string $created_at
 * @property-read \App\User $createdBy
 */
class Advert extends Document
{
    use HasFactory;

    public $fillable = [
        'name',
        'description',
        'status',
        'created_by',
        'custom_fields',
        'category',
        // Advert
        'ad_subtitle',
        'ad_registrar_id',
        'ad_payment_method',
        'ad_payment_mobile_network',
        'ad_amount',
        'ad_category'
    ];
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('category', function ($builder) {
            $builder->where('category', config('constants.DOC_TYPES.ADVERT'));
        });
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function registrar()
    {
        return $this->belongsTo(\App\User::class, 'ad_registrar_id', 'id');
    }
}
