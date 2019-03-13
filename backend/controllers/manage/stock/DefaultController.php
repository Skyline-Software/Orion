<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 03.12.2018
 * Time: 13:59
 */

namespace backend\controllers\manage\stock;


use backend\forms\stock\StockSearch;
use core\entities\stock\Stock;
use core\forms\manage\stock\StockForm;
use core\repositories\NotFoundExeption;
use Yii;
use yii\web\Controller;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        $searchModel = new StockSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate(){
        $form = new StockForm();
        if($form->load(\Yii::$app->request->post()) && $form->validate()){
            try{
                $stock = Stock::create(
                    $form->card_type_id,
                    $form->name,
                    $form->discount,
                    $form->from,
                    $form->to,
                    $form->partner->config,
                    $form->category->config
                );
                $stock->save();
                \Yii::$app->session->setFlash('success','Новая скидка успешно создана');
                return $this->redirect(['view','id'=>$stock->id]);
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

        $form = new StockForm($model);
        if($form->load(\Yii::$app->request->post()) && $form->validate()){
            try{
                $model->edit(
                    $form->card_type_id,
                    $form->name,
                    $form->discount,
                    $form->from,
                    $form->to,
                    $form->partner->config,
                    $form->category->config
                );
                $model->save();

                \Yii::$app->session->setFlash('success','Скидка успешно отредактирована');
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
            \Yii::$app->session->setFlash('success','Скидка успешно удалена');
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

    public function load($id):? Stock
    {
        if(!$model = Stock::findOne(['id'=>$id])){
            throw new NotFoundExeption('Скидка не найдена');
        }

        return $model;
    }
}