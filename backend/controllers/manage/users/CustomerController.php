<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 17.09.2018
 * Time: 13:31
 */

namespace backend\controllers\manage\users;


use backend\forms\CustomerSearch;
use core\entities\user\User;
use core\forms\manage\user\CustomerForm;
use core\repositories\NotFoundExeption;
use core\useCase\user\CustomerService;
use Yii;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\web\Controller;

class CustomerController extends Controller
{
    private $customerService;

    public function __construct(string $id, $module, CustomerService $customerService, array $config = [])
    {
        $this->customerService = $customerService;
        parent::__construct($id, $module, $config);
    }

    public function actionIndex()
    {
        $searchModel = new CustomerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Yii::$app->getUser()->setReturnUrl(['/manage/users/customer/index']);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@']
                    ]
                ]
            ]
        ];
    }

    public function actionCreate(){
        $form = new CustomerForm();
        if($form->load(\Yii::$app->request->post()) && $form->validate()){
            try{
                $customer = $this->customerService->create($form);
                \Yii::$app->session->setFlash('success','Клиент успешно создан');
                return $this->redirect(['view','id'=>$customer->id]);
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

        $form = new CustomerForm($user);
        if($form->load(\Yii::$app->request->post()) && $form->validate()){
            try{
                $customer = $this->customerService->edit($id,$form);
                \Yii::$app->session->setFlash('success','Клиент успешно отредактирован');
                return $this->redirect(['view','id'=>$customer->id]);
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
            $this->customerService->delete($id);
            \Yii::$app->session->setFlash('success','Клиент успешно удален');
        }catch (\RuntimeException | NotFoundExeption $e){
            \Yii::$app->session->setFlash('error',$e->getMessage());
            \Yii::$app->errorHandler->logException($e);
        }

        return $this->redirect('index');
    }

    public function actionView($id){
        try{
            $customer = $this->loadUser($id);
        }catch (NotFoundExeption $e){
            \Yii::$app->session->setFlash('error',$e->getMessage());
            \Yii::$app->errorHandler->logException($e);
            return $this->redirect('index');
        }

        return $this->render('view',['model'=>$customer]);
    }

    public function loadUser($id):?User
    {
        if(array_search($id,Yii::$app->user->getIdentity()->getUserAllowIDs()) === false){
            throw new Exception('Нет прав',400);
        }
        if(!$user = User::findOne(['id'=>$id])){
            throw new NotFoundExeption('Пользователь не найден');
        }

        return $user;
    }
}