<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 17.09.2018
 * Time: 11:20
 */

namespace core\forms\manage\user;


use core\entities\Image;
use core\entities\Rows;
use core\entities\user\User;
use core\forms\ImageForm;
use core\forms\MultipleInputForm;
use core\helpers\AgencyHelper;
use elisdn\compositeForm\CompositeForm;
use Yii;

/**
 * Class ManagerForm
 * @package core\forms\manage\user
 * @property ProfileForm $profile
 * @property ImageForm $photo
 * @property MultipleInputForm $agencies
 */
class AgencyAdminForm extends CompositeForm
{
    public $email;
    public $password;
    public $status;
    public $working_status;
    public $price;
    public $coordinates;

    private $_user;

    public function __construct(User $user = null, array $config = [])
    {
        if($user){
            $this->email = $user->email;
            $this->status = $user->status;
            $this->profile = new ProfileForm($user->profile);
            $this->photo = new ImageForm('PhotoForm', new Image($user->profile->photo));
            if(!Yii::$app->user->identity->isAdmin()){
                $this->agencies = new MultipleInputForm('AgenciesForm', new Rows($user->getAgencyAssn()->where(['in','agency_id',AgencyHelper::getAllowedAgenciesIds()])->all()));
            }else{
                $this->agencies = new MultipleInputForm('AgenciesForm', new Rows($user->agencyAssn));
            }

            $this->_user = $user;
        }else{
            $this->profile = new ProfileForm();
            $this->photo = new ImageForm('PhotoForm');
            $this->agencies = new MultipleInputForm('AgenciesForm');
        }

        parent::__construct($config);
    }

    public function rules()
    {
        if(!$this->_user){
            return [
                [['email','password','status','working_status','price','coordinates'],'safe'],
                [['status'],'default','value' => "0"],
                ['email', 'email'],
                ['email', 'string', 'max' => 255],
                [['email'], 'unique', 'targetClass' => User::class, 'message' => Yii::t('backend','Этот E-mail уже занят.')]
            ];
        }

        return [
            [['email','password','status','working_status','price','coordinates'],'safe'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            [['email'], 'unique', 'targetClass' => User::class, 'filter' => ['<>', 'id', $this->_user->id], 'message' => Yii::t('backend','Этот E-mail уже занят.')]
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => Yii::t('backend','E-mail'),
            'password' => Yii::t('backend','Пароль'),
            'coordinates' => Yii::t('backend','Координаты'),
            'working_status' => Yii::t('backend','Рабочий статус'),
            'price' => Yii::t('backend','Цена'),
        ];
    }

    public function internalForms()
    {
        return ['profile','photo','agencies'];
    }
}