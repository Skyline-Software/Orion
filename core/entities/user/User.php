<?php
namespace core\entities\user;

use common\traits\ImageTrait;
use core\entities\agency\Agency;
use core\entities\assn\UserAgencyAssn;
use core\entities\card\Card;
use core\entities\card\UserCard;
use core\entities\cert\Cert;
use core\entities\cert\UserCert;
use core\entities\favorites\UserFavorites;
use core\entities\sales\Sales;
use core\entities\user\vo\Auth;
use core\entities\user\vo\Profile;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * user data model
 *
 * @property integer $id
 * @property string $password_hash
 * @property integer $type
 * @property string $email
 * @property integer $created_at
 * @property integer $vk_token
 * @property integer $fb_token
 * @property string $reset_token
 * @property integer $status
 *
 * @property string $name;
 * @property string $phone;
 * @property string $sex;
 * @property string $birthday;
 * @property string $language;
 * @property string $photo;
 *
 * @property Profile $profile;
 * @property UserAgencyAssn $agencyAssn
 * @property Agency[] $agencies
 */
class User extends ActiveRecord implements IdentityInterface
{
    use ImageTrait;

    const STATUS_BANNED_BY_ADMIN = -1;
    const STATUS_ACTIVE = 0;

    const ROLE_CUSTOMER = 0;
    const ROLE_ADMIN = 2;
    const ROLE_AGENCY_ADMIN = 3;
    const ROLE_AGENT = 4;


    public static function createAdmin(Auth $auth, Profile $profile): self
    {
        $user = new static();
        $user->email = $auth->email;
        $user->setPassword($auth->password);
        $user->status = self::STATUS_ACTIVE;
        $user->type = self::ROLE_ADMIN;

        $user->name = $profile->name;
        $user->phone = $profile->phone;
        $user->sex = $profile->sex;
        $user->birthday = $profile->birthday;
        $user->language = $profile->language;
        $user->photo = $profile->photo;

        $user->created_at = time();

        return $user;
    }

    public static function createCustomer(Auth $auth, Profile $profile, $status = false): self
    {
        $user = new static();
        $user->email = $auth->email;
        $user->setPassword($auth->password);
        $user->name = $profile->name;
        $user->phone = $profile->phone;
        $user->sex = $profile->sex;
        $user->birthday = $profile->birthday;
        $user->language = $profile->language;
        $user->photo = $profile->photo;

        $user->created_at = time();
        if(is_string($status)){
            $user->status = $status;
        }else{
            $user->status = rand(100000000,999999999);
        }

        $user->type = self::ROLE_CUSTOMER;


        return $user;
    }

    public function edit(Auth $auth, Profile $profile, $status = null): void
    {
        $this->email = $auth->email;
        if(!empty($auth->password)){
            $this->setPassword($auth->password);
        }
        $this->name = $profile->name;
        $this->phone = $profile->phone;
        $this->sex = $profile->sex;
        $this->birthday = $profile->birthday;
        $this->language = $profile->language;
        if(!empty($this->photo)){
            $this->deleteImage($this->photo);
        }
        $this->photo = $profile->photo;
        if($status != null){
            $this->status = (int)$status;
        }


    }

    public function editByApi(Auth $auth, Profile $profile, $delPhoto = 0): void
    {
        $this->email = $auth->email;
        if(!empty($auth->password)){
            $this->setPassword($auth->password);
        }
        $this->name = $profile->name;
        $this->phone = $profile->phone;
        $this->sex = $profile->sex;
        $this->birthday = $profile->birthday;
        $this->language = $profile->language;

        if($delPhoto == 1 && !empty($this->photo)){
            $this->deleteImage($this->photo);
        }

        if($delPhoto == 1 && empty($this->photo)){
            $this->deleteImage($this->photo);
        }

        if(!empty($profile->photo) && !empty($this->photo)){
            $this->deleteImage($this->photo);
        }

        $this->photo = $profile->photo;
    }




