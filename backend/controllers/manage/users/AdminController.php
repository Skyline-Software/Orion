<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 17.09.2018
 * Time: 13:31
 */

namespace backend\controllers\manage\users;


use backend\forms\AdminSearch;
use core\entities\cert\FaceValue;
use core\entities\user\User;
use core\forms\manage\user\AdminForm;
use core\repositories\NotFoundExeption;
use core\useCase\user\AdminService;
use Yii;
use yii\base\Exception;
use yii\web\Controller;

class AdminController extends Controller
{
    private $adminService;

    public function __construct(string $id, $module, AdminService $customerService, array $config = [])
    {
        $this->adminService = $customerService;
        parent::__construct($id, $module, $config);
    }

    public function beforeAction($action)
    {
        if(!Yii::$app->user->getIdentity()->isAdmin()){
            throw new Exception(Yii::t('backend','Нет прав'),400);
        }
        return parent::beforeAction($action);

    }

    public function actionIndex()
    {
        $searchModel = new AdminSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate(){
        $form = new AdminForm();
        if($form->load(\Yii::$app->request->post()) && $form->validate()){
            try{
                $admin = $this->adminService->create($form);
                \Yii::$app->session->setFlash('success',
                Yii::t('backend','Администратор успешно создан'));
                return $this->redirect(['view','id'=>$admin->id]);
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
            $user = $this->loadUser($id);
        }catch (NotFoundExeption $e){
            \Yii::$app->session->setFlash('error',$e->getMessage());
            \Yii::$app->errorHandler->logException($e);
            return $this->redirect('index');
        }

        $form = new AdminForm($user);
        if($form->load(\Yii::$app->request->post()) && $form->validate()){
            try{
                $admin = $this->adminService->edit($id,$form);
                \Yii::$app->session->setFlash('success',
                    Yii::t('backend','Администратор успешно отредактирован'));
                return $this->redirect(['view','id'=>$admin->id]);
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
            $this->adminService->delete($id);
            \Yii::$app->session->setFlash('success',
                Yii::t('backend','Администратор успешно удален'));
        }catch (\RuntimeException | NotFoundExeption $e){
            \Yii::$app->session->setFlash('error',$e->getMessage());
            \Yii::$app->errorHandler->logException($e);
        }

        return $this->redirect('index');
    }

    public function actionView($id){
        try{
            $admin = $this->loadUser($id);
        }catch (NotFoundExeption $e){
            \Yii::$app->session->setFlash('error',$e->getMessage());
            \Yii::$app->errorHandler->logException($e);
            return $this->redirect('index');
        }

        return $this->render('view',['model'=>$admin]);
    }

    public function loadUser($id):?User
    {
        if(!$user = User::findIdentity($id)){
            throw new NotFoundExeption(
                Yii::t('backend','Пользователь не найден, или не активирован'));
        }

        return $user;
    }
}