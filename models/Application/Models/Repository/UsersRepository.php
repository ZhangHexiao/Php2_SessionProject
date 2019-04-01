<?php

namespace Application\Models\Repository;

use Application\Models\Entity\Users;
use Doctrine\ORM\EntityRepository;

class UsersRepository extends EntityRepository
{

    protected $users;

    public function findUser(string $username)
    {
        $results = $this->findBy([], ['username' => 'ASC']);

        if (is_object($results)) {
            $nameInDB = $results->getUsername();

            if(strcmp($username,$nameInDB)==0){
                return $results;
            }
        }
        else {
            for ($i = 0; $i < count($results); $i++) {
                $nameInDB =$results[$i]->getUsername();
                if(strcmp($username,$nameInDB)==0){
                    return $results[$i];
                }

            }
        }
        return null;
    }

    public function save(array $usersArray, $users = null)
    {
        $this->users = $this->setData($usersArray, $users);

        try {
            $this->_em->persist($this->users);
            $this->_em->flush();
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    public function delete(Users $users)
    {
        $this->users = $users;

        try {
            $this->_em->remove($this->users);
            $this->_em->flush();
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    public function setData(array $userArray, Users $users = null)
    {
        if (!$users) {
            $this->users = new Users();
        } else {
            $this->users = $users;
        }

        $this->users->setUsername($userArray['username']);
        $this->users->setPassword($userArray['password']);

        return $this->users;
    }
}
