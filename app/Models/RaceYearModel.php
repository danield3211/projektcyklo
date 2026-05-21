<?php

namespace App\Models;

use CodeIgniter\Model;

class RaceYearModel extends Model
{
    protected $table            = 'race_year';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;

    // Pouze tyto sloupce lze editovat přes model
    protected $allowedFields = [
        'real_name',
        'id_race',
        'year',
        'start_date',
        'end_date',
        'uci_tour',
        'logo',
        'sex',
        'category',
        'country',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Soft delete – záznamy se fyzicky nemažou, jen se nastaví deleted_at
    protected $useSoftDeletes = true;

    /**
     * Vrátí ročníky pro daný závod (stránkovaně)
     */
    public function getByRacePaginated(int $raceId, int $perPage): array
    {
        return $this->where('id_race', $raceId)
                    ->orderBy('year', 'DESC')
                    ->paginate($perPage);
    }

    /**
     * Vrátí jeden ročník i se soft-deleted (pro přehled)
     */
    public function getByRaceAll(int $raceId): array
    {
        return $this->where('id_race', $raceId)
                    ->orderBy('year', 'DESC')
                    ->findAll();
    }
}
