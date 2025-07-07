<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = 'clients';

    protected $fillable = [
        'id',
        'name',
        'sector',
    ];

    public function alerts()
    {
        return $this->hasMany(Alert::class);
    }

    public function iocs()
    {
        return $this->hasMany(IOC::class);
    }

    public function rapports()
    {
        return $this->hasMany(Rapport::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
