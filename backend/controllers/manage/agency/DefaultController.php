<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 13.03.2019
 * Time: 16:17
 */

namespace backend\controllers\manage\agency;


use backend\forms\agency\AgencySearch;
use core\entities\agency\Agency;
use core\forms\manage\agency\AgencyForm;
use core\repositories\NotFoundExeption;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        $searchModel = new AgencySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate(){
        $form = new AgencyForm();
        if($form->load(\Yii::$app->request->post()) && $form->validate()){
            try{
                $agency = Agency::create(
                    $form->name,
                    ArrayHelper::getValue($form->logo->config,'path'),
                    $form->web_site,
                    $form->status
                );
                $agency->save();
                \Yii::$app->session->setFlash('success','Агенство успешно создано');
                return $this->redirect(['view','id'=>$agency->id]);
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

        $form = new AgencyForm($model);
        if($form->load(\Yii::$app->request->post()) && $form->validate()){
            try{
                $model->edit(
                    $form->name,
                    ArrayHelper::getValue($form->logo->config,'path'),
                    $form->web_site,
                    $form->status
                );
                $model->save();

                \Yii::$app->session->setFlash('success','Агенство отредактировано');
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
            \Yii::$app->session->setFlash('success','Агенство успешно удалено');
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

    public function load($id):? Agency
    {
        if(!$model = Agency::findOne(['id'=>$id])){
            throw new NotFoundExeption('Агенство не найдено');
        }

        return $model;
    }
}