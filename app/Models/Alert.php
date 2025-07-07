<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    protected $fillable = ['title', 'description', 'status', 'severity', 'client_id'];

    public function client() {
        return $this->belongsTo(Client::class);
    }

    public function iocs() {
        return $this->hasMany(IOC::class);
    }

    public function comments() {
        return $this->hasMany(Comment::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function rapport() {
        return $this->belongsTo(Rapport::class);
    }
}
