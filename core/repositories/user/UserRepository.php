<?php
/**
 * Created by PhpStorm.
 * user: Mopkau
 * Date: 04.08.2018
 * Time: 16:41
 */

namespace core\repositories\user;


use core\entities\user\User;
use core\repositories\NotFoundExeption;
use yii\db\ActiveRecord;

class UserRepository
{
    public function get($id):User
    {
        if(!$user =  User::findOne(['id'=>$id])){
            throw new NotFoundExeption('Пользователь не найден');
        }
        return $user;
    }

    public function getByEmail(string $email): User
    {
        if(!$user = User::find()->where(['email'=>$email])->limit(1)->one()){
            throw new NotFoundExeption('Пользователь не найден');
        }
        return $user;
    }

    public function findByNetworkIdentity($network, $identity):? ActiveRecord
    {
        return User::find()->joinWith('networks n')->andWhere(['n.network' => $network, 'n.identity'=>$identity ])->one();
    }

    public function getByEmailConfirmToken(string $token):ActiveRecord
    {
        return $this->getBy(['email_confirm_token'=>$token]);
    }

    public function getByPasswordResetToken(string $token):ActiveRecord
    {
        return $this->getBy(['reset_token'=>$token]);
    }

    public function existByPasswordResetToken(string $token): bool
    {
        return (bool) User::findByPasswordResetToken($token);
    }

    public function save(User $user): void
    {
        if(!$user->save()){
            throw new \RuntimeException('Saving error.');
        }
    }

    private function getBy(array $condition): ActiveRecord
    {
        if(!$user = User::find()->andWhere($condition)->limit(1)->one()){
            throw new NotFoundExeption('user is not found.');
        }
        return $user;
    }

    public function delete(User $user): void
    {
        if(!$user->delete()){
            throw new \RuntimeException('Deleting error.');
        }
    }
}