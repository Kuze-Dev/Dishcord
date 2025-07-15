<?php

namespace App\Observers;

use App\Models\User;
use Mockery\Matcher\Not;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        // Get current admin (if logged in via Filament)
        $recipient = Auth::user();
        Notification::make()
            ->title( 'New User Created')
            ->body("A new user has been created: {$user->name} ({$user->email})")
            ->sendToDatabase($recipient);



    }
    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
