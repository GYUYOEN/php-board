<?php
namespace App\Models;  
use CodeIgniter\Model;
 
class BoardModel extends Model{
    protected $table = 'board';//사용하는 테이블
    protected $primaryKey = 'bid';
    protected $returnType     = 'object';//이값이 없으면 기본이 array가 된다.
    //사용할 컬럼지정, 전부 다 해줬다.
    protected $allowedFields = [
        'bid'
        ,'userid'
        ,'subject'
        ,'content'
        ,'regdate'
        ,'modifydate'
        ,'status'
        ,'parent_id'
    ];

    public function get_board_list()
    {
        return $this->orderBy('bid', 'DESC')->findAll();
    }

    public function save_board($data)
    {
        return $this->insert($data);
    }

    public function get_board($bid)
    {
        return $this->find($bid);
    }
}