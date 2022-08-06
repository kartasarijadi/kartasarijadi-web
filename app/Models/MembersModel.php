<?php

namespace App\Models;

use CodeIgniter\Model;

class MembersModel extends Model
{
    protected $table = 'members';
    protected $primaryKey = 'member_id';
    protected $useTimestamps = 'true';
    protected $allowedFields = ['member_name', 'member_position', 'member_type', 'member_active', 'member_image'];
    protected $returnType     = 'object';
    protected $useSoftDeletes = true;

    public function getMember($memberId)
    {
        $where = [
            'member_id' => $memberId
        ];
        return $this->where($where)->get()->getRow();
    }
}