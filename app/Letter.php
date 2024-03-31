<?php
namespace App;
use App\Document;
use Illuminate\Database\Eloquent\Model;
/**
 * Class Letter
 * 
 * @mixin \Eloquent
 * @property string|null $description
 * @property string|null $sender
 * @property string $subject
 * @property string $sending_entity
 * @property int $executed_by
 * @property string $lt_executor_notes
 * @property string $lt_manager_notes
 * @property int $managed_by
 * @property int $assigned_to
 * @property string|null $executed_at
 * @property string|null $managed_at
 * @property string|null $assigned_at
 * @property-read \App\User|null $executedBy
 * @property-read \App\User|null $managedBy
 * @property-read \App\User|null $assignedTo
 */

class Letter extends Document
{
    public $table = 'documents'; // Default table name
    
    public $fillable = [
        'name',
        'description',
        'status',
        'created_by',
        'custom_fields',
        'verified_at',
        'verified_by',
        // lettet fields.
        'subject',
        'sender',
        'sending_entity',
        'category'
    ];
    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('category', function ($builder) {
            $builder->where('category', config('constants.DOC_TYPES.LETTER'));
        });
    }

    public function executedBy(){
        return $this->belongsTo(\App\User::class, 'executed_by', 'id');
    }
    
    public function managedBy(){
        return $this->belongsTo(\App\User::class, 'managed_by', 'id');
    }

    public function assignedTo(){
        return $this->belongsTo(\App\User::class, 'assigned_to', 'id');
    }

    public function newActivity($activity_text,$include_document=true){
        if($include_document){
            $activity_text .= " : ".'<a href="'.route('letters.show',$this->id).'">'.$this->name."</a>";
        }
        Activity::create([
            'activity' => $activity_text,
            'created_by' => \Auth::id(),
            'document_id' => $this->id,
        ]);
    }
}
