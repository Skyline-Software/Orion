<?php
/**
 * Created by PhpStorm.
 * user: Mopkau
 * Date: 04.08.2018
 * Time: 22:15
 */

namespace api\controllers\network;


use core\useCase\auth\NetworkService;
use yii\authclient\AuthAction;
use yii\authclient\ClientInterface;
use yii\helpers\ArrayHelper;
use yii\rest\Controller;
use yii\web\Response;

class AuthController extends Controller
{
    private $_networkService;

    public function __construct(string $id, $module, NetworkService $networkService, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->_networkService = $networkService;
    }

    public function actions()
    {
        return [
            'auth' => [
                'class' => AuthAction::class,
                'successCallback' => [$this, 'onAuthSuccess']
            ]
        ];
    }

    public function onAuthSuccess(ClientInterface $client):Response
    {
        $network = $client->getId();
        $attributes = $client->getUserAttributes();
        $identity = ArrayHelper::getValue($attributes,'id');
        if($network === 'facebook'){
            $name = ArrayHelper::getValue($attributes,'name');
            $email = ArrayHelper::getValue($attributes,'email');
        }
        if($network === 'vkontakte'){
            $name = ArrayHelper::getValue($attributes,'first_name');
            $email = ArrayHelper::getValue($attributes,'email');
        }
        try{
            $user = $this->_networkService->auth($network,$identity,$name,$email);
            return new Response([
                'content' => [
                    'status'=>'ok',
                    'data' => $user
                ]]
            );
        }catch (\DomainException $e){
            \Yii::$app->errorHandler->logException($e);
            return new Response([
                    'content' => [
                        'status'=>'ok',
                        'data' => $e->getMessage()
                    ]]
            );
        }
    }
}