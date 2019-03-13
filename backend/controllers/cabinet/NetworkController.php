<?php
/**
 * Created by PhpStorm.
 * user: Mopkau
 * Date: 04.08.2018
 * Time: 22:15
 */

namespace backend\controllers\cabinet;


use core\useCase\auth\NetworkService;
use yii\authclient\AuthAction;
use yii\authclient\ClientInterface;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Controller;

class NetworkController extends Controller
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
            'attach' => [
                'class' => AuthAction::class,
                'successCallback' => [$this, 'onAuthSuccess'],
                'successUrl' => Url::to(['cabinet/default/index'])
            ]
        ];
    }

    public function onAuthSuccess(ClientInterface $client): void
    {
        d($client);

        $network = $client->getId();
        $attributes = $client->getUserAttributes();
        $identity = ArrayHelper::getValue($attributes,'id');

        try{
            $this->_networkService->attach(\Yii::$app->user->id, $network, $identity);
            \Yii::$app->session->setFlash('success','Сеть успешно подключена.');
        }catch (\DomainException $e){
            \Yii::$app->errorHandler->logException($e);
            \Yii::$app->session->setFlash('error',$e->getMessage());
        }
    }
}