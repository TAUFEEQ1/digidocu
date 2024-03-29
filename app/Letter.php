<?php
use App\Document;
use Illuminate\Database\Eloquent\Model;

class Letter extends Document
{
    protected $table = 'documents'; // Default table name
    
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
}
