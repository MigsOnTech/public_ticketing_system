<?php

namespace App\Models;

use CodeIgniter\Model;

class Office extends Model
{
    protected $table            = 'office';
    protected $primaryKey       = 'office_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['office_name','office_code','divisionorsection_name','divisionorsection_code'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'office_name' => 'required|min_length[5]|max_length[100]',
        'office_code'  => 'required|min_length[1]|max_length[20]',
        'divisionorsection_name'      => 'required|min_length[5]|max_length[100]',
        'divisionorsection_code'  => 'required|min_length[1]|max_length[20]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
    // Relationships
    protected $returnTypeRelations = 'array';
    protected $belongsTo = [
        'Ticket' => [
            'model' => 'App\Models\Ticket',
            'foreign_key' => 'ticket_id',
        ],
    ];

}
