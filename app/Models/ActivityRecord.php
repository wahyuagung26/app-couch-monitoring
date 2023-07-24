<?php

namespace App\Models;

use CodeIgniter\Model;

class ActivityRecord extends Model
{
    protected $table = 'activity_record';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'id',
        'activity_name',
        'activity_note',
        'created_at'
    ];

    protected $db;

    public function __construct()
    {
        if($this->db === null) {
            $this->db = \Config\Database::connect();
            $this->builder = $this->db->table($this->table);
        }

        $this->db->reconnect();
    }

    public function getTotalBatuk(?string $startDate, ?string $endDate)
    {
        $query = $this->builder
                    ->select('count(id) as total')
                    ->where('created_at >=', $startDate)
                    ->where('created_at <=', $endDate)
                    ->where('activity_name', 'Batuk')
                    ->get()
                    ->getRowArray();

        return (int) $query['total'] ?? 0;
    }

    public function getByPeriode(?string $startDate, ?string $endDate)
    {
        return $this->builder
                    ->select('*')
                    ->where('created_at >=', $startDate)
                    ->where('created_at <=', $endDate)
                    ->get()
                    ->getResultArray();
    }

}
