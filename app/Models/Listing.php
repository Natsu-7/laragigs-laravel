<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
   // protected $fillable=['title','company','description','tags','location','website','email'];
    use HasFactory;

    public function scopeFilter($query,array $filters)
    {
        if($filters['tag'] ?? false)
        {
            $query->where('tags','like','%'. request('tag'). '%');
        }

        if($filters['search'] ?? false)
        {
            $query->where('title','like','%'. request('search'). '%')
            ->orWhere('description','like','%'. request('description'). '%')
            ->orWhere('tags','like','%'. request('tag'). '%');
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}

