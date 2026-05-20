<?php

namespace App\Models;

use CodeIgniter\Model;

class StageModel extends Model
{
    protected $table            = 'stage';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;

    // Pouze tyto sloupce lze editovat přes model
    protected $allowedFields = [
        'number',
        'date',
        'note',
        'departure',
        'arrival',
        'distance',
        'parcour_type',
        'vertical_meters',
        'profile',
        'id_race_year',
        'link',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $useSoftDeletes = true;

    /**
     * Vrátí etapy pro daný ročník závodu
     */
    public function getByRaceYear(int $raceYearId): array
    {
        return $this->select('stage.*, parcour_type.name AS parcour_name')
                    ->join('parcour_type', 'parcour_type.id = stage.parcour_type', 'left')
                    ->where('stage.id_race_year', $raceYearId)
                    ->orderBy('stage.number', 'ASC')
                    ->orderBy('stage.date', 'ASC')
                    ->findAll();
    }
}
