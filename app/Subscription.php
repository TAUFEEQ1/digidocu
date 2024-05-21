<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Document;

/**
 * Class Subscription
 * @mixin \Eloquent
 * @property string $sub_type
 * @property string $sub_start_date
 * @property string $sub_end_date
 * @property int $sub_amount
 * @property string $sub_payment_status
 * @property string $sub_payment_method
 * @property string $sub_payment_mobile_network
 * @property string $sub_payment_mobile_no
 * @property string $sub_payment_ref
 * @property string $sub_payment_notes
 * */

class Subscription extends Document
{
    public $table = 'documents';
    public $fillable = [
        'name',
        'description',
        'status',
        'created_by',
        'custom_fields',
        'category',
        // subscription fields.
        'sub_type',
        'sub_start_date',
        'sub_end_date',
        'sub_status',
        'sub_amount',
        'sub_payment_method',
        'sub_payment_mobile_network',
        'sub_payment_mobile_no',
        'sub_payment_status',
        'sub_payment_ref'
    ];    

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('category', function ($builder) {
            $builder->where('category', config('constants.DOC_TYPES.SUBSCRIPTION'));
        });
    }
    public function getIsActiveAttribute()
    {
        
        $today = now();
        
        return $this->sub_start_date <= $today && $this->sub_end_date >= $today;
    }
   
    public function newActivity($activity_text,$include_document=true){
        if($include_document){
            $activity_text .= " : ".'<a href="'.route('subscriptions.show',$this->id).'">'.$this->name."</a>";
        }
        Activity::create([
            'activity' => $activity_text,
            'created_by' => $this->created_by,
            'document_id' => $this->id,
        ]);
    }

    public function createdBy()
    {
        return $this->belongsTo(\App\User::class, 'created_by', 'id');
    }


}
