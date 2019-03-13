<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 10.12.2018
 * Time: 13:16
 */

namespace backend\controllers\marketing;


use backend\forms\marketing\EmailConfigSearch;
use backend\forms\marketing\PushConfigSearch;
use core\entities\marketing\EmailConfig;
use core\entities\marketing\PushConfig;
use core\forms\marketing\EmailConfigForm;
use core\forms\marketing\PushConfigForm;
use core\repositories\NotFoundExeption;
use Yii;
use yii\web\Controller;

class PushController extends Controller
{
    public function actionIndex()
    {
        $searchModel = new PushConfigSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate(){
        $form = new PushConfigForm();
        if($form->load(\Yii::$app->request->post()) && $form->validate()){
            try{
                $card = PushConfigForm::create(
                    $form->title,
                    $form->message,
                    $form->card_types,
                    $form->age,
                    $form->sex,
                    $form->partner_categories,
                    $form->partners,
                    $form->is_for_already_has_sale
                );
                $card->save();
                \Yii::$app->session->setFlash('success','Шаблон успешно создан');
                return $this->redirect(['view','id'=>$card->id]);
            }catch (\RuntimeException | NotFoundExeption $e){
                \Yii::$app->session->setFlash('error',$e->getMessage());
                \Yii::$app->errorHandler->logException($e);
                return $this->redirect('index');
            }
        }

        return $this->render('create',['model'=>$form]);
    }

    public function actionEdit($id){

        try{
            $model = $this->load($id);
        }catch (NotFoundExeption $e){
            \Yii::$app->session->setFlash('error',$e->getMessage());
            \Yii::$app->errorHandler->logException($e);
            return $this->redirect('index');
        }

        $form = new PushConfigForm($model);
        if($form->load(\Yii::$app->request->post()) && $form->validate()){
            try{
                $model->edit(
                    $form->title,
                    $form->message,
                    $form->card_types,
                    $form->age,
                    $form->sex,
                    $form->partner_categories,
                    $form->partners,
                    $form->is_for_already_has_sale
                );
                $model->save();

                \Yii::$app->session->setFlash('success','Шаблон успешно отредактирован');
                return $this->redirect(['view','id'=>$model->id]);
            }catch (\RuntimeException | NotFoundExeption $e){
                \Yii::$app->session->setFlash('error',$e->getMessage());
                \Yii::$app->errorHandler->logException($e);
                return $this->redirect('index');
            }
        }

        return $this->render('edit',['model'=>$form]);

    }

    public function actionDelete($id){
        try{
            $model = $this->load($id);
            $model->delete();
            \Yii::$app->session->setFlash('success','Шаблон успешно удален');
        }catch (\RuntimeException | NotFoundExeption $e){
            \Yii::$app->session->setFlash('error',$e->getMessage());
            \Yii::$app->errorHandler->logException($e);
        }

        return $this->redirect('index');
    }

    public function actionView($id){
        try{
            $cardType = $this->load($id);
        }catch (NotFoundExeption $e){
            \Yii::$app->session->setFlash('error',$e->getMessage());
            \Yii::$app->errorHandler->logException($e);
            return $this->redirect('index');
        }

        return $this->render('view',['model'=>$cardType]);
    }

    public function load($id):? PushConfig
    {
        if(!$model = PushConfig::findOne(['id'=>$id])){
            throw new NotFoundExeption('Шаблон не найден');
        }

        return $model;
    }
}