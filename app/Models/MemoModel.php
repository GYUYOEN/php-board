<?php
namespace App\Models;  
use CodeIgniter\Model;
 
class MemoModel extends Model{
    protected $table = 'memo';//사용하는 테이블
    protected $primaryKey = 'mid';
    protected $returnType     = 'object';//이값이 없으면 기본이 array가 된다.
    //사용할 컬럼지정, 전부 다 해줬다.
    protected $allowedFields = [
        'mid'
        ,'bid'
        ,'userid'
        ,'memo'
        ,'status'
    ];

    public function insert_memo($bid, $userid, $memo) 
    {
        $data = [
            'bid'=>$bid,
            'userid'=>$userid,
            'memo'=>$memo,
            'status'=>1
        ];
        return $this->insert($data) ? $this->insertID() : false;
    }

    public function memo_list_with_file($bid) 
    {
        return $this->db->table('memo m')
                    ->select('*, m.userid, m.regdate, m.mid')
                    ->join('file_table f', 'm.mid=f.mid and f.type="memo"', 'left')
                    ->where('m.status', 1)
                    ->where('m.bid', $bid)
                    ->orderBy('m.mid', 'ASC')
                    ->get()
                    ->getResult();
    }

    public function get_memo($mid) 
    {
        return $this->where('mid', $mid)  
                    ->first();
    }

    public function delete_memo($mid)
    {
        return $this->where('mid', $mid)
                    ->delete();
    }

    public function get_memo_by_mid($mid)
    {
        return $this->where('type', 'memo')
                    ->where('mid', $mid)
                    ->first();
    }

    public function update_memo($memo_text, $mid)
    {
        return $this->set('memo', $memo_text)
                    ->where('mid', $mid)
                    ->update();
    }
}