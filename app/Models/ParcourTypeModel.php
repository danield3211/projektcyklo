<?php

namespace App\Models;

use CodeIgniter\Model;

class ParcourTypeModel extends Model
{
    protected $table            = 'parcour_type';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'icon'];

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