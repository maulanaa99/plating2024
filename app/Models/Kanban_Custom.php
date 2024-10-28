<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kanban_Custom extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'kanban_custom';

    public function pengiriman_custom()
    {
        return $this->hasMany(Pengiriman::class,'id_masterdata','id');
    }
}
