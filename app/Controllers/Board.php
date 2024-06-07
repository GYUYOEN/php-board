<?php

namespace App\Controllers;
use App\Models\BoardModel;

class Board extends BaseController
{

    public function list()
    {
        // $db = db_connect();
        // $query = "select * from board order by bid desc";
        // $rs = $db->query($query);
        // $data['list'] = $rs->getResult();//결과값 저장
        $boardModel = new BoardModel();
        $data['list'] = $boardModel->orderBy('bid', 'DESC')->findAll();
        return render('board_list', $data);//view에 리턴
    }

    public function write()
    {
        return render('board_write');  
    }

    public function save()
    {
        $subject=$this->request->getVar('subject');
        $content=$this->request->getVar('content');
    }

    public function view($bid = null)
    {
        $boardModel = new BoardModel();
        $data['view'] = $boardModel->where('bid', $bid)->findAll();
        return render('board_view', $data);  
    }
}
