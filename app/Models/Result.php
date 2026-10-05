<?php

namespace App\Models;

use CodeIgniter\Model;

class Result extends Model
{
    protected $table            = 'result';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [];

    public function getClassification(int $stageId, int $typeResult): array
    {
        $builder = $this->db->table('result AS res')
            ->select("res.*, CONCAT(rd.first_name, ' ', rd.last_name) AS rider_name", false)
            ->join('rider AS rd', 'rd.id = res.id_rider', 'left')
            ->where('res.id_stage', $stageId)
            ->where('res.type_result', $typeResult)
            ->where('res.rank >', 0)
            ->orderBy('res.rank', 'ASC');

        if ($col = $this->softDeleteColumn('result')) {
            $builder->where('res.' . $col, null);
        }

        return $builder->get()->getResult();
    }

    public function getWinners(array $stageIds): array
    {
        if ($stageIds === []) {
            return [];
        }

        $builder = $this->db->table('result AS res')
            ->select("res.id_stage, CONCAT(rd.first_name, ' ', rd.last_name) AS rider_name", false)
            ->join('rider AS rd', 'rd.id = res.id_rider', 'left')
            ->whereIn('res.id_stage', $stageIds)
            ->where('res.type_result', 1)
            ->where('res.rank', 1);

        if ($col = $this->softDeleteColumn('result')) {
            $builder->where('res.' . $col, null);
        }

        $rows = $builder->get()->getResult();

        $winners = [];
        foreach ($rows as $row) {
            $winners[(int) $row->id_stage] ??= $row->rider_name;
        }
        return $winners;
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