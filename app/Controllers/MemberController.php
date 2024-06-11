<?php
namespace App\Controllers;  
use CodeIgniter\Controller;
use App\Models\UserModel;

class MemberController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function login()
    {
        return render('login');
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/board');
    }
    
    public function loginok()
    {
        $userid = $this->request->getVar('userid');
        $passwd = $this->request->getVar('passwd');

        $user = $this->userModel->authenticate($userid, $passwd);
        if($user){
            $ses_data = [
                'userid' => $user->userid,
                'username' => $user->username,
                'email' => $user->email
            ];
            $this->session->set($ses_data);
            return redirect()->to('/board');
        }else{
            return redirect()->to('/login');
        }
    }
}