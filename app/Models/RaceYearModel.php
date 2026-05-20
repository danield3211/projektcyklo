<?php

namespace App\Models;

use CodeIgniter\Model;

class RaceYearModel extends Model
{
    protected $table            = 'race_year';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'real_name', 'id_race', 'year', 'start_date', 'end_date',
        'uci_tour', 'logo', 'sex', 'category', 'country',
    ];

    protected $useTimestamps = false;

    /**
     * Vrátí ročníky pro daný závod včetně názvu UCI kategorie.
     */
    public function getYearsByRace(int $raceId): array
    {
        return $this->select('race_year.*, uci_tour_type.name AS uci_tour_name')
            ->join('uci_tour_type', 'uci_tour_type.id = race_year.uci_tour', 'left')
            ->where('race_year.id_race', $raceId)
            ->orderBy('race_year.year', 'DESC')
            ->findAll();
    }

    /**
     * Vrátí detail jednoho ročníku včetně názvu závodu a UCI kategorie.
     */
    public function getYearDetail(int $id): ?array
    {
        return $this->select('race_year.*, uci_tour_type.name AS uci_tour_name, race.default_name AS race_name')
            ->join('uci_tour_type', 'uci_tour_type.id = race_year.uci_tour', 'left')
            ->join('race', 'race.id = race_year.id_race', 'left')
            ->where('race_year.id', $id)
            ->first();
    }
}