<?php

namespace App\Controllers;

use App\Models\Result;
use App\Models\Stage;
use CodeIgniter\Exceptions\PageNotFoundException;

class Vysledky extends BaseController
{
    /**
     * @param int $typeResult 1 = pořadí v etapě, 4 = pořadí po etapě
     */
    public function etapa($stageId, $typeResult)
    {
        $stageId    = (int) $stageId;
        $typeResult = (int) $typeResult;

        if (!in_array($typeResult, [1, 4], true)) {
            throw PageNotFoundException::forPageNotFound();
        }

        $stage = (new Stage())->getWithRaceYear($stageId);
        if (!$stage) {
            throw PageNotFoundException::forPageNotFound('Etapa nebyla nalezena.');
        }

        return view('vysledky', [
            'title'       => $stage->real_name . ' ' . $stage->year,
            'stage'       => $stage,
            'type_result' => $typeResult,
            'results'     => (new Result())->getClassification($stageId, $typeResult),
        ]);
    }
}
