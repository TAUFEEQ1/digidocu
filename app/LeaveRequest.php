<?php

namespace App;
use App\Document;

use Illuminate\Database\Eloquent\Factories\HasFactory;
/**
 * Class LeaveRequest
 * 
 * @mixin \Eloquent
 * @property string|null $lv_reference_number
 * @property string|null $lv_application_date
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

    public $fillable = [
        'lv_reference_number',
        'lv_application_date',
        'lv_designation',
        'lv_department',
        'lv_type',
        'lv_start_date',
        'lv_end_date'
    ];
}
