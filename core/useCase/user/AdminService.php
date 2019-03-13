<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 17.09.2018
 * Time: 13:16
 */

namespace core\useCase\user;


use core\entities\user\User;
use core\entities\user\vo\Auth;
use core\entities\user\vo\Profile;
use core\forms\manage\user\AdminForm;
use core\forms\manage\user\ManagerForm;
use core\repositories\user\UserRepository;
use yii\helpers\ArrayHelper;

class AdminService
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function create(AdminForm $form){
        $admin = User::createAdmin(
            new Auth($form->email,$form->password),
            new Profile(
                $form->profile->name,
                $form->profile->phone,
                $form->profile->sex,
                $form->profile->birthday,
                $form->profile->language,
                ArrayHelper::getValue($form->photo->config,'path')
            )
        );
        $this->userRepository->save($admin);
        return $admin;
    }

    public function edit($id, AdminForm $form){
        $admin = $this->userRepository->get($id);
        $admin->edit(
            new Auth($form->email,$form->password),
            new Profile(
                $form->profile->name,
                $form->profile->phone,
                $form->profile->sex,
                $form->profile->birthday,
                $form->profile->language,
                ArrayHelper::getValue($form->photo->config,'path')
            )
        );
        $this->userRepository->save($admin);
        return $admin;
    }

    public function delete($id): bool
    {
        $manager = $this->userRepository->get($id);
        $this->userRepository->delete($manager);

        return true;
    }
}