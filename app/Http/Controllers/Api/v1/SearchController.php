<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\AbstractController;
use App\Models\Search;
use App\Models\SearchList;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class SearchController extends AbstractController
{
    // Would not be called if user not logged in
    public function getSearchLists(): JsonResponse
    {
        $user = Auth::user();
        $lists = $user->searchLists()->orderBy('name')->get();

        return response()->json($lists);
    }

    public function removeSearchList(int $searchListId): JsonResponse
    {
        $user = Auth::user();
        $searchList = $user->searchLists()->find($searchListId);

        if (!$searchList instanceof SearchList) {
            return $this->jsonError('Search list unknown');
        }

        $searchList->delete();

        return $this->jsonSuccess();
    }

    public function createSearchList(): JsonResponse
    {
        $params = $this->validateInput([
            'name' => 'regex:/^[a-zA-Z0-9\s]+$/|min:3'
        ]);

        $user = Auth::user();

        $existingSearchList = $user->searchLists()->where('name', $params['name'])->first();

        if ($existingSearchList !== null) {
            return $this->jsonError('Attempt create duplicate SearchList', 419);
        }

        $newSearchList = SearchList::make([
            'name' => $params['name'],
        ]);

        $user->searchLists()->save($newSearchList);

        return response()->json($newSearchList);
    }

    public function createSearch(int $searchListId): JsonResponse
    {
        $params = $this->validateInput([
            'term' => 'required|alpha|min:3',
            'field' => 'required|alpha|min:3'
        ]);

        $user = Auth::user();
        $searchList = $user->searchLists()->find($searchListId);

        if (!$searchList instanceof SearchList) {
            return $this->jsonError('Search list unknown');
        }

        // Has already?
        $existingSearch = $searchList->searches()->where([
            'term' => $params['term'],
            'field' => $params['field'],
        ])->first();

        if ($existingSearch !== null) {
            return $this->jsonMessage('Duplicate attempted', 409);
        }

        $newSearch = Search::make([
            'term' => $params['term'],
            'field' => $params['field'],
        ]);

        $searchList->searches()->save($newSearch);

        return response()->json($newSearch);
    }
    public function getSearches(int $searchListId): JsonResponse
    {
        $user = Auth::user();
        $searchList = $user->searchLists()->find($searchListId);

        if (!$searchList instanceof SearchList) {
            return $this->jsonError('Search list unknown');
        }

        return response()->json($searchList->searches()
            ->orderBy('updated_at', 'desc')
            ->get()->makeVisible('updated_at'));
    }

    public function removeSearch(int $searchListId, int $searchId): JsonResponse
    {
        $user = Auth::user();
        $searchList = $user->searchLists()->find($searchListId);

        if (!$searchList instanceof SearchList) {
            return $this->jsonError('Invalid search list. Not found');
        }

        $search = $searchList->searches()->find($searchId);

        if (!$search instanceof Search) {
            return $this->jsonMessage('Invalid search. Not found');
        }

        $search->delete();

        return $this->jsonSuccess();
    }
}
