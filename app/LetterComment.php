<?php

namespace App;
use App\Letter;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * Class LetterComment
 * @property string $notes;
 * 
*/
class LetterComment extends Model
{
    use HasFactory;

    public $fillable = [
        "notes",
        "created_by",
    ];
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function createdBy()
    {
        return $this->belongsTo(\App\User::class, 'created_by', 'id');
    }
    public function document()
    {
        return $this->belongsTo(\App\Document::class, 'document_id', 'id');
    }
}
