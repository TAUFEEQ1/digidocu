<?php

namespace App;
use App\Document;

use Illuminate\Database\Eloquent\Factories\HasFactory;
/**
 * Class LeaveRequest
 * 
 * @mixin \Eloquent
 * @property string|null $lv_reference_number
 * @property string|null $lv_designation
 * @property string $lv_type
 * @property string $lv_start_date
 * @property string $lv_end_date
 
 * @property int $lv_line_manager_id
 * @property string $lv_line_manager_notes
 * @property int $lv_hr_manager_id
 * @property int $lv_hr_manager_notes
 * @property int $lv_managing_director_id
 * @property int $lv_managing_director_notes
 * @property string|null $lv_line_managed_at
 * @property string|null $lv_hr_managed_at
 * @property string|null $lv_managing_directed_at
 * @property-read \App\User|null $lineManager
 * @property-read \App\User|null $hrManager
 * @property-read \App\User|null $managingDirector
 */
class LeaveRequest extends Document
{
    use HasFactory;
    public $table = 'documents'; // Default table name


    public $fillable = [
        'lv_reference_number',
        'lv_application_date',
        'lv_designation',
        'lv_department',
        'lv_type',
        'lv_start_date',
        'lv_end_date'
    ];
    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('category', function ($builder) {
            $builder->where('category', config('constants.DOC_TYPES.LEAVE_REQUESTS'));
        });
    }

    public function createdBy()
    {
        return $this->belongsTo(\App\User::class, 'created_by', 'id');
    }
    public function lineManager(){
        return $this->belongsTo(\App\User::class, 'lv_line_manager_id', 'id');
    }
    public function hrManager(){
        return $this->belongsTo(\App\User::class, 'lv_hr_manager_id', 'id');
    }
    public function managingDirector(){
        return $this->belongsTo(\App\User::class, 'lv_managing_director_id', 'id');
    }
    public function newActivity($activity_text,$include_document=true){
        if($include_document){
            $activity_text .= " : ".'<a href="'.route('leave_requests.show',$this->id).'">'.$this->name."</a>";
        }
        Activity::create([
            'activity' => $activity_text,
            'created_by' => \Auth::id(),
            'document_id' => $this->id,
        ]);
    }
}
