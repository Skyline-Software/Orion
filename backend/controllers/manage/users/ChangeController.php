<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 17.09.2018
 * Time: 15:29
 */

namespace backend\controllers\manage\users;


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
            $this->changeService->activateUser($id);
            \Yii::$app->session->setFlash('success','Запись успешно активирована');
        }catch (\RuntimeException | NotFoundExeption $e){
            \Yii::$app->session->setFlash('error',$e->getMessage());
            \Yii::$app->errorHandler->logException($e);
        }

        return $this->goBack();

    }

    public function actionDeactivate($id){
        try{
            $this->changeService->deactivateUser($id);
            \Yii::$app->session->setFlash('success','Запись успешно деактивирована');
        }catch (\RuntimeException | NotFoundExeption $e){
            \Yii::$app->session->setFlash('error',$e->getMessage());
            \Yii::$app->errorHandler->logException($e);
        }

        return $this->goBack();

    }
}