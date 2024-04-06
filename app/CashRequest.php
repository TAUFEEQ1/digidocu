<?php

namespace App;

use App\Document;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashRequest extends Document
{
    use HasFactory;

    public $fillable = [
        'cr_reference_number',
        'cr_department',
        'cr_title',
        'cr_purpose',
        'cr_amount'
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

}
