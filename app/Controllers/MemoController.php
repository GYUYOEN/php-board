<?php

namespace App\Controllers;
use App\Models\MemoModel;
use App\Models\FileModel;

class MemoController extends BaseController {
    protected $memoModel;
    protected $fileModel;

    public function __construct()
    {
        $this->memoModel = new MemoModel();
        $this->fileModel = new FileModel();
    }

    public function memo_write()
    {
        if(!isset($_SESSION['userid'])) {
            return "login";
            exit;
        }

        $memo=$this->request->getVar('memo');
        $bid=$this->request->getVar('bid');
        $file_table_id=$this->request->getVar('file_table_id');

        $insertid=$this->memoModel->insert_memo($bid, $_SESSION['userid'], $memo);
        if(!empty($file_table_id)) { // 첨부파일이 있는 경우
            $this->fileModel->update_memo_file($bid, $insertid, $file_table_id);
            $rs=$this->fileModel->select_memo_file($file_table_id);
            $imgarea="<img src='/uploads/".$rs->filename."' style='max-width:90%'>";
        } else {
            $imgarea="";
        }

        $return_data = "<div class=\"card mb-4\" id=\"memo_".$insertid."\" style=\"max-width: 100%;margin-top:20px;\">
                            <div class=\"row g-0\">
                                <div class=\"col-md-12\">
                                <div class=\"card-body\">
                                <p class=\"card-text\">".$imgarea."<br>".$memo."</p>
                                <p class=\"card-text\"><small class=\"text-muted\">".$_SESSION['userid']." / now</small></p>
                                <p class=\"card-text\" style=\"text-align:right\"><a href=\"javascript:;\" onclick=\"memo_modify(".$insertid.")\"><button type=\"button\" class=\"btn btn-secondary btn-sm\">수정</button></a>&nbsp;<a href=\"javascript:;\" onclick=\"memo_del(".$insertid.")\"><button type=\"button\" class=\"btn btn-secondary btn-sm\">삭제</button></a></p>
                            </div>
                            </div>
                            </div>
                        </div>";
        return $return_data;
    }

    public function save_image_memo()
    {
        if(!isset($_SESSION['userid'])) {
            $return_data=array("result"=>"fail", "data"=>"login");
            return json_encode($return_data);
            exit;
        }

        $file=$this->request->getFile('savefile');
        if($file->getName()) {
            $filename=$file->getName();
            $newName=$file->getRandomName();
            $filepath=$file->store('memo/',$newName);
        }

        if(isset($filepath)) {
            $inserid=$this->fileModel->insert_memo_file($_SESSION['userid'], $filepath);
        }

        $return_data=array("result"=>"success", "fid"=>$inserid, "savename"=>$filepath);
        return json_encode($return_data);
    }

    public function memo_file_delete()
    {
        $fid=$this->request->getVar('fid');
        $rs=$this->fileModel->get_memo_file($fid);
        if(unlink('uploads/'.$rs->filename)) {
            $this->fileModel->delete_memo_file($fid);
            $return_data=array("result"=>"ok");
        } else {
            $return_data=array("result"=>"no");
        }
        return json_encode($return_data);
    }

    public function memo_delete()
    {
        if(!isset($_SESSION['userid'])) {
            $return_data=array("result"=>"login");
            return json_encode($return_data);
            exit;
        }

        $mid=$this->request->getVar('mid');
        $rs=$this->memoModel->get_memo($mid);
        if($mid and $rs->mid) {
            if($rs->userid==$_SESSION['userid']) {
                $rs2=$this->memoModel->delete_memo($mid);
                if($rs2) {
                    $rs3=$this->fileModel->get_memo_file_by_bid_mid($rs->bid, $mid);
                    if(isset($rs3->filename) and unlink('uploads/'.$rs3->filename)) {
                        $this->fileModel->delete_memo_file($rs3->fid);
                    }
                    $return_data=array("result"=>"ok");
                    return json_encode($return_data);
                    exit;
                } else {
                    $retun_data = array("result"=>"fail");
                    return json_encode($retun_data);
                    exit;
                }
            } else {
                $retun_data = array("result"=>"my");
                return json_encode($retun_data);
                exit;
            }
        } else {
            $retun_data = array("result"=>"nodata");
            return json_encode($retun_data);
            exit;
                    
        }
    }

