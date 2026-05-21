<?php

namespace App\Models;

use CodeIgniter\Model;

class RaceModel extends Model
{
    protected $table            = 'race';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    // Pouze tyto sloupce lze editovat přes model
    protected $allowedFields = [
        'default_name',
        'link',
        'country',
        'type',
    ];

    protected $useTimestamps = false;

    /**
     * Vrátí stránkovaný seznam závodů
     */
    public function getRacesPaginated(int $perPage): array
    {
        return $this->paginate($perPage);
    }
}
