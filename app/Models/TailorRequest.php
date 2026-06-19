<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TailorRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'original_content',
        'job_url',
        'job_description',
        'tailored_content',
        'status',
    ];
}
