<?php

namespace Koodilab\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use Koodilab\Http\Controllers\Controller;
use Koodilab\Models\Bookmark;
use Koodilab\Models\Star;
use Koodilab\Transformers\BookmarkTransformer;

class BookmarkController extends Controller
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('verified');
        $this->middleware('player');
    }

    /**
     * Get the bookmarks in json format.
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
     * @throws \Exception|\Throwable
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
     * @throws \Exception|\Throwable
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
