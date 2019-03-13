<?php
namespace api\controllers\user;

use api\forms\ResetConfirmForm;
use api\forms\ResetRequestForm;
use core\useCase\auth\PasswordResetService;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\rest\Controller;
use yii\web\User;

/**
 * Site controller
 */
class ResetController extends Controller
{
    private $_passwordResetService;

    public function __construct($id, $module, PasswordResetService $passwordResetService, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->_passwordResetService = $passwordResetService;
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequest()
    {
        $form = new ResetRequestForm();
        $form->email = ArrayHelper::getValue($_POST,'email');
        if($form->validate()){
            try{
                $reset = $this->_passwordResetService->requestByApi($form->email);
                $headers = "From: " . Yii::$app->params['fromName'].'<'.Yii::$app->params['noReplyEmail'].'>' . "\r\n";
                $headers .= "MIME-Version: 1.0\r\n";
                $headers .= "Content-Type: text/html; charset=utf-8\r\n";
                $html = '<html><body>';
                $html .= "Здравствуйте, $reset->name!";
                $html .= "<br>";
                $html .= "<br>";
                $html .= "Для активации перейдите по ссылке:";
                $html .= "<br>";
                $html .= "<a href=\"https://app.ulitsarubinshteina.ru/reset/confirm/".$reset->reset_token."\">https://app.ulitsarubinshteina.ru/reset/confirm/".$reset->reset_token."</a>";
                $html .= "<br>";
                $html .= "<br>";
                $html .= "или укажите в приложении код сброса: <b>".$reset->reset_token."</b>.";
                $html .= "<br>";
                $html .= "<hr>";
                $html .= "С уважением, 3d-робот автоматической доставки собщений.";
                $html .= '</body></html>';

                mail($form->email, 'Восстановление пароля', $html,$headers);
                return ['status'=>'ok','result'=>''];
            }catch (\DomainException $e){
                Yii::$app->errorHandler->logException($e);
                return ['status'=>'fail','code'=>'EXCEPTION','message'=>$e->getMessage()];
            }
        }
        return ['status'=>'fail','code'=>'INVALID_INPUT','errors'=>$form->errors];
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionConfirm()
    {
        $form = new ResetConfirmForm();
        $form->token = ArrayHelper::getValue($_POST,'token');
        $form->pass = ArrayHelper::getValue($_POST,'pass');
        if($form->validate()){
            try {
                $this->_passwordResetService->validateToken($form->token);
            } catch (\DomainException $e) {
                return ['status'=>'fail','code'=>'EXCEPTION','message'=>$e->getMessage()];
            }

            try{
                $this->_passwordResetService->resetByApi($form->token,$form->pass);
                return ['status'=>'ok','result'=>''];
            }catch (\DomainException $e){
                Yii::$app->errorHandler->logException($e);
                return ['status'=>'fail','code'=>'EXCEPTION','message'=>$e->getMessage()];
            }
            return ['status'=>'fail','code'=>'NOT_LOGGED_IN'];
        }
        return ['status'=>'fail','code'=>'INVALID_INPUT','errors'=>$form->errors];

    }

}
