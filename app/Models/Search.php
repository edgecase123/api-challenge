<?php

namespace App\Models;

use Database\Factories\SearchFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperSearch
 */
class Search extends Model
{
    /** @use HasFactory<SearchFactory> */
    use HasFactory;

    protected $fillable = ['search_list_id', 'term', 'field', 'limit'];

    protected $hidden = ['created_at', 'updated_at', 'search_list_id'];
}
