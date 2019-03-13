<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 29.11.2018
 * Time: 20:13
 */

namespace api\controllers\card;


use core\entities\card\CardType;
use yii\rest\Controller;

class TypeController extends Controller
{
    public function actionIndex()
    {
        return ['status' => 'ok', 'result' => CardType::find()->all()];
    }

}