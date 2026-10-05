<?php

namespace App\Controllers;

use App\Models\Race;
use App\Models\RaceYear;
use App\Models\Stage;
use CodeIgniter\Exceptions\PageNotFoundException;

class Rocniky extends BaseController
{
    protected $helpers = ['form'];

    public function index($id_race)
    {
        $id_race = (int) $id_race;

        $race = (new Race())->find($id_race);
        if (!$race) {
            throw PageNotFoundException::forPageNotFound('Závod nebyl nalezen.');
        }

        // Všechny ročníky od nejnovějšího
        $rocniky = (new RaceYear())
            ->where('id_race', $id_race)
            ->orderBy('year', 'DESC')
            ->orderBy('start_date', 'DESC')
            ->findAll();

        $etapy = (new Stage())->getForRaceYears(array_map(static fn ($r) => (int) $r->id, $rocniky));

        foreach ($rocniky as $rocnik) {
            $rocnik->stages         = $etapy[(int) $rocnik->id] ?? [];
            $rocnik->total_distance = array_sum(array_map(static fn ($s) => (float) $s->distance, $rocnik->stages));
        }

        return view('rocniky', [
            'title'            => $race->default_name,
            'race'             => $race,
            'id_race'          => $id_race,
            'rocniky'          => $rocniky,
            'male_elite_races' => (new Race())->getMaleEliteRaces(),
        ]);
    }
}
