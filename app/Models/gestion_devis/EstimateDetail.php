<?php

namespace App\Models\gestion_devis;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstimateDetail extends Model
{
    use HasFactory;
    protected $table = 'estimate_detail';
    protected $primaryKey = 'id_estimate_detail';
}
