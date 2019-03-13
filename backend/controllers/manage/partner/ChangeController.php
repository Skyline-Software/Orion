<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 17.09.2018
 * Time: 15:29
 */

namespace backend\controllers\manage\partner;


use core\entities\partner\Partner;
use core\repositories\NotFoundExeption;
use core\useCase\user\StatusService;
use yii\web\Controller;

class ChangeController extends Controller
{
    private $changeService;

    public function __construct(string $id, $module, StatusService $changeService, array $config = [])
    {
        $this->changeService = $changeService;
        parent::__construct($id, $module, $config);
    }

    public function actionActivate($id){
        try{
            $model = $this->load($id);
            $model->status = Partner::STATUS_ACTIVE;
            $model->update(false);
            \Yii::$app->session->setFlash('success','Запись успешно активирована');
        }catch (\RuntimeException | NotFoundExeption $e){
            \Yii::$app->session->setFlash('error',$e->getMessage());
            \Yii::$app->errorHandler->logException($e);
        }

        return $this->redirect(['/manage/partner/default/index']);

    }

    public function actionDeactivate($id){
        try{
            $model = $this->load($id);
            $model->status = Partner::STATUS_HIDE;
            $model->update(false);
            \Yii::$app->session->setFlash('success','Запись успешно деактивирована');
        }catch (\RuntimeException | NotFoundExeption $e){
            \Yii::$app->session->setFlash('error',$e->getMessage());
            \Yii::$app->errorHandler->logException($e);
        }

        return $this->redirect(['/manage/partner/default/index']);

    }

    public function load($id):? Partner
    {
        if(!$model = Partner::findOne(['id'=>$id])){
            throw new NotFoundExeption('Заведение не найдено');
        }

        return $model;
    }
}