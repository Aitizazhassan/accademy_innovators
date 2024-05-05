<?php

namespace App\Services;

class CrewCodeGeneratorService
{
    public function generateCode($crewPositions)
    {
        // Sort the crew positions array
        $sortedCrewPositions = $this->sortCrewPositions($crewPositions);

        $code = '';

        $length = count($sortedCrewPositions);
        $i = 1;
        foreach ($sortedCrewPositions as $key => $crewPosition) {
            $quantity = (int) $crewPosition['quantity'];
            $occupancy = (int) $crewPosition['occupancy'];

            $code .= ($quantity == 1 ? '' : $quantity) . $crewPosition['crew_position_abbreviation'] . $occupancy . ($i === $length ? '' : '-');
            $i++;
        }

        return $code;
    }

    public function sortCrewPositions($crewPositions)
    {
        usort($crewPositions, function ($a, $b) {
            return strcmp($a['crew_position_abbreviation'], $b['crew_position_abbreviation']);
        });

        return $crewPositions;
    }
}
