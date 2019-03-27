<?php

namespace Application\Models\Repository;

use Application\Models\Entity\Users;
use Doctrine\ORM\EntityRepository;

class UsersRepository extends EntityRepository
{

    protected $users;

    public function findAll()
    {
        return $this->findBy([], ['username' => 'ASC']);
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
