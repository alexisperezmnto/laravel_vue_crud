<?php

namespace App\Models;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'imagen',
        'observaciones'
    ];

    public function customers() {
        return $this->belongsToMany(Customer::class);
    }
}
