<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Destinasi extends Model
{
    protected $table = 'destinasis';

    protected $fillable = [
        'nama',
        'deskripsi',
        'kategori',
        'alamat',
        'foto',
        'tiktok',
        'rating',
        'latitude',
        'longitude',
        'meta'
    ];

    protected $casts = [
        'meta' => 'array',
        'rating' => 'float'
    ];

    protected $attributes = [
        'latitude' => null,
        'longitude' => null,
    ];
}
