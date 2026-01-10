<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppStatistic extends Model
{
    protected $table = 'app_statistics';

    protected $fillable = [
        'type',
        'icon',
        'title',
        'count',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];
}