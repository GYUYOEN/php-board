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
        ,'type'
        ,'mid'
    ];

    public function save_file($data)
    {
        return $this->insert($data) ? $this->insertID() : false;
    }

    public function get_file($fid)
    {
        return $this->find($fid);
    }

    public function get_type_board_file($bid)
    {
        return $this->where('type', 'board')
                    ->where('bid', $bid)
                    ->findAll();
    }

    public function delete_file($fid)
    {
        return $this->where('type', 'board')
                    ->where('fid', $fid)
                    ->delete();
    }

    public function update_file($insert_id, $fi)
    {
        return $this->set('bid', $insert_id)
                    ->where('fid', $fi)
                    ->update();
    }

    public function get_file_by_bid($bid)
    {
        return $this->where('bid', $bid)
                    ->where('type', 'board')
                    ->findAll();
    }

    public function update_memo_file($bid, $insertid, $file_table_id)
    {
        return $this->set('bid', $bid)
                    ->set('mid', $insertid)
                    ->where('fid', $file_table_id)
                    ->update();
    }

    public function select_memo_file($file_table_id)
    {
        return $this->where('status', 1)
                    ->where('fid', $file_table_id)
                    ->first();
    }

    public function insert_memo_file($userid, $filepath)
    {
        $data = [
            "userid"=>$userid,
            "filename"=>$filepath,
            "type"=>'memo'
        ];
        return $this->insert($data) ? $this->insertID() : false;
    }

    public function get_memo_file($fid)
    {
        return $this->where('type', 'memo')
                    ->where('fid', $fid)
                    ->first();
    }

    public function delete_memo_file($fid)
    {
        return $this->where('type', 'memo')
                    ->where('fid', $fid)
                    ->delete();
    }

    public function get_memo_file_by_bid_mid($bid, $mid)
    {
        return $this->where('type', 'memo')
                    ->where('bid', $bid)
                    ->where('mid', $mid)
                    ->first();
    }

    public function upldate_memo_file_by_bid_mid($bid, $mid, $modify_file_table_id)
    {
        return $this->set('bid', $bid)
                    ->set('mid', $mid)
                    ->where('fid', $modify_file_table_id)
                    ->update();
    }

    public function get_memo_file_by_mid($mid)
    {
        return $this->where('type', 'memo')
                    ->where('mid', $mid)
                    ->first();
    }
}