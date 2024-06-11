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
        
        $bid=$this->request->getVar('bid'); // bid 값이 있으면 수정이고 아니면 등록
        $subject=$this->request->getVar('subject');
        $content=$this->request->getVar('content');

        if($bid) {
            $rs = $this->boardModel->get_board($bid);
            if($_SESSION['userid']==$rs->getRow()->userid) {
                $data = [
                    'subject' => $subject,
                    'content' => $content
                ];
                $this->boardModel->modify_board($data, $bid);
                return $this->response->redirect(site_url('/boardView/'.$bid));
                exit;
            } else {
                echo "<script>alert('본인이 작성한 글만 수정할 수 있습니다.');location.href='/login';</script>";
                exit;
            }
        }

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

    public function modify($bid=null)
    {
        $rs = $this->boardModel->get_board($bid);
        if($_SESSION['userid']==$rs->getRow()->userid) {
            $data['view']=$rs->getRow();
            return render('board_write', $data);
        } else {
            echo "<script>alert('본인이 작성한 글만 수정할 수 있습니다');location.href='/login';</script>";
            exit;
        }
    }

    public function delete($bid=null)
    {
        $rs = $this->boardModel->get_board($bid);
        if($_SESSION['userid']==$rs->getRow()->userid) {
            $this->boardModel->delete_board($bid);
            return $this->response->redirect(site_url('/board'));
        } else {
            echo "<script>alert('본인이 작성한 글만 수정할 수 있습니다');location.href='/login';</script>";
            exit;
        }
    }
}
