<?php

namespace App\Controllers;

use App\Models\RaceYearModel;
use App\Models\RaceModel;
use App\Models\UciTourTypeModel;
use App\Config\Pagination;

class RaceYears extends BaseController
{
    protected RaceYearModel    $raceYearModel;
    protected RaceModel        $raceModel;
    protected UciTourTypeModel $uciModel;
    protected Pagination       $paginationConfig;

    public function __construct()
    {
        $this->raceYearModel    = new RaceYearModel();
        $this->raceModel        = new RaceModel();
        $this->uciModel         = new UciTourTypeModel();
        $this->paginationConfig = config('Pagination');
        helper('cyklo');
    }

    /**
     * Ročníky daného závodu.
     */
    public function index(int $raceId): string
    {
        $race = $this->raceModel->find($raceId);
        if (! $race) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $years = $this->raceYearModel->getYearsByRace($raceId);

        $data = [
            'race'  => $race,
            'years' => $years,
            'title' => 'Ročníky: ' . $race['default_name'],
        ];

        return view('layouts/main', $data + ['content' => view('race_years/index', $data)]);
    }

    /**
     * Formulář pro přidání ročníku.
     */
    public function create(int $raceId): string
    {
        $race = $this->raceModel->find($raceId);
        if (! $race) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'race'       => $race,
            'uciTypes'   => $this->uciModel->getDropdown(),
            'years'      => year_range(),
            'raceYear'   => null,
            'title'      => 'Přidat ročník',
        ];

        return view('layouts/main', $data + ['content' => view('race_years/form', $data)]);
    }

    /**
     * Uložení nového ročníku.
     */
    public function store()
    {
        $raceId = (int) $this->request->getPost('id_race');
        $race   = $this->raceModel->find($raceId);
        if (! $race) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $logoName = $this->uploadLogo();

        $data = [
            'id_race'    => $raceId,
            'real_name'  => $this->request->getPost('real_name'),
            'year'       => $this->request->getPost('year'),
            'start_date' => $this->request->getPost('start_date'),
            'end_date'   => $this->request->getPost('end_date') ?: null,
            'uci_tour'   => $this->request->getPost('uci_tour') ?: null,
            'logo'       => $logoName ?? '',
            'sex'        => $this->request->getPost('sex') ?? '',
            'category'   => $this->request->getPost('category') ?? '',
            'country'    => $this->request->getPost('country') ?? '',
        ];

        $this->raceYearModel->insert($data);

        return redirect()->to(base_url('races/' . $raceId . '/years'))->with('success', 'Ročník byl úspěšně přidán.');
    }

    /**
     * Formulář pro editaci ročníku.
     */
    public function edit(int $raceId, int $id): string
    {
        $race     = $this->raceModel->find($raceId);
        $raceYear = $this->raceYearModel->find($id);
        if (! $race || ! $raceYear) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'race'     => $race,
            'raceYear' => $raceYear,
            'uciTypes' => $this->uciModel->getDropdown(),
            'years'    => year_range(),
            'title'    => 'Upravit ročník',
        ];

        return view('layouts/main', $data + ['content' => view('race_years/form', $data)]);
    }

    /**
     * Uložení editace ročníku.
     */
    public function update(int $raceId, int $id)
    {
        $race     = $this->raceModel->find($raceId);
        $raceYear = $this->raceYearModel->find($id);
        if (! $race || ! $raceYear) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $logoName = $this->uploadLogo() ?? $raceYear['logo'];

        $data = [
            'real_name'  => $this->request->getPost('real_name'),
            'year'       => $this->request->getPost('year'),
            'start_date' => $this->request->getPost('start_date'),
            'end_date'   => $this->request->getPost('end_date') ?: null,
            'uci_tour'   => $this->request->getPost('uci_tour') ?: null,
            'logo'       => $logoName,
            'sex'        => $this->request->getPost('sex') ?? '',
            'category'   => $this->request->getPost('category') ?? '',
            'country'    => $this->request->getPost('country') ?? '',
        ];

        $this->raceYearModel->update($id, $data);

        return redirect()->to(base_url('races/' . $raceId . '/years'))->with('success', 'Ročník byl úspěšně upraven.');
    }

    /**
     * Soft delete ročníku.
     */
    public function delete(int $raceId, int $id)
    {
        $raceYear = $this->raceYearModel->find($id);
        if (! $raceYear) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $this->raceYearModel->delete($id);

        return redirect()->to(base_url('races/' . $raceId . '/years'))->with('success', 'Ročník byl smazán.');
    }

    /**
     * Upload loga.
     */
    private function uploadLogo(): ?string
    {
        $file = $this->request->getFile('logo');
        if ($file && $file->isValid() && ! $file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(ROOTPATH . 'public/uploads/logos', $newName);
            return $newName;
        }
        return null;
    }
}