<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 29.11.2018
 * Time: 18:57
 */

namespace api\controllers\user;


use api\forms\LoginForm;
use api\forms\PushTokenForm;
use api\forms\UserForm;
use common\traits\loadTrait;
use core\entities\user\User;
use core\entities\user\UserAuth;
use core\forms\manage\user\CustomerForm;
use core\repositories\NotFoundExeption;
use core\useCase\user\CustomerService;
use trntv\filekit\widget\Upload;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\httpclient\Client;
use yii\rest\Controller;
use yii\web\UploadedFile;

class AuthController extends Controller
{
    use loadTrait;

    private $customerService;

    public function __construct(string $id, $module, CustomerService $customerService, array $config = [])
    {
        $this->customerService = $customerService;
        parent::__construct($id, $module, $config);
    }

    public function actions()
    {
        return [
            'upload' => [
                'class' => 'trntv\filekit\actions\UploadAction',
                'fileparam' => 'photo',
                'deleteRoute' => 'upload-delete'
            ],
        ];
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
            'cors' => [
                'Origin'                           => ['*'],
                'Access-Control-Allow-Origin'    => ['*'],
                'Access-Control-Allow-Headers'    => ['*'],
                'Access-Control-Request-Method'    => ['POST', 'GET'],
                'Access-Control-Allow-Credentials' => true,
                'Access-Control-Max-Age'           => 3600,
            ],
        ];
        return $behaviors;
    }

    public function actionRegistration()
    {
        $form = new UserForm();
        $form->load(\Yii::$app->request->post(),'');
        if($form->validate()){
            $this->loadPhoto();
            try{
                $customer = $this->customerService->createByAPI($form,$_POST);
                $headers = "From: " . Yii::$app->params['fromName'].'<'.Yii::$app->params['noReplyEmail'].'>' . "\r\n";
                $headers .= "MIME-Version: 1.0\r\n";
                $headers .= "Content-Type: text/html; charset=utf-8\r\n";
                $html = '<html><body>';
                $html .= "Здравствуйте, $customer->name!";
                $html .= "<br>";
                $html .= "<br>";
                $html .= "Для Вас зарегистрирована учетная запись в проекте ".Yii::$app->params['fromName'].".";
                $html .= "<br>";
                $html .= "<br>";
                $html .= "Для активации перейдите по ссылке:";
                $html .= "<br>";
                $html .= "<a href=\"https://app.ulitsarubinshteina.ru/activate/".$customer->email."/".$customer->status."\">https://app.ulitsarubinshteina.ru/activate/".$customer->email."/".$customer->status."</a>";
                $html .= "<br>";
                $html .= "<br>";
                $html .= "или укажите в приложении код активации: <b>".$customer->status."</b>.";
                $html .= "<br>";
                $html .= "<hr>";
                $html .= "С уважением, 3d-робот автоматической доставки собщений.";
                $html .= '</body></html>';


                mail($customer->email, 'Регистрация учетной записи', $html,$headers);
                return ['status'=>'ok','result' => $customer];
            }catch (\RuntimeException | NotFoundExeption $e){
                \Yii::$app->errorHandler->logException($e);
                return ['status'=>'fail','code' => 'EXCEPTION','message'=>$e->getMessage()];
            }
        }

        return ['status'=>'fail','code'=>'INVALID_INPUT','errors'=>$form->errors];


    }

    public function actionLogin(){

        $form = new LoginForm();
        $form->load(\Yii::$app->request->post(),'');
        if($form->validate()){
            $user = User::find()->where(['email'=>$form->email])->one();
            if($form->pass){
                if($user->validatePassword($form->pass)){
                    if($user->status === User::STATUS_BANNED_BY_ADMIN){
                        return ['status'=>'fail','code'=>'BANNED_BY_ADMIN'];
                    }

                    if($user->status > 0){
                        if($user->status == $form->code){
                            $user->activate();
                            $user->update(false);
                        }else{
                            return ['status'=>'fail','code'=>'INVALID_CODE'];
                        }
                    }

                    $auth = UserAuth::create($user->id);
                    $auth->setDevice($form->device);
                    $auth->save(false);
                    return ['status'=>'ok','result'=>$auth->token];
                }
                return ['status'=>'fail','code'=>'INVALID_EMAIL_OR_PASS'];
            }elseif($form->code){
                    if($user->status === User::STATUS_BANNED_BY_ADMIN){
                        return ['status'=>'fail','code'=>'BANNED_BY_ADMIN'];
                    }

                    if($user->status > 0){
                        if($user->status == $form->code){
                            $user->activate();
                            $user->update(false);
                        }else{
                            return ['status'=>'fail','code'=>'INVALID_CODE'];
                        }
                    }
                    $auth = UserAuth::create($user->id);
                    $auth->setDevice($form->device);
                    $auth->save(false);
                    return ['status'=>'ok','result'=>$auth->token];
            }
            return ['status'=>'fail','code'=>'INVALID_EMAIL_OR_PASS'];
        }

        return ['status'=>'fail','code'=>'INVALID_INPUT','errors'=>$form->errors];


    }

    public function actionLogout()
    {
        $header = ArrayHelper::getValue(getallheaders(),'Authorization');
        $token = str_replace('Bearer','',$header);

        if(is_null(trim($token)) || empty(trim($token))){
            return ['status'=>'fail','code'=>'TOKEN_NOT_SET'];
        }

        if(!$user = UserAuth::findOne(['token'=>trim($token)])){
            return ['status'=>'fail','code'=>'INVALID_TOKEN'];
        }

        $user->delete();

        return ['status'=>'ok','result'=>''];

    }

    public function actionPushToken()
    {
        $header = ArrayHelper::getValue(getallheaders(),'Authorization');
        $token = str_replace('Bearer','',$header);
        $pushToken = ArrayHelper::getValue($_POST,'push_token');

        if(is_null(trim($token))){
            return ['status'=>'fail','code'=>'TOKEN_NOT_SET'];
        }

        if(is_null($pushToken)){
            return ['status'=>'fail','code'=>'PUSH_TOKEN_NOT_SET'];
        }
        $form = new PushTokenForm();
        $form->load(\Yii::$app->request->post(),'');
        if($form->validate()){
            if(!$auth = UserAuth::findOne(['token'=>trim($token)])){
                return ['status'=>'fail','code'=>'AUTH_NOT_FOUND'];
            }

            $auth->setPushToken($pushToken);
            $auth->update(false);

            return ['status'=>'ok','result'=>''];
        }

        return ['status'=>'fail','code'=>'INVALID_INPUT','errors'=>$form->errors];
    }
}