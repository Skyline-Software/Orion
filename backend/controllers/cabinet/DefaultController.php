<?php
/**
 * Created by PhpStorm.
 * user: Mopkau
 * Date: 04.08.2018
 * Time: 23:23
 */

namespace backend\controllers\cabinet;


use core\forms\manage\user\AccountForm;
use core\forms\manage\user\AdminForm;
use core\useCase\user\AdminService;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

class DefaultController extends Controller
{
    private $adminService;


    public function __construct(
        string $id,
        $module,
        AdminService $customerService,
        array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->adminService = $customerService;
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

    public function actionProfile()
    {
        $form = new AdminForm(\Yii::$app->user->identity);
        if($form->load(\Yii::$app->request->post()) && $form->validate())
        {
            try{
                $this->adminService->edit(\Yii::$app->user->id, $form);
                Yii::$app->language = $form->profile->language;
                \Yii::$app->session->setFlash('success',Yii::t('backend','Данные успешно сохранены'));
                return $this->refresh();
            }catch (\DomainException | \RuntimeException $e){
                \Yii::$app->session->setFlash('error',$e->getMessage());
                \Yii::$app->errorHandler->logException($e);
            }
        }

        return $this->render('//manage/users/admin/edit',['model'=>$form]);
    }
}