<?php

namespace App;

use App\Document;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use NumberFormatter;
/**
 * Class CashRequest
 * 
 * @mixin \Eloquent
 * @property string $cr_reference_number
 * @property string $cr_department
 * @property string $cr_title
 * @property string $cr_purpose
 * @property string $cr_amount
 * @property int|null $finance_manager_id
 * @property int|null $hod_id
 * @property string $cr_hod_notes
 * @property int|null $internal_auditor_id
 * @property int|null $managing_director_id
 * @property string $cr_hod_at
 * */
class CashRequest extends Document
{
    use HasFactory;

    public $fillable = [
        'cr_reference_number',
        'cr_department',
        'cr_title',
        'cr_purpose',
        'cr_amount',
        "status",
        "name",
        "created_by"
    ];
    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('category', function ($builder) {
            $builder->where('category', config('constants.DOC_TYPES.CASH_REQUEST'));
        });
    }
    public function createdBy()
    {
        return $this->belongsTo(\App\User::class, 'created_by', 'id');
    }

    public function hod(){
        return $this->belongsTo(\App\User::class, 'cr_hod_id', 'id');
    }

    public function financeManager(){
        return $this->belongsTo(\App\User::class, 'cr_finance_manager_id', 'id');
    }
    
    public function internalAuditor(){
        return $this->belongsTo(\App\User::class, 'cr_internal_auditor_id', 'id');
    }

    public function managingDirector(){
        return $this->belongsTo(\App\User::class, 'cr_managing_director_id', 'id');
    }

    public function getCrAmountWordsAttribute(){
        $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
        return $f->format($this->cr_amount);
    }
}
