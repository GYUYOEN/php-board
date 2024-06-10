<?php
namespace App\Models;  
use CodeIgniter\Model;
 
class UserModel extends Model{
    protected $table = 'users';//사용하는 테이블
    protected $primaryKey = 'uid';
    protected $returnType     = 'object';//이값이 없으면 기본이 array가 된다.
    //사용할 컬럼지정, 전부 다 해줬다.
    protected $allowedFields = [
        'uid'
        ,'userid'
        ,'passwd'
        ,'username'
        ,'email'
    ];
}