<?php

namespace App\Models;

use CodeIgniter\Model;

class UciTourTypeModel extends Model
{
    protected $table         = 'uci_tour_type';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $protectFields = true;
    protected $allowedFields = ['name'];
    protected $useTimestamps = true;
   

    /**
     * Vrátí asociativní pole id => name pro použití v dropdownu
     */
    public function getDropdown(): array
    {
        $rows = $this->orderBy('name', 'ASC')->findAll();
        $result = [];
        foreach ($rows as $row) {
            $result[$row['id']] = $row['name'];
        }
        return $result;
    }
}
