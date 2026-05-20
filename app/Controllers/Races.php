<?php

namespace App\Controllers;

use App\Models\RaceModel;
use App\Config\Pagination;

class Races extends BaseController
{
    protected RaceModel $raceModel;
    protected Pagination $paginationConfig;

    public function __construct()
    {
        $this->raceModel        = new RaceModel();
        $this->paginationConfig = config('Pagination');
        helper('cyklo');
    }

    /**
     * Seznam závodů – stránkované karty.
     */
    public function index(): string
    {
        $perPage = $this->paginationConfig->perPage;

        $data = [
            'races' => $this->raceModel->paginate($perPage),
            'pager' => $this->raceModel->pager,
            'title' => 'Přehled závodů',
        ];

        return view('layouts/main', $data + ['content' => view('races/index', $data)]);
    }
}