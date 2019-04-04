<?php

namespace Application\Controllers;

use Application\Models\Entity\Users;
use Application\Services\UsersService;
use Application\Services\UsersServiceTrait;
use Ascmvc\AscmvcControllerFactoryInterface;
use Ascmvc\Mvc\AscmvcEventManager;
use Ascmvc\Mvc\Controller;
use Ascmvc\Mvc\AscmvcEvent;
use Pimple\Container;

class UserController extends Controller implements AscmvcControllerFactoryInterface
{
    use UsersServiceTrait;

//************************This part can help refer the result of check login ***************************
    public static function onBootstrap(AscmvcEvent $event)
    {
        $app = $event->getApplication();

        $baseConfig = $app->getBaseConfig();

        $serviceManager = $app->getServiceManager();

        $em = $serviceManager['dem1'];

        $users = new Users();

        $serviceManager[UsersService::class] = function ($serviceManager) use ($users, $em) {
            static $sessionManager;

            if (!isset($sessionManager)) {
                $sessionManager = new UsersService($users, $em);
            }

            return $sessionManager;
        };

        $usersService = $serviceManager[UsersService::class];

        $view = $baseConfig['view'];

        $view['authenticated'] = $usersService->isAuthenticated();

        $app->appendBaseConfig('view', $view);
    }

//************************This part can help refer the result of check login ***************************



//************************Some Part should be changed due to the onBootStrap Function*****************
    public static function factory(array &$baseConfig, &$viewObject, Container &$serviceManager, AscmvcEventManager &$eventManager)
    {
        $serviceManager[UserController::class] = $serviceManager->factory(function ($serviceManager) use ($baseConfig) {
//            $em = $serviceManager['dem1'];
//            $users = new Users();
            $usersService = $serviceManager[UsersService::class];
//          $crudService = new UsersService($users, $em);

            $controller = new UserController($baseConfig);

            $controller->setUsersService($usersService);

            return $controller;
        });
    }


    public function onDispatch(AscmvcEvent $event)
    {
        $this->view['saved'] = 0;

        $this->view['error'] = 0;
    }

    public function indexAction($vars = null)
    {
        $this->view['bodyjs'] = 1;

        $this->view['templatefile'] = 'user_index';

        return $this->view;
    }

    public function checkLoginAction($vars)
    {
//  **************** There is no part about check the session for my code**********************************
//        if (!empty($vars['post'])) {
//            // Would have to sanitize and filter the $_POST array.
//            $userArray['username'] = (string)$vars['post']['username'];
//            $userArray['password'] = (string)$vars['post']['password'];
//
//            if ($this->crudService->checkLogin($userArray['username'],$userArray['password'])) {
//
//                $this->view['authenticated'] = 1;
//                $this->view['bodyjs'] = 1;
//                $this->view['templatefile'] = 'user_index';
//                return $this->view;
//            }
//        }
//        $this->view['bodyjs'] = 1;
//        $this->view['templatefile'] = 'user_checkLogin_form';
//        return $this->view;
//  **************** There is no part about check the session for my code**********************************
        $this->view['error_message'] = '';
        $this->view['bodyjs'] = 1;

        if ($this->usersService->isAuthenticated()) {
//            $response = new Response();
//            $response = $response
//                ->withStatus(302)
//                ->withHeader('Location', '/index');
//            return $response;
            $this->view['templatefile'] = 'user_index';
            return $this->view;
        }

        if (!empty($vars['post'])) {
            if(
                isset($vars['post']['username'])
                && isset($vars['post']['password'])
                && isset($vars['post']['submit'])
            ) {
                $username = (string) $vars['post']['username'];

                $password = (string) $vars['post']['password'];

                if (!ctype_alnum($username)) {
                    $username = preg_replace("/[^a-zA-Z]+/", "", $username);
                }

                if (strlen($username) > 40) {
                    $username = substr($username, 0, 39);
                }

                $password = preg_replace("/[^_a-zA-Z0-9]+/", "", $password);

                if (strlen($password) > 40) {
                    $password = substr($password, 0, 39);
                }

                // check login $this->sessionService->checkLogin($username, $password)
                if($this->usersService->checkLogin($username, $password)) {
//                    $response = new Response();
//                    $response = $response
//                        ->withStatus(302)
//                        ->withHeader('Location', '/index');
//                    return $response;
                $this->view['templatefile'] = 'user_index';
                return $this->view;
                }

                $this->view['error_message'] = 'Wrong credentials!';
            }
        }

        // post the form
        $this->view['templatefile'] = 'user_checkLogin_form';
        return $this->view;




    }





}