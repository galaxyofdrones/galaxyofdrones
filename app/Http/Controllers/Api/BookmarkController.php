<?php

namespace Koodilab\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use Koodilab\Http\Controllers\Controller;
use Koodilab\Models\Bookmark;
use Koodilab\Models\Star;
use Koodilab\Models\Transformers\BookmarkTransformer;

class BookmarkController extends Controller
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('player');
    }

    /**
     * Get the bookmarks in json format.
     *
     * @param BookmarkTransformer $transformer
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(BookmarkTransformer $transformer)
    {
        /** @var \Koodilab\Models\User $user */
        $user = auth()->user();

        return $transformer->transformCollection(
            $user->bookmarks()
                ->with('star')
                ->latest()
                ->paginate()
        );
    }

    /**
     * Store a newly created bookmark in storage.
     *
     * @param Star $star
     *
     * @return mixed|\Illuminate\Http\Response
     */
    public function store(Star $star)
    {
        DB::transaction(function () use ($star) {
            Bookmark::firstOrCreate([
                'user_id' => auth()->id(),
                'star_id' => $star->id,
            ], [
                'name' => $star->name,
            ]);
        });
    }

    /**
     * Remove the bookmark from storage.
     *
     * @param Bookmark $bookmark
     *
     * @return mixed|\Illuminate\Http\Response
     */
    public function destroy(Bookmark $bookmark)
    {
        $this->authorize('destroy', $bookmark);

        DB::transaction(function () use ($bookmark) {
            $bookmark->delete();
        });
    }
}
