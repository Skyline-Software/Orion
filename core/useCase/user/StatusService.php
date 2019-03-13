<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 17.09.2018
 * Time: 15:30
 */

namespace core\useCase\user;


use core\entities\user\User;
use core\repositories\user\UserRepository;

class StatusService
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function activateUser($id):? User
    {
        $user = $this->userRepository->get($id);
        $user->activate();
        $this->userRepository->save($user);

        return $user;
    }

    public function deactivateUser($id):? User
    {
        $user = $this->userRepository->get($id);
        $user->deactivate();
        $this->userRepository->save($user);

        return $user;
    }

}