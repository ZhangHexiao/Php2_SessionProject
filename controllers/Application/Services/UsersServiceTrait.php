<?php

namespace Application\Services;

trait UsersServiceTrait
{

    protected $usersService;

    /**
     * @return mixed
     */
    public function getUsersService()
    {
        return $this->usersService;
    }

    /**
     * @param mixed $usersProducts
     */
    public function setUsersService($usersService)
    {
        $this->usersService = $usersService;
    }
}
