<?php
namespace backend\controllers\auth;

use core\useCase\auth\AuthService;
use Yii;
use yii\web\Controller;
use core\forms\auth\LoginForm;

/**
 * Site controller
 */
class AuthController extends Controller
{
    private $_authService;
    public $layout = 'main-login';

    public function __construct($id, $module,AuthService $authService,array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->_authService = $authService;
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $form = new LoginForm();
        if ($form->load(Yii::$app->request->post())) {
            try{
                $user = $this->_authService->auth($form);
                if(!$user->isUserHasAdminRights()){
                    Yii::$app->session->setFlash('error','У вас нет прав');
                    return $this->goBack();
                }
                Yii::$app->user->login($user,$form->rememberMe ? 3600 * 24 * 30 : 0);

                return $this->redirect(['/manage/agency/default']);
            }catch (\DomainException $e){
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error',$e->getMessage());
            }
        }

        return $this->render('login', [
            'model' => $form,
        ]);
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
