<?php

namespace App\Observers;

use App\Models\CrewPosition;

class CrewPositionObserver
{
    /**
     * Handle the CrewPosition "created" event.
     */
    public function created(CrewPosition $crewPosition): void
    {
        //
    }

    /**
     * Handle the CrewPosition "updated" event.
     */
    public function updated(CrewPosition $crewPosition): void
    {
        // Check if the hourly_rate has changed
        if ($crewPosition->isDirty('hourly_rate')) {
            // Get all crews associated with this crew position
            $crews = $crewPosition->crews;

            // Iterate through each crew and recalculate crewTotalRate
            foreach ($crews as $crew) {
                $crewTotalRate = 0;
                foreach ($crew->crewPositions as $pos) {
                    $crewTotalRate += $pos->hourly_rate * $pos->pivot->quantity;
                }

                // Update the crew's crew_total_rate
                $crew->update(['crew_total_rate' => $crewTotalRate]);
            }
        }
    }

    /**
     * Handle the CrewPosition "deleted" event.
     */
    public function deleted(CrewPosition $crewPosition): void
    {
        //
    }

    /**
     * Handle the CrewPosition "restored" event.
     */
    public function restored(CrewPosition $crewPosition): void
    {
        //
    }

    /**
     * Handle the CrewPosition "force deleted" event.
     */
    public function forceDeleted(CrewPosition $crewPosition): void
    {
        //
    }
}
