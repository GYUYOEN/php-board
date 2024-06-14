<?php
namespace App\Models;  
use CodeIgniter\Model;
 
class FileModel extends Model{
    protected $table = 'file_table';//사용하는 테이블
    protected $primaryKey = 'fid';
    protected $returnType     = 'object';//이값이 없으면 기본이 array가 된다.
    //사용할 컬럼지정, 전부 다 해줬다.
    protected $allowedFields = [
        'fid'
        ,'bid'
        ,'userid'
        ,'filename'
        ,'regdate'
        ,'status'
        ,'memoid'
        ,'type'
    ];

    public function save_file($data)
    {
        return $this->insert($data);
    }

    public function get_file($bid)
    {
        return $this->find($bid);
    }

    public function get_type_board_file($bid)
    {
        return $this->where('type', 'board')
                    ->where('bid', $bid)
                    ->findAll();
    }

    public function delete_file($bid)
    {
        return $this->delete($bid);
    }
}