<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Block;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class BlockController extends Controller
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
     * Update.
     *
     * @throws \Exception|\Throwable
     *
     * @return mixed|\Illuminate\Http\Response
     */
    public function update(User $user)
    {
        $block = auth()->user()->findByBlocked($user);

        DB::transaction(function () use ($user, $block) {
            if ($block) {
                $block->delete();
            } else {
                Block::create([
                    'blocked_id' => $user->id,
                    'user_id' => auth()->id(),
                ]);
            }
        });
    }
}
