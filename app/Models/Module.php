<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'ip_address',
        'room_id',
        'module_type_id',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function moduleType()
    {
        return $this->belongsTo(ModuleType::class);
    }

    public function attendants()
    {
        return $this->belongsToMany(Attendant::class, 'module_attendant_accesses')->withTimestamps();
    }
}