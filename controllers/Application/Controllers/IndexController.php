<?php


namespace Application\Controllers;

use Ascmvc\Mvc\Controller;
use Application\Models\Entity\Users;
use Application\Services\UsersService;
use Application\Services\CrudProductsServiceTrait;
use Ascmvc\AscmvcControllerFactoryInterface;
use Ascmvc\Mvc\AscmvcEventManager;
use Ascmvc\Mvc\AscmvcEvent;
use Pimple\Container;

class IndexController extends Controller
{
    private $authenticated = 1;
    public function indexAction($vars = null)
    {
        $this->view['bodyjs'] = 1;

        if($this->authenticated){

            $this->view['authenticated']= 1;
        }

//        $this->view['templatefile'] = 'user_checkLogin_form';
        $this->view['templatefile'] = 'index_index';

        return $this->view;
    }
}