<?php
/**
 * Created by PhpStorm.
 * User: z_hexiao
 * Date: 2019-03-26
 * Time: 3:20 PM
 */

namespace Application\Services;

use Application\Models\Entity\Users;
use Application\Models\Traits\DoctrineTrait;
use Application\Models\Repository\UsersRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;

class UsersService
{
    use DoctrineTrait;

    protected $users;

    protected $usersRepository;

    public function __construct(Users $users, EntityManager $em)
    {
        $this->users = $users;

        $this->em = $em;

        $this->usersRepository = new UsersRepository(
            $this->em,
            new ClassMetaData('Application\Models\Entity\Users')
        );
    }


//    public function read(int $id = null)
//    {
//        try {
//            if (isset($id)) {
//                $results = $this->getEm()->find(Users::class, $id);
//            } else {
//                return false;
//            }
//        } catch (\Exception $e) {
//            return false;
//        }
//
//        return $results;
//    }
//
//    public function getQuote()
//    {
//        return "'";
//    }
///////////////////////////////////////////////////////////////////////////////////////////////////

    public function checkLogin($username, $password)
    {
//        if (!ctype_alpha($username)) {
//
//            $username = preg_replace("/[^a-zA-Z]+/", "", $username);
//
//        }
//
//        if (strlen($username) > 40) {
//
//            $username = substr($username, 0, 39);
//
//        }
//
//        $password = preg_replace("/[^_a-zA-Z0-9]+/", "", $password);
//
//        if (strlen($password) > 40) {
//
//            $password = substr($password, 0, 39);
//
//        }

        try {
            if (isset($username)) {
                $users = $this->usersRepository->findUser($username);
            }
        } catch (\Exception $e) {
            return false;
        }

        $passwordVerified = password_verify($password, $users->getPassword());

        return $passwordVerified;


    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////

}