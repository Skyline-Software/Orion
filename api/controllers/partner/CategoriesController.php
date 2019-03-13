<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 09.12.2018
 * Time: 17:05
 */

namespace api\controllers\partner;


use core\entities\partner\PartnerCategory;
use yii\db\Expression;
use yii\rest\Controller;

class CategoriesController extends Controller
{
    public function actionList(){
        return ['status'=>'ok','result'=>PartnerCategory::find()->andFilterWhere(['parent_id'=>0])->all()];
    }
}