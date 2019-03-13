<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 03.12.2018
 * Time: 13:00
 */

namespace backend\controllers\manage\cert;


use backend\forms\cert\CertSearch;
use core\entities\cert\Cert;
use core\entities\cert\Item;
use core\forms\manage\cert\CertCreateForm;
use core\forms\manage\cert\CertEditForm;
use core\repositories\NotFoundExeption;
use Yii;
use yii\web\Controller;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        $searchModel = new CertSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate(){
        $form = new CertCreateForm();
        if($form->load(\Yii::$app->request->post()) && $form->validate()){
            try{
                $range = range(0,$form->count);
                foreach ($range as $cert_num){
                    $cert = Cert::createByAdmin(new Item($form->nominal,rand(111111111111,999999999999)));
                    $cert->save();
                }
                if($form->count > 1){
                    \Yii::$app->session->setFlash('success',"Сертификаты успешно созданы");
                }else{
                    \Yii::$app->session->setFlash('success',"Сертификат успешно создан");
                }
                return $this->redirect(['index']);
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

        $form = new CertEditForm($model);
        if($form->load(\Yii::$app->request->post()) && $form->validate()){
            try{
                $model->assignUser($form->user_id);
                $model->save();
                \Yii::$app->session->setFlash('success','Клиент успешно назначен');
                return $this->redirect(['view','id'=>$model->id]);
            }catch (\RuntimeException | NotFoundExeption $e){
                \Yii::$app->session->setFlash('error',$e->getMessage());
                \Yii::$app->errorHandler->logException($e);
                return $this->redirect('index');
            }
        }

        return $this->render('edit',['model'=>$form]);

    }

    public function actionView($id){
        try{
            $cert = $this->load($id);
        }catch (NotFoundExeption $e){
            \Yii::$app->session->setFlash('error',$e->getMessage());
            \Yii::$app->errorHandler->logException($e);
            return $this->redirect('index');
        }

        return $this->render('view',['model'=>$cert]);
    }

    public function actionDelete($id){
        try{
            $model = $this->load($id);
            $model->delete();
            \Yii::$app->session->setFlash('success','Сертификат успешно удален');
        }catch (\RuntimeException | NotFoundExeption $e){
            \Yii::$app->session->setFlash('error',$e->getMessage());
            \Yii::$app->errorHandler->logException($e);
        }

        return $this->redirect('index');
    }

    public function load($id):? Cert
    {
        if(!$model = Cert::findOne(['id'=>$id])){
            throw new NotFoundExeption('Сертификат не найден');
        }

        return $model;
    }
}