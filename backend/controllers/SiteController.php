<?php
namespace backend\controllers;

use backend\forms\SaleSearch;
use core\entities\agency\Order;
use core\entities\marketing\RecipientList;
use core\entities\partner\Partner;
use core\entities\user\User;
use core\helpers\AgencyHelper;
use paragraph1\phpFCM\Recipient\Device;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

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
        $agency_id = ArrayHelper::getValue($_GET,'agency_id',0);
        $from = ArrayHelper::getValue($_GET,'from',(new \DateTime())->modify('-1 month')->format('d.m.y'));
        if(!is_null($from)){
            $from = date_create_from_format('d.m.y',$from);
            $from->setTime(0,0,0);
        }

        $to = ArrayHelper::getValue($_GET,'to',(new \DateTime())->format('d.m.y'));
        if(!is_null($to)){
            $to = date_create_from_format('d.m.y',$to);
            $to->setTime(23,59,59);
        }
        $period = new \DatePeriod(
            $from,
            new \DateInterval('P1D'),
            $to,
            0
        );
        $dates = [];
        $ordersByDates = [];
        $summByDates = [];
        foreach ($period as $key => $value) {
           array_push($dates,$value->format('d.m'));
        }
        foreach ($period as $key => $value) {
            $orders = Order::find()
                ->andFilterWhere(['in','agency_id',AgencyHelper::getAllowedAgenciesIds()])
                ->andFilterWhere(['status'=>Order::STATUS_PAYED])
                ->andFilterWhere(['>=', 'created_at', $value->getTimestamp()])
                ->andFilterWhere(['<=', 'created_at', $value->setTime(23,59,59)->getTimestamp()])
                ->all();
            array_push($ordersByDates,count($orders));
            array_push($summByDates,$summ = array_sum(array_map(function ($item){
                return $item->price;
            },$orders)));
        }


        if($agency_id === 0){
            $agents = User::find()
                ->joinWith('agencyAssn aAssn')
                ->where(['aAssn.role'=>User::ROLE_AGENT])
                ->andFilterWhere(['in','aAssn.agency_id',AgencyHelper::getAllowedAgenciesIds()])
                ->andFilterWhere(['>=', 'users.created_at', $from ? $from->getTimestamp() : null])
                ->andFilterWhere(['<=', 'users.created_at', $to ? $to->getTimestamp() : null])
                ->all();
            $clients = User::find()
                ->joinWith('agencyAssn aAssn')
                ->where(['aAssn.role'=>User::ROLE_CUSTOMER])
                ->andFilterWhere(['in','aAssn.agency_id',AgencyHelper::getAllowedAgenciesIds()])
                ->andFilterWhere(['>=', 'users.created_at', $from ? $from->getTimestamp() : null])
                ->andFilterWhere(['<=', 'users.created_at', $to ? $to->getTimestamp() : null])
                ->all();
            $orders = Order::find()
                ->andFilterWhere(['in','agency_id',AgencyHelper::getAllowedAgenciesIds()])
                ->andFilterWhere(['status'=>Order::STATUS_PAYED])
                ->all();
        }else{
            $agents = User::find()
                ->joinWith('agencyAssn aAssn')
                ->where(['aAssn.role'=>User::ROLE_AGENT])
                ->andFilterWhere(['>=', 'users.created_at', $from ? $from->getTimestamp() : null])
                ->andFilterWhere(['<=', 'users.created_at', $to ? $to->getTimestamp() : null])
                ->all();
            $clients = User::find()
                ->joinWith('agencyAssn aAssn')
                ->where(['aAssn.agency_id'=>$agency_id,'aAssn.role'=>User::ROLE_CUSTOMER])
                ->andFilterWhere(['>=', 'users.created_at', $from ? $from->getTimestamp() : null])
                ->andFilterWhere(['<=', 'users.created_at', $to ? $to->getTimestamp() : null])
                ->all();
            $orders = Order::find()
                ->andFilterWhere(['agency_id'=>$agency_id])
                ->andFilterWhere(['status'=>Order::STATUS_PAYED])
                ->all();
        }

        $summ = array_sum(array_map(function ($item){
            return $item->price;
        },$orders));


        return $this->render('stat',[
            'agents'=>$agents,
            'clients'=>$clients,
            'orders'=>$orders,
            'summ'=>$summ,
            'from'=>$from,
            'to' => $to,
            'dates'=>$dates,
            'ordersByDates'=>$ordersByDates,
            'summByDates'=>$summByDates
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