    public function activate():void
    {
        $this->status = self::STATUS_ACTIVE;
    }

    public function deactivate():void
    {
        $this->status = self::STATUS_BANNED_BY_ADMIN;
    }

    public function isActive():bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isBanned():bool
    {
        return $this->status === self::STATUS_BANNED_BY_ADMIN;
    }

    public function getProfile():Profile
    {
        return new Profile(
            $this->name,
            $this->phone,
            $this->sex,
            $this->birthday,
            $this->language,
            $this->photo
        );
    }

    public function getUserAuth():ActiveQuery
    {
        return $this->hasOne(UserAuth::class,['user_id'=>'id']);
    }

    public function requestPasswordReset():void
    {
        if(!empty($this->reset_token) && self::isPasswordResetTokenValid($this->reset_token)){
            throw new \DomainException('Password resetting is already requested');
        }
        $this->reset_token = md5($this->password_hash);
    }

    public function resetPassword(string $password):void
    {
        if(empty($this->reset_token)){
            throw  new \DomainException('Password resseting is not requested');
        }
        $this->setPassword($password);
        $this->reset_token = null;
    }

    public function isAdmin():bool
    {
        return $this->type === self::ROLE_ADMIN;
    }

    public function isCustomer():bool
    {
        return $this->type === self::ROLE_CUSTOMER;
    }

    public static function tableName():string
    {
        return '{{%users}}';
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function findIdentity($id):?self
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findIdentityByAccessToken($token, $type = NULL)
    {
        if($user = self::find()->joinWith('userAuth ua')->where(['ua.token'=>$token,'users.status'=>self::STATUS_ACTIVE])->one()){
            UserAuth::updateActivity($token);
            return $user;
        }
    }

    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getAuthKey(){}

    public function validateAuthKey($authKey){}

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public function setPassword($password)
    {
        if(is_null($password)){
           $password = Yii::$app->security->generateRandomString(9);
        }
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function attributeLabels()
    {
        return [
            'created_at' =>'Создан',
            'email' =>'E-mail',
            'status' =>'Статус',
            'name' => 'ФИО',
            'type' => 'Тип',
            'phone' => 'Номер телефона',
            'sex' => 'Пол',
            'birthday' => 'Дата рождения',
            'language' => 'Язык',

        ];
    }

    public function getFullName(){
        return $this->email;
    }

    public function getCards():ActiveQuery
    {
        return $this->hasMany(Card::class,['user_id'=>'id']);
    }

    public function getSales()
    {
        return $this->hasMany(Sales::class,['user_id'=>'id']);
    }

    public function getCert():ActiveQuery
    {
        return $this->hasMany(Cert::class,['user_id'=>'id']);
    }

    public function getFavorites():ActiveQuery
    {
        return $this->hasMany(UserFavorites::class,['user_id'=>'id']);
    }

    public function fields()
    {
        return [
            'id',
            'email',
            'created_at',
            'name',
            'phone',
            'sex',
            'birthday',
            'photo',
            'language'

            /*'status',*/
            /*'reset_token',
            'vk_token',
            'fb_token',*/
            #'cards',
            #'cert',
            #'networks',
            #'favorites'
        ];
    }

    public function getAgencies():ActiveQuery
    {
        return $this->hasMany(Agency::class,['id'=>'agency_id'])->via('agencyAssn');
    }

    public function getAgencyAssn():ActiveQuery
    {
        return $this->hasMany(UserAgencyAssn::class,['user_id'=>'id']);
    }

    public function behaviors()
    {
        return [
            'manyRelation' => [
                'class' => SaveRelationsBehavior::class,
                'relations' => ['agencyAssn']
            ]
        ];
    }

    public function isUserHasAdminRights():bool
    {
        if($this->isAdmin()){
            return true;
        }
        foreach ($this->agencyAssn as $assn){
            if($assn->role === self::ROLE_AGENCY_ADMIN || $assn->role === self::ROLE_ADMIN){
                return true;
            }

        }
        return false;

    }
}
