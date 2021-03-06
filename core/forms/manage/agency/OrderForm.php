<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 13.03.2019
 * Time: 16:05
 */

namespace core\forms\manage\agency;


use core\entities\agency\Agency;
use core\entities\agency\Order;
use core\entities\Image;
use core\forms\ImageForm;
use elisdn\compositeForm\CompositeForm;
use Yii;
use yii\base\Model;

/**
 * Class AgencyForm
 * @package core\forms\manage\agency
 * @property string $name
 * @property string $web_site
 * @property integer $status
 * @property ImageForm $logo
 */
class OrderForm extends Model
{
    public $agency_id;
    public $agent_id;
    public $user_id;
    public $start_coordinates;
    public $end_coordinates;
    public $price;
    public $start_time;
    public $end_time;
    public $rating;
    public $comment;
    public $status;
    public $lat1;
    public $lon1;
    public $lat2;
    public $lon2;

    public function __construct(Order $agency = null, array $config = [])
    {
        if($agency){
            $this->setAttributes($agency->getAttributes(),false);
            $coords1 = explode(',',$agency->start_coordinates);
            $coords2 = explode(',',$agency->end_coordinates);
            $this->lat1 = $coords1[0];
            $this->lon1 = $coords1[1];
            $this->lat2 = $coords2[0];
            $this->lon2 = $coords2[1];
        }
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['agency_id','agent_id','user_id','start_coordinates','end_coordinates','start_time','end_time','price'],'safe'],
            [['status'],'integer'],
            [['rating','comment','end_time'],'safe'],
            [['lat1','lat2','lon1','lon2'],'required']
        ];
    }

    public function attributeLabels()
    {
        return [
            'agency_id'=>Yii::t('backend','Агентство'),
            'agent_id'=>Yii::t('backend','Агент'),
            'user_id'=>Yii::t('backend','Клиент'),
            'start_coordinates'=>Yii::t('backend','Исходная локация'),
            'end_coordinates'=>Yii::t('backend','конечная локация'),
            'start_time'=>Yii::t('backend','Время начала выполнения'),
            'end_time'=>Yii::t('backend','Время окончания выполнения'),
            'price'=>Yii::t('backend','Стоимость заказа'),
            'rating'=>Yii::t('backend','Оценка заказа клиентом'),
            'comment'=>Yii::t('backend','Комментарий заказчика'),
            'status'=>Yii::t('backend','Статус'),
        ];
    }
}