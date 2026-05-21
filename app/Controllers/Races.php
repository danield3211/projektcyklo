<?php

namespace App\Controllers;

use App\Models\RaceModel;
use App\Models\RaceYearModel;
use App\Models\StageModel;
use App\Models\UciTourTypeModel;
use Config\Cycling;

class Races extends BaseController
{
    protected RaceModel       $raceModel;
    protected RaceYearModel   $raceYearModel;
    protected StageModel      $stageModel;
    protected UciTourTypeModel $uciTourTypeModel;
    protected Cycling         $config;

    public function initController(
        \CodeIgniter\HTTP\RequestInterface $request,
        \CodeIgniter\HTTP\ResponseInterface $response,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);

        $this->raceModel        = new RaceModel();
        $this->raceYearModel    = new RaceYearModel();
        $this->stageModel       = new StageModel();
        $this->uciTourTypeModel = new UciTourTypeModel();
        $this->config           = new Cycling();
    }

    // ------------------------------------------------------------------
    // 1. Seznam závodů (stránkovaně, v kartách)
    // ------------------------------------------------------------------
    public function index(): string
    {
        $races  = $this->raceModel->getRacesPaginated($this->config->racesPerPage);
        $pager  = $this->raceModel->pager;

        return view('races/index', [
            'races' => $races,
            'pager' => $pager,
        ]);
    }

    // ------------------------------------------------------------------
    // 2. Ročníky daného závodu
    // ------------------------------------------------------------------
    public function years(int $raceId): string
    {
        $race = $this->raceModel->find($raceId);
        if (!$race) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $raceYears = $this->raceYearModel->getByRacePaginated($raceId, $this->config->raceyearsPerPage);
        $pager     = $this->raceYearModel->pager;

        // Načteme UCI Tour slovník pro zobrazení
        $uciTourTypes = $this->uciTourTypeModel->getDropdown();

        return view('races/years', [
            'race'         => $race,
            'raceYears'    => $raceYears,
            'pager'        => $pager,
            'uciTourTypes' => $uciTourTypes,
        ]);
    }

    // ------------------------------------------------------------------
    // 3. Etapy daného ročníku
    // ------------------------------------------------------------------
    public function stages(int $raceYearId): string
    {
        $raceYear = $this->raceYearModel->find($raceYearId);
        if (!$raceYear) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $race   = $this->raceModel->find($raceYear['id_race']);
        $stages = $this->stageModel->getByRaceYear($raceYearId);

        return view('races/stages', [
            'race'     => $race,
            'raceYear' => $raceYear,
            'stages'   => $stages,
        ]);
    }

    // ------------------------------------------------------------------
    // 4. Formulář: nový ročník
    // ------------------------------------------------------------------
    public function create(int $raceId): string
    {
        $race = $this->raceModel->find($raceId);
        if (!$race) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $uciTourTypes = $this->uciTourTypeModel->getDropdown();
        $years        = $this->_buildYearDropdown();

        return view('races/form', [
            'race'         => $race,
            'raceYear'     => null,
            'uciTourTypes' => $uciTourTypes,
            'years'        => $years,
        ]);
    }

    // ------------------------------------------------------------------
    // 5. Uložení nového ročníku
    // ------------------------------------------------------------------
    public function store(int $raceId): \CodeIgniter\HTTP\RedirectResponse
    {
        $race = $this->raceModel->find($raceId);
        if (!$race) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $rules = [
            'real_name' => 'required|max_length[255]',
            'year'      => 'required|integer|greater_than[1900]',
            'uci_tour'  => 'required|integer',
            'category'  => 'required|max_length[10]',
            'country'   => 'required|max_length[10]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Upload loga
        $logoName = '';
        $logo = $this->request->getFile('logo');
        if ($logo && $logo->isValid() && !$logo->hasMoved()) {
            $logoName = $logo->getRandomName();
            $logo->move(ROOTPATH . 'public/uploads/logos', $logoName);
        }

        $data = [
            'id_race'   => $raceId,
            'real_name' => $this->request->getPost('real_name'),
            'year'      => $this->request->getPost('year'),
            'uci_tour'  => $this->request->getPost('uci_tour'),
            'category'  => $this->request->getPost('category'),
            'country'   => $this->request->getPost('country'),
            'logo'      => $logoName,
            'start_date'=> $this->request->getPost('start_date') ?? date('Y-01-01'),
            'end_date'  => $this->request->getPost('end_date') ?? date('Y-01-01'),
            'sex'       => $this->request->getPost('sex') ?? '',
        ];

        $this->raceYearModel->insert($data);

        return redirect()->to(base_url("races/{$raceId}/years"))
                         ->with('success', 'Ročník byl úspěšně přidán.');
    }

    // ------------------------------------------------------------------
    // 6. Formulář: editace ročníku
    // ------------------------------------------------------------------
    public function edit(int $raceId, int $raceYearId): string
    {
        $race     = $this->raceModel->find($raceId);
        $raceYear = $this->raceYearModel->find($raceYearId);

        if (!$race || !$raceYear || $raceYear['id_race'] != $raceId) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $uciTourTypes = $this->uciTourTypeModel->getDropdown();
        $years        = $this->_buildYearDropdown();

        return view('races/form', [
            'race'         => $race,
            'raceYear'     => $raceYear,
            'uciTourTypes' => $uciTourTypes,
            'years'        => $years,
        ]);
    }

    // ------------------------------------------------------------------
    // 7. Uložení editace ročníku
    // ------------------------------------------------------------------
    public function update(int $raceId, int $raceYearId): \CodeIgniter\HTTP\RedirectResponse
    {
        $race     = $this->raceModel->find($raceId);
        $raceYear = $this->raceYearModel->find($raceYearId);

        if (!$race || !$raceYear || $raceYear['id_race'] != $raceId) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $rules = [
            'real_name' => 'required|max_length[255]',
            'year'      => 'required|integer|greater_than[1900]',
            'uci_tour'  => 'required|integer',
            'category'  => 'required|max_length[10]',
            'country'   => 'required|max_length[10]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'real_name' => $this->request->getPost('real_name'),
            'year'      => $this->request->getPost('year'),
            'uci_tour'  => $this->request->getPost('uci_tour'),
            'category'  => $this->request->getPost('category'),
            'country'   => $this->request->getPost('country'),
            'start_date'=> $this->request->getPost('start_date'),
            'end_date'  => $this->request->getPost('end_date'),
            'sex'       => $this->request->getPost('sex') ?? '',
        ];

        // Upload nového loga (volitelné)
        $logo = $this->request->getFile('logo');
        if ($logo && $logo->isValid() && !$logo->hasMoved()) {
            $logoName = $logo->getRandomName();
            $logo->move(ROOTPATH . 'uploads/logos', $logoName);
            $data['logo'] = $logoName;
        }

        $this->raceYearModel->update($raceYearId, $data);

        return redirect()->to(base_url("races/{$raceId}/years"))
                         ->with('success', 'Ročník byl úspěšně upraven.');
    }

    // ------------------------------------------------------------------
    // 8. Soft delete ročníku
    // ------------------------------------------------------------------
    public function delete(int $raceId, int $raceYearId): \CodeIgniter\HTTP\RedirectResponse
    {
        $raceYear = $this->raceYearModel->find($raceYearId);

        if (!$raceYear || $raceYear['id_race'] != $raceId) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Soft delete – nastaví deleted_at timestamp, fyzicky nesmaže
        $this->raceYearModel->delete($raceYearId);

        return redirect()->to(base_url("races/{$raceId}/years"))
                         ->with('success', 'Ročník byl odstraněn.');
    }

    // ------------------------------------------------------------------
    // Helper: dropdown let od 1900 do aktuálního roku
    // ------------------------------------------------------------------
    private function _buildYearDropdown(): array
    {
        $years = [];
        for ($y = (int)date('Y'); $y >= 1900; $y--) {
            $years[$y] = $y;
        }
        return $years;
    }
}
