<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Favorite extends Model
{
    // The table associated with the model.
    protected $table = 'watchlists';

    protected $fillable = [
        'user_id',
        'country_id',
    ];

    /**
     * Get the user that owns the favorite.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the country that is favorited.
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
