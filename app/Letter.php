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

}
