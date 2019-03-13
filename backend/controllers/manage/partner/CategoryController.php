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
use backend\forms\partner\PartnerCategorySearch;
use backend\forms\partner\PartnerSearch;
use core\entities\card\CardType;
use core\entities\Image;
use core\entities\partner\Partner;
use core\entities\partner\PartnerCategory;
use core\entities\Rows;
use core\entities\user\User;
use core\forms\manage\card\CardTypeForm;
use core\forms\manage\partner\PartnerCategoryForm;
use core\forms\manage\partner\PartnerForm;
use core\forms\manage\user\AdminForm;
use core\repositories\NotFoundExeption;
use core\useCase\user\AdminService;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

class CategoryController extends Controller
{
    public function actionIndex()
    {
        $searchModel = new PartnerCategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate(){
        $form = new PartnerCategoryForm();
        if($form->load(\Yii::$app->request->post()) && $form->validate()){
            try{
                $partner = PartnerCategory::create(
                    $form->name
                );
                $partner->setupParent($form->parent_id);
                $partner->setupIcon(ArrayHelper::getValue($form->icon->config,'path'));
                $partner->save();
                \Yii::$app->session->setFlash('success','Категория успешно создана');
                return $this->redirect(['view','id'=>$partner->id]);
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

        $form = new PartnerCategoryForm($model);
        if($form->load(\Yii::$app->request->post()) && $form->validate()){
            try{
                $model->edit(
                     $form->name
                );
                $model->setupParent($form->parent_id);
                $model->setupIcon(ArrayHelper::getValue($form->icon->config,'path'));
                $model->save();
                \Yii::$app->session->setFlash('success','Категория отредактирована');
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
            \Yii::$app->session->setFlash('success','Категория успешно удалена');
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

    public function load($id):? PartnerCategory
    {
        if(!$model = PartnerCategory::findOne(['id'=>$id])){
            throw new NotFoundExeption('Категория не найдена');
        }

        return $model;
    }
}