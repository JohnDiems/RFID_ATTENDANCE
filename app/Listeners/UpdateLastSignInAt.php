<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Auth\Events\Authenticated;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class UpdateLastSignInAt
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event)
    {
        $user = $event->user;
    
        // Refresh the user instance to get the latest data from the database
        $user->refresh();
    
        // Log the device for the current login
        $user->last_login_device = request()->userAgent();
    
        // Check if it's the first login
        if ($user->last_login_at === null) {
            // If it's the first login, set last login time and current login time to now
            $user->last_login_at = Carbon::now('Asia/Manila');
            $user->current_login_at = $user->last_login_at;
        } else {
            // If it's not the first login, update current login time and set last login time to the previous current login time
            $user->last_login_at = $user->current_login_at;
            $user->current_login_at = Carbon::now('Asia/Manila');
        }
    
        $user->save();
    }
    
}
