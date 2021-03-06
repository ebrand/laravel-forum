<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
	protected $guarded = [];
	
	public function owner()
	{
		return $this->belongsTo(User::class, 'user_id');
	}

	public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favorited');
    }

    public function favorite($userId)
    {
        $attributes = ['user_id' => $userId];

        if( ! $this->favorites()->where($attributes)->exists())
        {
            return $this->favorites()->create($attributes);
        }
    }

    public function isFavorited($userId)
    {
        return $this->favorites()->where('user_id', $userId)->exists();
    }
}