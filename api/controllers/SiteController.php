<?php
namespace api\controllers;

use core\entities\card\CardType;
use core\entities\marketing\RecipientList;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\Controller;

class SiteController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['class'] = HttpBearerAuth::className();
        $behaviors['authenticator']['only'] =['card'];
        return $behaviors;
    }



    public function actionIndex(): array
    {
        return [
            'version' => '1.0.0'
        ];
    }

    public function actionCard(){
        return \Yii::$app->user->identity;
    }

    public function actionDeleteImage()
    {
        $path = $_GET['path'];
        if(!isset($_GET['path'])){
            return ['status'=>'fail','code'=>'PATH_EMPTY'];
        }
        $fullPath = \Yii::getAlias('@storage').'/web/source/'.$path;
        if(file_exists($fullPath)){
            unlink($fullPath);
            return ['status'=>'ok','result'=>''];
        }
        return ['status'=>'fail','code'=>'FILE_DOES_NOT_EXISTS'];

    }

    public function actionViewed($id)
    {
        $rec = RecipientList::find()->where(['id'=>$id])->one();
        $rec->status = RecipientList::WATCHED;
        $rec->update(false);
        echo 'https://api.ulitsarubinshteina.ru/dot.png';
    }
}
