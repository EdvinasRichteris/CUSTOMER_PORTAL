<?php

namespace App\Listeners;

use Laravel\Passport\Events\AccessTokenCreated;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class UpdateTokenWithRole
{
    public function handle(AccessTokenCreated $event)
    {
        $userRole = User::find($event->userId)->role;

        DB::table('oauth_access_tokens')
            ->where('id', $event->tokenId)
            ->update(['role' => $userRole]);
    }
}
