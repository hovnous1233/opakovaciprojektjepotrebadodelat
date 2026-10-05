<?php

namespace App\Models;

use CodeIgniter\Model;

class Stage extends Model
{
    protected $table            = 'stage';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [];

    public function getForRaceYears(array $raceYearIds): array
    {
        if ($raceYearIds === []) {
            return [];
        }

        $builder = $this->db->table('stage AS s')
            ->select('s.*, pt.name AS stage_type')
            ->join('parcour_type AS pt', 'pt.id = s.parcour_type', 'left')
            ->whereIn('s.id_race_year', $raceYearIds)
            ->orderBy('s.number', 'ASC')
            ->orderBy('s.date', 'ASC');

        if ($col = $this->softDeleteColumn('stage')) {
            $builder->where('s.' . $col, null);
        }

        $stages = $builder->get()->getResult();

        $winners = (new Result())->getWinners(array_map(static fn ($s) => (int) $s->id, $stages));

        $grouped = [];
        foreach ($stages as $stage) {
            $stage->winner = $winners[(int) $stage->id] ?? '—';
            $grouped[(int) $stage->id_race_year][] = $stage;
        }
        return $grouped;
    }

    public function getWithRaceYear(int $stageId): ?object
    {
        return $this
            ->select('stage.*, parcour_type.name AS stage_type, race_year.real_name, race_year.year, race_year.id_race')
            ->join('parcour_type', 'parcour_type.id = stage.parcour_type', 'left')
            ->join('race_year', 'race_year.id = stage.id_race_year', 'inner')
            ->where('stage.id', $stageId)
            ->first();
    }

    private function softDeleteColumn(string $table): ?string
    {
        foreach (['deleted_at', 'delete_at'] as $column) {
            if ($this->db->fieldExists($column, $table)) {
                return $column;
            }
        }
        return null;
    }
}