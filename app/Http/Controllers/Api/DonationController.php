<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\DonationCreated;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DonationController extends Controller
{
    /**
     * Get the donation reward.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $key = config('donation.key');

        if (empty($key)) {
            throw new NotFoundHttpException();
        }

        if ($key != request()->get('key')) {
            throw new BadRequestHttpException();
        }

        $user = User::findNotDonated(request()->get('email'));

        if (empty($user)) {
            throw new BadRequestHttpException();
        }

        $user->forceFill([
            'solarion' => $user->solarion + config('donation.reward'),
            'donated_at' => $user->freshTimestamp(),
        ])->save();

        $user->notify(
            new DonationCreated()
        );
    }
}
