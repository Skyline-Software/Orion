<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 17.09.2018
 * Time: 13:31
 */

namespace backend\controllers\manage\card;


use backend\forms\AdminSearch;
use backend\forms\card\CardSearch;
use backend\forms\card\TypeSearch;
use core\entities\card\Card;
use core\entities\card\CardType;
use core\entities\Image;
use core\entities\Rows;
use core\entities\user\User;
use core\forms\manage\card\CardForm;
use core\forms\manage\card\CardTypeForm;
use core\forms\manage\user\AdminForm;
use core\repositories\NotFoundExeption;
use core\useCase\user\AdminService;
use Yii;
use yii\db\ActiveRecord;
use yii\web\Controller;

class CardController extends Controller
{
    public function actionIndex()
    {
        $searchModel = new CardSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate(){
        $form = new CardForm();
        if($form->load(\Yii::$app->request->post()) && $form->validate()){
            try{
                $card = Card::create(
                    $form->type_id,
                    $form->number,
                    $form->code,
                    $form->status
                );
                if($form->user_id){
                    $card->buy($form->user_id);
                }
                $card->save();
                \Yii::$app->session->setFlash('success','Новая карта успешно создана');
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

        $form = new CardForm($model);
        if($form->load(\Yii::$app->request->post()) && $form->validate()){
            try{
                 $model->edit(
                     $form->type_id,
                     $form->number,
                     $form->code,
                     $form->status
                );
                if($form->user_id){
                    $model->buy($form->user_id);
                }

                $model->save();

                \Yii::$app->session->setFlash('success','Карта успешно отредактирована');
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
            \Yii::$app->session->setFlash('success','Карта успешно удалена');
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

    public function load($id):? Card
    {
        if(!$model = Card::findOne(['id'=>$id])){
            throw new NotFoundExeption('Карта не найдена');
        }

        return $model;
    }
}