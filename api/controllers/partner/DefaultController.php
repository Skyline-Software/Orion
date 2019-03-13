<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 06.12.2018
 * Time: 16:41
 */

namespace api\controllers\partner;


use api\form\PartnerSearch;
use api\forms\PartnerForm;
use common\traits\loadTrait;
use core\entities\partner\Partner;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\helpers\ArrayHelper;
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
        $searchModel = new PartnerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->post());

        return [
            'status'=>'ok',
            'result'=>
                [
                    'search' => $searchModel,
                    'items' => $dataProvider['items']->getModels(),
                    'totalCount'=> $dataProvider['count']
                ]
        ];
    }

    public function actionView()
    {
        $form = new PartnerForm();
        $form->load(Yii::$app->request->post(),'');
        if($form->validate()){
            $partner = Partner::find()->where(['id'=>$form->id])->one();
            if($partner){
                return [
                    'status'=>'ok','result'=>[
                        'partner' => $partner,
                    ]
                ];
            }
            return ['status'=>'fail','code'=>'PARTNER_NOT_FOUND'];
        }

        return ['status'=>'fail','code'=>'INVALID_INPUT','errors'=>$form->errors];



    }
}