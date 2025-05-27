<?php

namespace App\Models;

use Database\Factories\SearchFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperSearch
 */
class Search extends Model
{
    /** @use HasFactory<SearchFactory> */
    use HasFactory;

    protected $fillable = ['search_list_id', 'term', 'field', 'limit'];

    protected $hidden = ['created_at', 'updated_at', 'search_list_id', 'user_id'];

    /* -------------------------------------------------
     * Relationships
     * -----------------------------------------------*/

    public function searchList(): BelongsTo
    {
        return $this->belongsTo(SearchList::class);
    }
}
