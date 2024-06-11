<?php

namespace App\Controllers;
use App\Models\BoardModel;

class Board extends BaseController
{
    protected $boardModel;

    public function __construct()
    {
        $this->boardModel = new BoardModel();
    }

    public function list()
    {
        $data['list'] = $this->boardModel->get_board_list();
        return render('board_list', $data);
    }

    public function write()
    {
        if(!isset($_SESSION['userid'])) {
            echo "<script>alert('로그인하십시오.');location.href='/login'</script>";
            exit;
        }
        return render('board_write');  
    }

    public function save()
    {
        if(!isset($_SESSION['userid'])) {
            echo "<script>alert('로그인하십시오.');location.href='/login'</script>";
            exit;
        }
        
        $subject=$this->request->getVar('subject');
        $content=$this->request->getVar('content');

        $data = [
            'userid' => $_SESSION['userid'],
            'subject' => $subject,
            'content' => $content
        ];
    
        $this->boardModel->save_board($data);

        return $this->response->redirect(site_url('/board'));
    }

    public function view($bid = null)
    {
        $data['view'] = $this->boardModel->get_board($bid);
        return render('board_view', $data);  
    }
}
