<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 28.11.2018
 * Time: 18:46
 */

namespace backend\controllers\manage\discount;


use backend\forms\card\DiscountSearch;
use core\entities\card\CardAndPartner;
use core\entities\card\CardType;
use yii\web\Controller;
use yii2mod\editable\EditableAction;

class DefaultController extends Controller
{
    public function actions()
    {
        return [
            'change-discount' => [
                'class' => EditableAction::class,
                'modelClass' => CardAndPartner::class,
            ],
        ];
    }

    public function actionList($id)
    {
        $searchModel = new DiscountSearch();
        $dataProvider = $searchModel->search($id, \Yii::$app->request->queryParams);
        $cardType = CardType::findOne(['id'=>$id]);
        return $this->render('index', [
            'cardType' => $cardType,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }
}