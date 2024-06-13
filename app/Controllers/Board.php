<?php

namespace App\Controllers;
use App\Models\BoardModel;
use App\Models\FileModel;

class Board extends BaseController
{
    protected $boardModel;
    protected $fileModel;

    public function __construct()
    {
        $this->boardModel = new BoardModel();
        $this->fileModel = new FileModel();
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
            if($_SESSION['userid']==$rs->userid) {
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

        $file=$this->request->getFile('upfile');
        if($file->getName()) {
            $filename=$file->getName(); //기존 파일명을 저장할때 필요하다. 여기서는 사용하지 않는다.
            $newName=$file->getRandomName(); //서버에 저장할때 파일명을 바꿔준다.
            $filepath=$file->store('board/',$newName); //CI4의 store 함수를 이용해 저장한다.
        }

        $data = [
            'userid' => $_SESSION['userid'],
            'subject' => $subject,
            'content' => $content
        ];
    
        $insert_id=$this->boardModel->save_board($data);
        if($file->getName()) {
            $data = [
                'bid' => $insert_id,
                'userid' => $_SESSION['userid'],
                'filename' => $filepath,
                'type' => 'board'
            ];
            $this->fileModel->save_file($data);
        }
        return $this->response->redirect(site_url('/board'));
    }

    public function view($bid = null)
    {
        $data['view'] = $this->boardModel->get_board($bid);
        $data['file_view'] = $this->fileModel->get_file($bid);
        return render('board_view', $data);  
    }

    public function modify($bid=null)
    {
        $rs = $this->boardModel->get_board($bid);
        if($_SESSION['userid']==$rs->userid) {
            $data['view']=$rs;
            return render('board_write', $data);
        } else {
            echo "<script>alert('본인이 작성한 글만 수정할 수 있습니다');location.href='/login';</script>";
            exit;
        }
    }

    public function delete($bid=null)
    {
        $rs = $this->boardModel->get_board($bid);
        if($_SESSION['userid']==$rs->userid) {
            $this->boardModel->delete_board($bid);
            return $this->response->redirect(site_url('/board'));
        } else {
            echo "<script>alert('본인이 작성한 글만 수정할 수 있습니다');location.href='/login';</script>";
            exit;
        }
    }
}
