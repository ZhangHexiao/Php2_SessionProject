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

class IndexController extends Controller implements AscmvcControllerFactoryInterface
{
//    protected $data = [];
//
//    protected $dataStorage;
//
//    protected $templateManager;

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




    // Set flags.
    protected $loginCheck = FALSE;

    protected $validSession = FALSE;

    protected $postLoginForm = TRUE;

    protected $authenticated = false;

// Initialize application business and frontend messages.
    protected $errorMessage = 0;

    protected $userMessage = '';
    ////////////////////////////////////////////////////////
    public function onDispatch(AscmvcEvent $event)
    {
        $this->view['authenticated'] = false;

        $this->view['error'] = 0;
    }
  ////////////////////////////////////////////////////////
    public function checkLoginAction($vars)
    {
        if ($this->validSession === FALSE) {

            $this->validSession = $this->session_secure_init();

        }

        if (!empty($vars['post'])) {
                // Would have to sanitize and filter the $_POST array.
                $userArray['username'] = (string) $vars['post']['username'];
                $userArray['password'] = (string) $vars['post']['password'];

                if ($this->crudService->checkLogin($userArray['username'],$userArray['password'])) {
                    $this->view['authenticated'] = true;

                    if ($this->validSession === TRUE) {

                        //  Check for cookie tampering.
                        if (isset($_SESSION['LOGGEDIN'])) {

                            $this->validSession = $this->session_obliterate();
                            $this->errorMessage = 3;
                            $this->postLoginForm = TRUE;

                        } else {

                            setcookie('loggedin', TRUE, time()+ 4200, '/');
                            $_SESSION['LOGGEDIN'] = TRUE;
                            $_SESSION['REMOTE_USER'] = $userArray['username'];
                            $this->postLoginForm = FALSE;

                        }

                    } else {

                        $this->validSession = $this->session_obliterate();
                        $this->errorMessage = 3;
                        $this->postLoginForm = TRUE;

                    }
                    $this->loginCheck = TRUE;
                    $this->view['bodyjs'] = 1;
                    $this->view['templatefile'] = 'index_page';
                    return $this->view;

                } else {
                    $this->view['error'] = 1;
                    $this->validSession = $this->session_obliterate();
                    $this->errorMessage = 1;
                    $this->postLoginForm = TRUE;
                    $this->loginCheck = TRUE;

                    $this->view['bodyjs'] = 1;
                    $this->view['templatefile'] = 'index_page';
                    return $this->view;

                }

            }
            $this->loginCheck = TRUE;
            $this->view['bodyjs'] = 1;
            $this->view['templatefile'] = 'index_page';
            return $this->view;
    }
    ///////////////////////////////////////////////////////////////////////////////////////////////////



    public function indexAction($vars = null)
    {
//         $this->dataStorage = new DataStorage();
//
//        $this->templateManager = new TemplateManager();

        // Check if user is already logged in.
        if (isset($_COOKIE['loggedin'])) {

            if ($this->validSession === FALSE) {

                $this->validSession = $this->session_secure_init();

            }

            //  Check for cookie tampering.
            if ($this->validSession === TRUE && isset($_SESSION['LOGGEDIN'])) {

                $this->postLoginForm = FALSE;

            } else {

                $this->validSession = $this->session_obliterate();

                $this->errorMessage = 3;

                $this->postLoginForm = TRUE;

            }

            // Cookie login check done.
            $this->loginCheck = TRUE;

        }

        // Login verification.
//        if (isset($_POST['submit'])
//            && $_POST['submit'] == 1
//            && !empty($_POST['username'])
//            && !empty($_POST['password'])) {

//            if ($this->validSession === FALSE) {
//
//                $this->validSession = $this->session_secure_init();
//
//            }
//
//            $username = (string) $_POST['username'];
//
//            $password = (string) $_POST['password'];

            // Check credentials.
//            if ($this->dataStorage->checkLogin($username, $password)) {
//
//                if ($this->validSession === TRUE) {
//
//                    //  Check for cookie tampering.
//                    if (isset($_SESSION['LOGGEDIN'])) {
//
//                        $this->validSession = $this->session_obliterate();
//                        $this->errorMessage = 3;
//                        $this->postLoginForm = TRUE;
//
//                    } else {
//
//                        setcookie('loggedin', TRUE, time()+ 4200, '/');
//                        $_SESSION['LOGGEDIN'] = TRUE;
//                        $_SESSION['REMOTE_USER'] = $username;
//                        $this->postLoginForm = FALSE;
//
//                    }
//
//                } else {
//
//                    $this->validSession = $this->session_obliterate();
//                    $this->errorMessage = 3;
//                    $this->postLoginForm = TRUE;
//
//                }
//
//            } else {
//
//                $this->validSession = $this->session_obliterate();
//                $this->errorMessage = 1;
//                $this->postLoginForm = TRUE;
//
//            }
///////////////////////////////////////////////////////////////////////////////////////////
            // Username-password login check done.
//            $this->loginCheck = TRUE;

//        }

        // Intercept logout POST.
        if (isset($_POST['logout'])) {

            if ($this->validSession === FALSE) {

                $this->session_secure_init();

            }

            $this->validSession = $this->session_obliterate();
            $this->errorMessage = 2;
            $this->postLoginForm = TRUE;

        }

        // Intercept invalid sessions and redirect to login page.
        if ($this->loginCheck === TRUE && $this->validSession === FALSE && $this->errorMessage === 0) {

            if ($this->validSession === FALSE) {

                $this->validSession = $this->session_secure_init();
                $this->validSession = $this->session_obliterate();

            }

            $this->errorMessage = 3;
            $this->postLoginForm = TRUE;

        }

        // Prepare view output.
        if ($this->postLoginForm === TRUE) {

            switch ($this->errorMessage) {
                case 0:
                    $this->userMessage = 'Please sign in.';
                    break;
                case 1:
                    $this->userMessage = 'Wrong credentials.  <a href="index.php">Try again</a>.';
                    break;
                case 2:
                    $this->userMessage = 'You are logged out!  <a href="index.php">You can login again</a>.';
                    break;
                case 3:
                    $this->userMessage = 'Invalid session. <a href="index.php">Please login again</a>.';
                    break;
            }

//            $this->templateManager->setUserMessage($this->userMessage)->loadFormTemplate();
            $this->view['bodyjs'] = 1;
            $this->view['templatefile'] = 'index_page';
            return $this->view;

        } else {
//            $this->templateManager->loadTemplate();

              $this->view['bodyjs'] = 1;
              $this->view['templatefile'] = 'index_index';
              return $this->view;
        }

//        $this->templateManager->render();

    }

