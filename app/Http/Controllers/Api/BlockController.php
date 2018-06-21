<?php

namespace Koodilab\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use Koodilab\Http\Controllers\Controller;
use Koodilab\Models\Block;
use Koodilab\Models\User;

class BlockController extends Controller
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
     * Update.
     *
     * @param User $user
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
