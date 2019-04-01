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


    public function onDispatch(AscmvcEvent $event)
    {
        $this->view['saved'] = 0;

        $this->view['error'] = 0;

        $this->view['authenticated'] =null;
    }

    public function indexAction($vars = null)
    {
        $this->view['bodyjs'] = 1;

        $this->view['templatefile'] = 'user_index';

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
                $this->view['bodyjs'] = 1;
                $this->view['templatefile'] = 'user_index';
                return $this->view;

            }
        }

        $this->view['bodyjs'] = 1;
        $this->view['templatefile'] = 'user_checkLogin_form';
        return $this->view;
    }





}