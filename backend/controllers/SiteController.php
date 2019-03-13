<?php
namespace backend\controllers;

use backend\forms\SaleSearch;
use core\entities\buy\Buy;
use core\entities\card\Card;
use core\entities\card\CardType;
use core\entities\cert\Cert;
use core\entities\cert\UserCert;
use core\entities\contractor\Contractor;
use core\entities\contractor\ContractorRequisites;
use core\entities\contractor\vo\Address;
use core\entities\contractor\vo\Addresses;
use core\entities\marketing\RecipientList;
use core\entities\partner\Partner;
use core\entities\sales\Sales;
use core\entities\user\User;
use core\entities\user\UserAuth;
use paragraph1\phpFCM\Recipient\Device;
use Yii;
use yii\db\Expression;
use yii\db\JsonExpression;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use core\forms\auth\LoginForm;
/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
    public function actionStatistics()
    {
        $from = ArrayHelper::getValue($_GET,'from');
        if(!is_null($from)){
            $from = date_create_from_format('d.m.y',$from);
            $from->setTime(0,0,0);
        }

        $to = ArrayHelper::getValue($_GET,'to');
        if(!is_null($to)){
            $to = date_create_from_format('d.m.y',$to);
            $to->setTime(0,0,0);
        }

        $users = User::find()
            ->andFilterWhere(['>=', 'created_at', $from ? $from->getTimestamp() : null])
            ->andFilterWhere(['<=', 'created_at', $to ? $to->getTimestamp() : null])
            ->all();
        $usingAndroid = UserAuth::find()
            ->where(['device'=>'android'])
            ->andFilterWhere(['>=', 'lastact', $from ? $from->getTimestamp() : null])
            ->andFilterWhere(['<=', 'lastact', $to ? $to->getTimestamp() : null])
            ->all();
        $usingIOS = UserAuth::find()
            ->where(['device'=>'iphone'])
            ->andFilterWhere(['>=', 'lastact', $from ? $from->getTimestamp() : null])
            ->andFilterWhere(['<=', 'lastact', $to ? $to->getTimestamp() : null])
            ->all();
        $usingWeb = UserAuth::find()
            ->where(['device'=>'desktop'])
            ->andFilterWhere(['>=', 'lastact', $from ? $from->getTimestamp() : null])
            ->andFilterWhere(['<=', 'lastact', $to ? $to->getTimestamp() : null])
            ->all();

        $cardTypes = CardType::find()


            ->all();



        $partners = Partner::find()
            ->andFilterWhere(['>=', 'created_at', $from ? $from->getTimestamp() : null])
            ->andFilterWhere(['<=', 'created_at', $to ? $to->getTimestamp() : null])
            ->all();
        $salesByCategory = Sales::find()
            ->andFilterWhere(['>=', 'created_at', $from ? $from->getTimestamp() : null])
            ->andFilterWhere(['<=', 'created_at', $to ? $to->getTimestamp() : null])
            ->all();
        $certs = Cert::find()
            ->andFilterWhere(['>=', 'created_at', $from ? $from->getTimestamp() : null])
            ->andFilterWhere(['<=', 'created_at', $to ? $to->getTimestamp() : null])
            ->all();
        $activatedCerts = Cert::find()
            ->andWhere(['not', ['used' => null]])
            ->andFilterWhere(['>=', 'created_at', $from ? $from->getTimestamp() : null])
            ->andFilterWhere(['<=', 'created_at', $to ? $to->getTimestamp() : null])
            ->all();

        $cats = [];
        foreach ($salesByCategory as $sale){
            if(!empty($sale->partner->category)){
                foreach ($sale->partner->category as $category){
                    if($category->parent){
                        $cats[$category->parent->name.'/'.$category->name][] = 1;
                    }else{
                        $cats[$category->name][] = 1;
                    }
                }
            }


        }
        return $this->render('stat',[
            'users'=>$users,
            'usingAndroid'=>$usingAndroid,
            'usingIOS'=>$usingIOS,
            'usingWeb'=>$usingWeb,
            'cardTypes'=>$cardTypes,
            'partners'=>$partners,
            'certs'=>$certs,
            'salesByCategory'=>$cats,
            'activatedCerts' => $activatedCerts,
            'from'=>$from,
            'to' => $to
        ]);
    }

    public function actionSaleHistory(){
            $searchModel = new SaleSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('sale_history', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
    }

    public function actionTest(){
        $note = Yii::$app->fcm->createNotification("test title", "testing body");


        $message = Yii::$app->fcm->createMessage();
        $message->addRecipient(new Device('86704303176493'));
        $message->setNotification($note)
            ->setData(['someId' => 111]);

        $response = Yii::$app->fcm->send($message);
        d($response->getStatusCode());
    }

    public function actionUserList($q = null, $id = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new Query;
            $query->select('id, email, name AS text')
                ->from('users')
                ->where(['like', 'name', $q])
                ->orWhere(['like','email',$q])
                ->limit(20);
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }
        elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => User::findOne($id)->name];
        }
        return $out;
    }



    public function actionPartnerList($q = null, $id = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new Query;
            $query->select('id, name AS text')
                ->from('partners')
                ->where(['like', 'name', $q])
                ->limit(20);
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }
        elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => Partner::findOne($id)->name];
        }
        return $out;
    }

    public function actionViewedEmail($id)
    {
        $rec = RecipientList::find()->where(['id'=>$id])->one();
        $rec->status = RecipientList::WATCHED;
        $rec->update(false);
        return '';
    }
}
