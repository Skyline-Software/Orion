<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 06.12.2018
 * Time: 17:03
 */

namespace api\controllers\user;


use api\forms\PartnerForm;
use core\entities\favorites\UserFavorites;
use core\entities\partner\Partner;
use core\entities\user\User;
use yii\filters\auth\HttpBearerAuth;
use yii\helpers\ArrayHelper;
use yii\rest\Controller;

class FavoritesController extends Controller
{
    public function behaviors()
    {
        $behaviours = parent::behaviors();
        $behaviours['authenticator'] = [
            'class' => HttpBearerAuth::class,
            'optional' => ['*']
        ];

        return $behaviours;
    }

    public function actionAdd()
    {
        $user = User::findIdentity(\Yii::$app->user->getId());
        if(!$user){
            return ['status'=>'fail','code'=>'NOT_LOGGED_IN'];
        }

        $form = new PartnerForm();
        $form->id = ArrayHelper::getValue($_POST,'partner_id');
        if($form->validate()){
            if(!$partner = Partner::findOne(['id'=>$form->id])){
                return ['status'=>'fail','code'=>'PARTNER_NOT_FOUND'];
            }

            if($find = UserFavorites::find()->where(['partner_id'=>$form->id,'user_id'=>$user->id])->one()){
                return ['status'=>'fail','code'=>'ALREADY_IN'];
            }

            $add = UserFavorites::add($user->id,$form->id);
            $add->save();

            return ['status'=>'ok','result'=>''];
        }
        return ['status'=>'fail','code'=>'INVALID_INPUT','errors'=>$form->errors];


    }

    public function actionRemove()
    {
        $user = User::findIdentity(\Yii::$app->user->getId());
        if(!$user){
            return ['status'=>'fail','code'=>'NOT_LOGGED_IN'];
        }

        $form = new PartnerForm();
        $form->id = ArrayHelper::getValue($_POST,'partner_id');
        if($form->validate()){
            if(!$find = UserFavorites::find()->where(['partner_id'=>$form->id,'user_id'=>$user->id])->one()){
                return ['status'=>'fail','code'=>'NOT_IN_YOUR_FAVORITES'];
            }

            UserFavorites::remove($user->id,$form->id);

            return ['status'=>'ok','result'=>''];
        }
        return ['status'=>'fail','code'=>'INVALID_INPUT','errors'=>$form->errors];
    }
}