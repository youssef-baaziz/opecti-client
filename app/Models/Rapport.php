<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rapport extends Model
{
    protected $table = 'rapports';

    protected $fillable = [
        'id',
        'file',
        'titre',
        'type',
        'client_id',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function alerts()
    {
        return $this->hasMany(Alert::class);
    }

    public function iocs()
    {
        return $this->hasMany(IOC::class);
    }
}
