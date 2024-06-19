<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use App\Document;
use App\Activity;
/**
 * Class Egazette
 * @mixin \Eloquent
 * @property string $gaz_published_on
 * @property string $gaz_issue_no
 * @property string $gaz_sub_category
 * @property string $gaz_passkey
 * @property string $status
 * @property boolean $gaz_is_downloadable
 * @property-read \App\User $createdBy
 * */
class Egazette extends Document
{

    public $table = 'documents';

    public $fillable = [
        'name',
        'description',
        'status',
        'created_by',
        'custom_fields',
        'category',
        //Egazette
        'gaz_published_on',
        'gaz_issue_no',
        'gaz_sub_category',
        'gaz_is_downloadable' 
    ];
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('category', function ($builder) {
            $builder->where('category', config('constants.DOC_TYPES.EGAZETTE'));
        });
    }

   public function newActivity($activity_text,$include_document=true){
        if($include_document){
            $activity_text .= " : ".'<a href="'.route('egazettes.show',$this->id).'">'.$this->name."</a>";
        }
        Activity::create([
            'activity' => $activity_text,
            'created_by' => $this->created_by,
            'document_id' => $this->id,
        ]);
    }
}
