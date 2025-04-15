<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'title', 'category', 'sop_type', 'region', 'file_path', 'file_type', 'uploaded_by', 'note'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function versions()
    {
        return $this->hasMany(DocumentVersion::class);
    }
}
