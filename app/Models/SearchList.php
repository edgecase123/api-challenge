<?php

namespace App\Models;

use Database\Factories\SearchListFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperSearchList
 */
class SearchList extends Model
{
    /** @use HasFactory<SearchListFactory> */
    use HasFactory;

    protected $fillable = ['name', 'user_id'];

    protected $hidden = ['created_at', 'updated_at', 'user_id'];

    /* -------------------------------------------------
     * Relationships
     * -----------------------------------------------*/

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function searches(): HasMany
    {
        return $this->hasMany(Search::class);
    }
}
