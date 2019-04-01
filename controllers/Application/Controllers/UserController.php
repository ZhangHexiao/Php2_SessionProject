<?php

namespace Application\Controllers;

use Application\Models\Entity\Users;
use Application\Services\UsersService;
use Application\Services\CrudProductsServiceTrait;
use Ascmvc\AscmvcControllerFactoryInterface;
use Ascmvc\Mvc\AscmvcEventManager;
use Ascmvc\Mvc\Controller;
use Ascmvc\Mvc\AscmvcEvent;
use Pimple\Container;

class UserController extends Controller implements AscmvcControllerFactoryInterface

{
    use CrudProductsServiceTrait;

//    protected  $authenticated = false;

    public static function factory(array &$baseConfig, &$viewObject, Container &$serviceManager, AscmvcEventManager &$eventManager)
    {
        $serviceManager[UserController::class] = $serviceManager->factory(function ($serviceManager) use ($baseConfig) {
            $em = $serviceManager['dem1'];

            $users = new Users();

            $crudService = new UsersService($users, $em);

            $controller = new UserController($baseConfig);

            $controller->setCrudService($crudService);

            return $controller;
        });
    }

//    public static function onBootStrap(AscmvcEvent $event){
//
//        $serviceManager = $event->getApplication()->getServiceManager();
//        $serviceManager[AuthenticationService::class] = new AuthenticationService();
////        $serviceManager[UserController::class] = new UserController();
//    }
//    public static function onBootStrap(AscmvcEvent $event){
//
//        $serviceManager = $event->getApplication()->getServiceManager();
//        $serviceManager[AuthenticationService::class] = new AuthenticationService;
////        $serviceManager[UserController::class] = new UserController();
////         $serviceManager[UserController::class] = new UserController();
//    }



    public function onDispatch(AscmvcEvent $event)
    {
        $this->view['saved'] = 0;

        $this->view['error'] = 0;

        $this->view['authenticated'] =0;
    }
    public function indexAction($vars = null)
    {
        $this->view['bodyjs'] = 1;

//        if($this->authenticated){
//
//        $this->view['authenticated']= 1;
//        }

        $this->view['templatefile'] = 'user_checkLogin_form';
//          $this->view['templatefile'] = 'user_index';

        return $this->view;
    }

    public function checkLoginAction($vars)
    {

        if (!empty($vars['post'])) {
            // Would have to sanitize and filter the $_POST array.
            $userArray['username'] = (string)$vars['post']['username'];
            $userArray['password'] = (string)$vars['post']['password'];




            if ($this->crudService->checkLogin($userArray['username'],$userArray['password'])) {
                $this->view['authenticated'] = 1;
//                $this->view['bodyjs'] = 1;
//                $this->view['templatefile'] = 'product_index';
//                return $this->view;

            } else {

                $this->view['authenticated'] = 0;
//                $this->view['bodyjs'] = 1;
//                $this->view['templatefile'] = 'product_index';
//                return $this->view;
            }
        }

        $this->view['bodyjs'] = 1;
        $this->view['templatefile'] = 'user_checkLogin_form';

        return $this->view;
    }





}