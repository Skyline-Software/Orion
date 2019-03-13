<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 17.09.2018
 * Time: 13:31
 */

namespace backend\controllers\manage\card;


use backend\forms\AdminSearch;
use backend\forms\card\TypeSearch;
use core\entities\card\CardType;
use core\entities\Image;
use core\entities\Rows;
use core\entities\user\User;
use core\forms\manage\card\CardTypeForm;
use core\forms\manage\user\AdminForm;
use core\repositories\NotFoundExeption;
use core\useCase\user\AdminService;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

class TypeController extends Controller
{
    public function actionIndex()
    {
        $searchModel = new TypeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate(){
        $form = new CardTypeForm();
        if($form->load(\Yii::$app->request->post()) && $form->validate()){
            try{
                $cardType = CardType::create(
                    $form->name,
                    $form->description,
                    ArrayHelper::getValue($form->photo->config,'path'),
                    $form->support_phone,
                    $form->price,
                    $form->validity
                );
                $cardType->save();
                $cardType->addPartners();
                \Yii::$app->session->setFlash('success','Новый тип карты успешно создан');
                return $this->redirect(['view','id'=>$cardType->id]);
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

        $form = new CardTypeForm($model);
        if($form->load(\Yii::$app->request->post()) && $form->validate()){
            try{
                 $model->edit(
                    $form->name,
                    $form->description,
                    ArrayHelper::getValue($form->photo->config,'path'),
                    $form->support_phone,
                    $form->price,
                    $form->validity
                );
                $model->save();
                $model->addPartners();

                \Yii::$app->session->setFlash('success','Тип карты успешно отредактирован');
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
            \Yii::$app->session->setFlash('success','Тип карты успешно удален');
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

    public function load($id):? CardType
    {
        if(!$model = CardType::findOne(['id'=>$id])){
            throw new NotFoundExeption('Тип карты не найден');
        }

        return $model;
    }
}