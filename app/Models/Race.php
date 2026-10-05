<?php

namespace App\Models;

use CodeIgniter\Model;

class Race extends Model
{
    protected $table            = 'race';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [];

    /**
     * Mužské závody kategorie E (Elite) pro dropdown.
     * Pohlaví a kategorie se berou z ročníků závodu (tabulka race_year).
     */
    public function getMaleEliteRaces(): array
    {
        return $this
            ->select('race.id, race.default_name, race.country')
            ->join('race_year', 'race_year.id_race = race.id', 'inner')
            ->where('race_year.sex', 'M')
            ->where('race_year.category', 'E')
            ->groupBy('race.id, race.default_name, race.country')
            ->orderBy('race.default_name', 'ASC')
            ->findAll();
    }

    public function getMaleEliteRace(int $id): ?object
    {
        foreach ($this->getMaleEliteRaces() as $race) {
            if ((int) $race->id === $id) {
                return $race;
            }
        }
        return null;
    }
}
