<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IOC extends Model
{
    protected $fillable = ['type', 'value', 'description', 'first_seen', 'last_seen', 'client_id'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function alert()
    {
        return $this->belongsTo(Alert::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rapport()
    {
        return $this->belongsTo(Rapport::class);
    }
}
