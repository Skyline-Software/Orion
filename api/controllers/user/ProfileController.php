<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 29.11.2018
 * Time: 19:50
 */

namespace api\controllers\user;


use api\forms\ProfileForm;
use common\traits\loadTrait;
use core\entities\Image;
use core\entities\user\User;
use core\entities\user\vo\Auth;
use core\entities\user\vo\Profile;
use yii\filters\auth\HttpBearerAuth;
use yii\helpers\ArrayHelper;
use yii\rest\Controller;
use yii\web\UploadedFile;

class ProfileController extends Controller
{
    use loadTrait;

    public function behaviors()
    {
        $behaviours = parent::behaviors();
        $behaviours['authenticator'] = [
            'class' => HttpBearerAuth::class,
            'optional' => ['*']
        ];

        return $behaviours;
    }

    public function actionView(){
        $user = User::findIdentity(\Yii::$app->user->getId());
        if($user){
            return ['status'=>'ok','result'=>$user];
        }
        return ['status'=>'fail','code'=>'NOT_LOGGED_IN'];
    }

    public function actionEdit(){
        $user = $this->loadUser();
        $delPhoto = ArrayHelper::getValue($_POST,'delPhoto');
        $form = new ProfileForm();
        $form->load(\Yii::$app->request->post(),'');
        if($form->validate()){
            $this->loadPhoto();
            if($user){
                $pass = ArrayHelper::getValue($_POST,'pass');
                $user->editByApi(
                    new Auth($user->email,$pass),
                    new Profile(
                        $form->name,
                        $form->phone,
                        $form->sex,
                        $form->birthday,
                        $form->language,
                        ArrayHelper::getValue($_POST,'photo')
                    ),
                    $delPhoto
                );
                $user->save();
                return ['status'=>'ok','result'=>$user];
            }

            return ['status'=>'fail','code'=>'NOT_LOGGED_IN'];
        }

        return ['status'=>'fail','code'=>'INVALID_INPUT','errors'=>$form->errors];
    }
}