<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;

class Zone extends Model
{
    use HasFactory, SpatialTrait;

    protected $fillable = ['name', 'coordinates', 'status'];

    protected $spatial = ['coordinates']; // This informs the model that the 'coordinates' field is spatial

    public function orders()
    {
        return $this->hasMany(Order::class); // Make sure the relation is correctly defined
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
