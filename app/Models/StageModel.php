<?php

namespace App\Models;

use CodeIgniter\Model;

class StageModel extends Model
{
    protected $table            = 'stage';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'number', 'date', 'note', 'departure', 'arrival',
        'distance', 'parcour_type', 'vertical_meters',
        'profile', 'id_race_year', 'link',
    ];

    protected $useTimestamps = false;

    /**
     * Vrátí etapy pro daný ročník seřazené podle čísla etapy.
     */
    public function getStagesByYear(int $raceYearId): array
    {
        return $this->select('stage.*, parcour_type.name AS parcour_name, parcour_type.icon AS parcour_icon')
            ->join('parcour_type', 'parcour_type.id = stage.parcour_type', 'left')
            ->where('stage.id_race_year', $raceYearId)
            ->orderBy('stage.number', 'ASC')
            ->findAll();
    }
}