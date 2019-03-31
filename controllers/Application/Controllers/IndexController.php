<?php

namespace Application\Controllers;

use Ascmvc\Mvc\Controller;

class IndexController extends Controller
{
    public function indexAction($vars = null)
    {
        $this->view['bodyjs'] = 1;
        
        $this->view['templatefile'] = 'user_index';
        
        return $this->view;
    }
}
