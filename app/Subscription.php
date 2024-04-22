<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Document;

class Subscription extends Document
{
    public $table = 'documents';
    public $fillable = [
        'name',
        'description',
        'status',
        'created_by',
        'custom_fields',
        // subscription fields.
        'sub_type',
        'sub_start_date',
        'sub_end_date',
        'sub_status',
        'sub_amount',
        'sub_payment_method',
        'sub_payment_mobile_network',
        'sub_payment_mobile_no'
    ];    

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('category', function ($builder) {
            $builder->where('category', config('constants.DOC_TYPES.SUBSCRIPTION'));
        });
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


}
