<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class motorInsurance extends Model
{
    use HasFactory;
    protected $fillable = [
    'client_whatsapp_number',
    'client_name',
    'vehicle_insured_name',
    'quarter',
    'vehicle_model',
    'vehicle_registration_number',
    'vehicle_manufacture_year',
    'vehicle_engine_number',
    'vehicle_chassis_number',
    'vehicle_maker',
    'vehicle_color',
    'vehicle_cover_type',
    'vehicle_type',
    'sum_insured',
    'insurance_type'
];
}
