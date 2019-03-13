<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 17.09.2018
 * Time: 13:31
 */

namespace backend\controllers\manage\partner;


use backend\forms\AdminSearch;
use backend\forms\card\TypeSearch;
use backend\forms\partner\PartnerSearch;
use core\entities\card\CardType;
use core\entities\Image;
use core\entities\Images;
use core\entities\partner\Partner;
use core\entities\Rows;
use core\entities\user\User;
use core\forms\manage\card\CardTypeForm;
use core\forms\manage\partner\PartnerForm;
use core\forms\manage\user\AdminForm;
use core\repositories\NotFoundExeption;
use core\useCase\manage\PartnerManageService;
use core\useCase\user\AdminService;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

class DefaultController extends Controller
{
    private $partnerManageService;

    public function __construct(string $id, $module, PartnerManageService $partnerManageService, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->partnerManageService = $partnerManageService;
    }

    public function actionIndex()
    {
        $searchModel = new PartnerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate(){
        $form = new PartnerForm();
        if($form->load(\Yii::$app->request->post()) && $form->validate()){
            try{
                $record = $this->partnerManageService->create($form);
                \Yii::$app->session->setFlash('success','Заведение успешно создано');
                return $this->redirect(['view','id'=>$record->id]);
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

        $form = new PartnerForm($model);
        if($form->load(\Yii::$app->request->post()) && $form->validate()){
            try{
                $record = $this->partnerManageService->edit($model,$form);
                \Yii::$app->session->setFlash('success','Заведение отредактировано');
                return $this->redirect(['view','id'=>$record->id]);
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
            \Yii::$app->session->setFlash('success','Заведение успешно удалено');
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

    public function load($id):? Partner
    {
        if(!$model = Partner::findOne(['id'=>$id])){
            throw new NotFoundExeption('Заведение не найдено');
        }

        return $model;
    }
}