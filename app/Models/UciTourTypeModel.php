<?php

namespace App\Models;

use CodeIgniter\Model;

class UciTourTypeModel extends Model
{
    protected $table            = 'uci_tour_type';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name'];

    protected $useTimestamps = false;

    /**
     * Vrátí asociativní pole id => name pro dropdown.
     */
    public function getDropdown(): array
    {
        $rows   = $this->findAll();
        $result = [];
        foreach ($rows as $row) {
            $result[$row['id']] = $row['name'];
        }
        return $result;
    }
}