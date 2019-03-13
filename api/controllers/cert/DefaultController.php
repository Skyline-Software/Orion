<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 29.11.2018
 * Time: 19:56
 */

namespace api\controllers\cert;


use api\forms\CardNumberForm;
use common\traits\loadTrait;
use core\entities\card\Card;
use core\entities\cert\FaceValue;
use core\entities\sales\Sales;
use yii\data\ArrayDataProvider;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\Controller;

class DefaultController extends Controller
{

    use loadTrait;

    public function behaviors()
    {
        $behaviours = parent::behaviors();
        $behaviours['authenticator'] = [
            'class' => HttpBearerAuth::class,
            'optional' => ['*']
        ];

        return $behaviours;
    }

    public function actionIndex()
    {
        $user = $this->loadUser();
        if(!$user){
            return ['status'=>'fail','code'=>'NOT_LOGGED_IN'];
        }

        return ['status'=>'ok','result'=> $user->cert];
    }

    public function actionValues()
    {
        return ['status'=>'ok','result'=>(new FaceValue())->getAll()];
    }
}