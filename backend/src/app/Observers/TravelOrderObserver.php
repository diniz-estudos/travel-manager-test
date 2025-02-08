<?php

namespace App\Observers;

use App\Models\TravelOrder;
use App\Notifications\TravelOrderStatusUpdated;

class TravelOrderObserver
{
    /**
     * Handle the TravelOrder "created" event.
     */
    public function created(TravelOrder $travelOrder): void
    {
        //
    }

    /**
     * Handle the TravelOrder "updated" event.
     */
    public function updated(TravelOrder $travelOrder): void
    {
        if ($travelOrder->isDirty('status')) {
            $travelOrder->user->notify(new TravelOrderStatusUpdated($travelOrder));
        }
    }

    /**
     * Handle the TravelOrder "deleted" event.
     */
    public function deleted(TravelOrder $travelOrder): void
    {
        //
    }

    /**
     * Handle the TravelOrder "restored" event.
     */
    public function restored(TravelOrder $travelOrder): void
    {
        //
    }

    /**
     * Handle the TravelOrder "force deleted" event.
     */
    public function forceDeleted(TravelOrder $travelOrder): void
    {
        //
    }
}
