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
        return $this->insert($data) ? $this->insertID() : false;
    }

    public function get_board($bid)
    {
        return $this->find($bid);
    }

    public function modify_board($data,$bid)
    {
        return $this->set($data)->where('bid', $bid)->update();
    }

    public function delete_board($bid) 
    {
        return $this->delete($bid); 
    }

    public function get_board_with_file($bid) 
    {
        return $this->db->table('board b')
                    ->select('b.*, GROUP_CONCAT(f.filename) as fs')
                    ->join('file_table f', 'f.type = "board" AND f.bid = b.bid', 'left')
                    ->where('b.bid', $bid)
                    ->get()
                    ->getRow();
    }
}