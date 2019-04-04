<?php
/**
 * Created by PhpStorm.
 * User: z_hexiao
 * Date: 2019-03-26
 * Time: 3:20 PM
 */

namespace Application\Services;

use Application\Models\Entity\Users;
//use Application\Models\Traits\DoctrineTrait;
use Application\Models\Repository\UsersRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;

class UsersService
{
//    use DoctrineTrait;

    protected $users;

    protected $usersRepository;

    protected $em;

    // Set flags.
    protected $authenticated = false;

    protected $validSession = false;

    public function __construct(Users $users, EntityManager $em)
    {
        $this->users = $users;

        $this->em = $em;

        $this->usersRepository = new UsersRepository(
            $this->em,
            new ClassMetaData('Application\Models\Entity\Users')
        );

        $this->checkSession();
    }

///////////////////////////////////////////////////////////////////////////////////////////////////
//Here the result of My code is a object, but Andrew's result is an array
////////////////////////////////////////////////////////////////////////////////////////////////
    public function checkLogin($username, $password)
    {
        try {
            if (isset($username)) {
                $users = $this->usersRepository->findUser($username);
            }
        } catch (\Exception $e) {
            return false;
        }

        if (isset($users)) {
            if (password_verify($password, $users->getPassword())) {
                if ($this->validSession === false) {
                    $this->validSession = $this->session_secure_init();
                }

                if ($this->validSession === true) {
                    //  Check for cookie tampering.
                    if (isset($_SESSION['LOGGEDIN'])) {
                        $this->validSession = $this->session_obliterate();
                    } else {
                        setcookie('loggedin', true, time() + 4200, '/');
                        $_SESSION['LOGGEDIN'] = true;
                        $_SESSION['REMOTE_USER'] = $username;
                        $this->authenticated = true;
                        return true;
                    }
                } else {
                    $this->validSession = $this->session_obliterate();
                    return false;
                }
            } else {
                $this->validSession = $this->session_obliterate();
                return false;
            }
        } else {
            return false;
        }
    }


    public function checkSession()
    {
        // Check if user is already logged in.
        if (isset($_COOKIE['loggedin'])) {
            if ($this->validSession === false) {
                $this->validSession = $this->session_secure_init();
            }

            //  Check for cookie tampering.
            if ($this->validSession !== true && !isset($_SESSION['LOGGEDIN'])) {
                $this->validSession = $this->session_obliterate();
            }

            // Cookie login check done.
            $this->authenticated = true;
        }

        // Intercept invalid sessions.
        if ($this->authenticated === true && $this->validSession === false) {
            $this->validSession = $this->session_secure_init();
            $this->validSession = $this->session_obliterate();
            $this->authenticated = false;
        }
    }

    public function logout()
    {
        if ($this->validSession === false) {
            $this->session_secure_init();
        }

        $this->validSession = $this->session_obliterate();
        $this->authenticated = false;
    }

    protected function session_obliterate()
    {

        $_SESSION = array();
        setcookie(session_name(),'', time() - 3600, '/');
        setcookie('loggedin', '', time() - 3600, '/');
        session_destroy();   // Destroy session data in storage.
        session_unset();     // Unset $_SESSION variable for the runtime.
        $this->validSession = false;
        return $this->validSession;

    }

    protected function session_secure_init()
    {
        session_set_cookie_params(4200);

        $this->validSession = true;

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
            ^ (isset($_COOKIE['loggedin']) && !isset($_SESSION['LOGGEDIN']))
        ) {
            $this->validSession = false;
        }

        if ($this->validSession == true) {
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
                    $this->validSession = false;
                }
            } else {
                $_SESSION['HTTP_USER_AGENT'] = md5($useragent);
            }

            // Avoid session fixation in case of an inactive session.
            if ($this->validSession === true && isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > 3600) {
                // Last request was more than 60 minutes ago.
                $this->validSession = false;
            } else {
                $_SESSION['LAST_ACTIVITY'] = time(); // Update last activity timestamp.
            }

        }

        return $this->validSession;
    }


    public function isAuthenticated(): bool
    {
        return $this->authenticated;
    }

    /**
     * @return bool
     */
    public function isValidSession(): bool
    {
        return $this->validSession;
    }


}