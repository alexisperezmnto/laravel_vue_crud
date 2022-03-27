<?php

namespace App\Models;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'imagen',
        'cedula',
        'email',
        'telefono',
        'observaciones'
    ];

    public function services() {
        return $this->belongsToMany(Service::class);
    }
}
