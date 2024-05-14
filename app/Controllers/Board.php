<?php

namespace App\Controllers;

class Board extends BaseController
{
    public function list()
    {
        return render('board_list');
    }
}