    public function memo_modify()
    {
        $mid=$this->request->getVar('mid');
        $rs=$this->memoModel->get_memo($mid);
        if($rs->userid==$_SESSION['userid']) {
            $rs2=$this->fileModel->get_memo_file_by_mid($mid);
            $html = "<form class=\"row g-3\">
                        <input type=\"hidden\" id=\"modify_memoid\" value=\"".$mid."\">
                        <input type=\"hidden\" id=\"modify_file_table_id\" value=\"\">

                        <div class=\"col-md-8\" style=\"padding:10px;\">
                            <textarea class=\"form-control\" id=\"memo_text_".$rs->mid."\" style=\"height: 60px\">".$rs->memo."</textarea>
                        </div>
                        <div class=\"col-md-2\" style=\"padding:10px;\">
                            <button type=\"button\" class=\"btn btn-secondary\" onclick=\"memo_modify_update(".$rs->mid.")\" >댓글수정</button>
                        </div>";
            if(isset($rs2->fid)) {
                $html .= "<div class=\"col-md-2\" style=\"padding:10px;\" id=\"memo_image_".$mid."\">
                            <div style=\"display:none;\" class=\"btn btn-warning\" id=\"filebutton_".$mid."\" onclick=\"$('#upfile').click();\">사진첨부</div>
                            <input type=\"file\" name=\"upfile\" class=\"upfile\" id=\"upfile_".$mid."\" style=\"display:none;\" />
                            <div class=\"col\" id=\"f_".$rs2->fid."\"><div class=\"card h-100\"><img src=\"/uploads/".$rs2->filename."\" class=\"card-img-top\"><div class=\"card-body\"><button type=\"button\" class=\"btn btn-warning\" onclick=\"memo_file_del(".$rs3->getRow()->fid.")\">삭제</button></div></div></div>
                        </div>";
            } else {
                $html .= "<div class=\"col-md-2\" style=\"padding:10px;\" id=\"memo_image_".$mid."\">
                            <div class=\"btn btn-warning\" id=\"filebutton_".$mid."\" onclick=\"$('#upfile').click();\">사진첨부</div>
                            <input type=\"file\" name=\"upfile\" id=\"upfile\" style=\"display:none;\" />
                        </div>";

            }
            $html .= "</form>";
            echo $html;
        } else {
            echo "my";
            exit;
        }
    }

    public function memo_modify_update()
    {
        $mid=$this->request->getVar('mid');
        $memo_text=$this->request->getVar('memo_text');
        $modify_file_table_id=$this->request->getVar('modify_file_table_id');
        $rs=$this->memoModel->get_memo($mid);
        if($rs->userid==$_SESSION['userid']) {
            $this->memoModel->update_memo($memo_text, $modify_file_table_id);
            if(!empty($modify_file_table_id)) {
                $this->fileModel->upldate_memo_file_by_bid_mid($rs->bid, $mid, $modify_file_table_id);
            }

            $rs2=$this->fileModel->get_memo_file_by_mid($mid);
            if(!empty($rs2->filename)) {
                $imgarea = "<img src='/uploads/".$rs2->filename."' style='max-width:90%'>";
            }else{
                $imgarea="";
            }
            $return_data = "<div class=\"row g-0\">
                                <div class=\"col-md-12\">
                                    <div class=\"card-body\">
                                        <p class=\"card-text\">".$imgarea."<br>".$memo_text."</p>
                                        <p class=\"card-text\"><small class=\"text-muted\">".$_SESSION['userid']." / now</small></p>
                                        <p class=\"card-text\" style=\"text-align:right\"><a href=\"javascript:;\" onclick=\"memo_modify(".$mid.")\"><button type=\"button\" class=\"btn btn-secondary btn-sm\">수정</button></a>&nbsp;<a href=\"javascript:;\" onclick=\"memo_del(".$memoid.")\"><button type=\"button\" class=\"btn btn-secondary btn-sm\">삭제</button></a></p>
                                    </div>
                                </div>
                            </div>";
            return $return_data;
        } else {
            echo "my";
            exit;
        }
    }
}