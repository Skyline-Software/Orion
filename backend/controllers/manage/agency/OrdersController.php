<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 26.03.2019
 * Time: 15:56
 */

namespace backend\controllers\manage\agency;


use backend\forms\agency\OrdersSearch;
use core\entities\agency\Order;
use core\entities\user\User;
use core\forms\manage\agency\OrderForm;
use core\repositories\NotFoundExeption;
use Yii;
use yii\web\Controller;

class OrdersController extends Controller
{
    public function actionIndex()
    {
        $searchModel = new OrdersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate(){
        $form = new OrderForm();
        if($form->load(\Yii::$app->request->post()) && $form->validate()){
            try{
                $order = Order::create(
                   $form->agency_id,
                   $form->agent_id,
                   $form->user_id,
                   $form->start_coordinates,
                   $form->end_coordinates,
                   $form->price,
                   $form->start_time,
                   $form->status
                );
                $order->setupEndTime($form->end_time);
                $order->setupComment($form->comment);
                $order->setupRating($form->rating);

                $order->save();
                \Yii::$app->session->setFlash('success','Заказ создан');
                return $this->redirect(['view','id'=>$order->id]);
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
            $order = $this->load($id);
        }catch (NotFoundExeption $e){
            \Yii::$app->session->setFlash('error',$e->getMessage());
            \Yii::$app->errorHandler->logException($e);
            return $this->redirect('index');
        }

        $form = new OrderForm($order);
        if($form->load(\Yii::$app->request->post()) && $form->validate()){
            try{
                $order->edit(
                    $form->agency_id,
                    $form->agent_id,
                    $form->user_id,
                    $form->start_coordinates,
                    $form->end_coordinates,
                    $form->price,
                    $form->start_time,
                    $form->status
                );
                $order->setupEndTime($form->end_time);
                $order->setupComment($form->comment);
                $order->setupRating($form->rating);
                $order->save();

                \Yii::$app->session->setFlash('success','Заказ изменен');
                return $this->redirect(['view','id'=>$order->id]);
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
            \Yii::$app->session->setFlash('success','Заказ удален');
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

    public function load($id):? Order
    {
        if(!$model = Order::findOne(['id'=>$id])){
            throw new NotFoundExeption('Заказ не найден');
        }

        return $model;
    }

    public function actionAgents()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $agency_id = $parents[0];
                $out = array_map(function ($user){
                    return ['id'=>$user->id,'name'=>$user->name];
                },User::find()
                    ->joinWith('agencyAssn assn')
                    ->where(['assn.agency_id'=>$agency_id,'assn.role'=>User::ROLE_AGENT])
                    ->all()
                );
                return ['output'=>$out, 'selected'=>''];
            }
        }
        return ['output'=>'', 'selected'=>''];
    }

    public function actionCustomers()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $agency_id = $parents[0];
                $out = array_map(function ($user){
                    return ['id'=>$user->id,'name'=>$user->name];
                },User::find()
                    ->joinWith('agencyAssn assn')
                    ->where(['assn.agency_id'=>$agency_id,'assn.role'=>User::ROLE_CUSTOMER])
                    ->all()
                );
                return ['output'=>$out, 'selected'=>''];
            }
        }
        return ['output'=>'', 'selected'=>''];
    }
}