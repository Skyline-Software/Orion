<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 17.09.2018
 * Time: 13:16
 */

namespace core\useCase\user;


use api\forms\UserForm;
use core\entities\user\User;
use core\entities\user\vo\Auth;
use core\entities\user\vo\Profile;
use core\forms\manage\user\CustomerForm;
use core\repositories\user\UserRepository;
use yii\helpers\ArrayHelper;

class CustomerService
{
    private $userRepository;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function create(CustomerForm $form){
        $admin = User::createCustomer(
            new Auth($form->email,$form->password),
            new Profile(
                $form->profile->name,
                $form->profile->phone,
                $form->profile->sex,
                $form->profile->birthday,
                $form->profile->language,
                ArrayHelper::getValue($form->photo->config,'path')
            ),
            $form->status
        );
        $this->userRepository->save($admin);
        return $admin;
    }

    public function createByAPI(UserForm $form,$post){
        $customer = User::createCustomer(
            new Auth($form->email,$form->password),
            new Profile(
                $form->name,
                $form->phone,
                $form->sex,
                $form->birthday,
                $form->language,
                ArrayHelper::getValue($post,'photo')
            )
        );

        $customer->save();
        return $customer;
    }

    public function edit($id, CustomerForm $form){
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
            ),
            $form->status
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