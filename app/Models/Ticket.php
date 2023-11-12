<?php

namespace App\Models;

use CodeIgniter\Model;

class Ticket extends Model
{
    protected $table            = 'ticket';
    protected $primaryKey       = 'ticket_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['category_id','office_id','categories_id','user_id','description','status','first_name','last_name','email'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'office_id'   => 'required|is_natural_no_zero',
        'category_id'   => 'required|is_natural_no_zero',
        'user_id'   => 'required|is_natural_no_zero',
        'description' => 'required|min_length[3]|max_length[100]',
        'status'     => 'max_length[1000]',
        'first_name'     => 'required|min_length[3]|max_length[100]',
        'last_name'     => 'required|min_length[3]|max_length[100]',
        'email'     => 'required|min_length[3]|max_length[1000]|valid_email',
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

}
