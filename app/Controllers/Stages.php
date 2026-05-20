<?php

namespace App\Controllers;

use App\Models\StageModel;
use App\Models\RaceYearModel;

class Stages extends BaseController
{
    protected StageModel    $stageModel;
    protected RaceYearModel $raceYearModel;

    public function __construct()
    {
        $this->stageModel    = new StageModel();
        $this->raceYearModel = new RaceYearModel();
        helper('cyklo');
    }

    /**
     * Přehled etap daného ročníku.
     */
    public function index(int $raceYearId): string
    {
        $raceYear = $this->raceYearModel->getYearDetail($raceYearId);
        if (! $raceYear) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $stages = $this->stageModel->getStagesByYear($raceYearId);

        $data = [
            'raceYear' => $raceYear,
            'stages'   => $stages,
            'title'    => 'Etapy: ' . $raceYear['real_name'],
        ];

        return view('layouts/main', $data + ['content' => view('stages/index', $data)]);
    }
}