    protected function session_obliterate()
    {
        $_SESSION = array();
        setcookie(session_name(),'', time() - 3600, '/');
        setcookie('loggedin', '', time() - 3600, '/');
        session_destroy();   // Destroy session data in storage.
        session_unset();     // Unset $_SESSION variable for the runtime.
        $this->validSession = FALSE;
        return $this->validSession;
    }

    protected function session_secure_init()
    {
        session_set_cookie_params(4200);

        $this->validSession = TRUE;

        if (!defined('OURUNIQUEKEY')) {

            define('OURUNIQUEKEY', 'phpi');

        }

        // Avoid session prediction.
        $sessionname = OURUNIQUEKEY;

        if (session_name() != $sessionname) {

            session_name($sessionname);

        } else {

            session_name();

        }

        // Start session.
        session_start();

        if ((!isset($_COOKIE['loggedin']) && isset($_SESSION['LOGGEDIN']))
            ^ (isset($_COOKIE['loggedin']) && !isset($_SESSION['LOGGEDIN']))) {

            $this->validSession = FALSE;

        }

        if ($this->validSession == TRUE) {

            // Avoid session fixation.
            if (!isset($_SESSION['INITIATED'])) {

                session_regenerate_id();
                $_SESSION['INITIATED'] = TRUE;

            }

            if (!isset($_SESSION['CREATED'])) {

                $_SESSION['CREATED'] = time();

            }

            if (time() - $_SESSION['CREATED'] > 3600) {

                // Session started more than 60 minutes ago.
                session_regenerate_id();    // Change session ID for the current session an invalidate old session ID.
                $_SESSION['CREATED'] = time();  // Update creation time.

            }

            // Avoid session hijacking.
            $useragent = $_SERVER['HTTP_USER_AGENT'];

            $useragent .= OURUNIQUEKEY;

            if (isset($_SESSION['HTTP_USER_AGENT'])) {

                if ($_SESSION['HTTP_USER_AGENT'] != md5($useragent)) {

                    $this->validSession = FALSE;

                }

            } else {

                $_SESSION['HTTP_USER_AGENT'] = md5($useragent);

            }

            // Avoid session fixation in case of an inactive session.
            if ($this->validSession == TRUE && isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > 3600) {

                // Last request was more than 60 minutes ago.
                $this->validSession = FALSE;

            } else {

                $_SESSION['LAST_ACTIVITY'] = time(); // Update last activity timestamp.

            }

        }

        return $this->validSession;

    }



}





//class IndexController extends Controller
//{
//    public function indexAction($vars = null)
//    {
//        $this->view['bodyjs'] = 1;

//        if($authenticated){
//
//            $this->view['authenticated']= 1;
//        }
//
//        $this->view['templatefile'] = 'index_checkLogin';
//
//        return $this->view;
//    }
//}
