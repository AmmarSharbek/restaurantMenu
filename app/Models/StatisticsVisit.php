<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatisticsVisit extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'id_of_name',
        'array_of_number_of_visit',
        'array_of_date_visit',
        'array_of_city',
        'array_of_country',
        'array_of_system',
        'sumVisit',
    ];
}
