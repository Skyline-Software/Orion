<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 27.11.2018
 * Time: 19:48
 */
namespace core\entities\partner;
use core\entities\behaviours\AttachmentsBehaviour;
use core\entities\card\CardAndPartner;
use core\entities\card\CardType;
use core\entities\favorites\UserFavorites;
use core\entities\Image;
use core\entities\Images;
use core\entities\Rows;
use core\entities\sales\Sales;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class Partner
 * @package core\entities\partner
 * @property string $name;
 * @property integer $id;
 * @property integer $avg_invoice;
 * @property integer $category_id;
 * @property Image $logo;
 * @property Image $header_photo;
 * @property string $description
 * @property array $work_time;
 * @property array $adresses;
 * @property string $website
 * @property string $instagram
 * @property Image $photos
 * @property integer $can_buy_ur
 * @property integer $can_accept_cert
 * @property string $token
 * @property string $created_at
 * @property int $status
 * @property PartnerCategory[] $category
 *
 */
class Partner extends ActiveRecord
{

    CONST STATUS_ACTIVE = 1;
    CONST STATUS_HIDE = 2;

    /**
     * @return string
     */
    public static function tableName()
    {
        return 'partners';
    }

    /**
     * @param $name
     * @param $category_id
     * @param $logo
     * @param $header_photo
     * @param Images $photos
     * @param $description
     * @param $adresses
     * @param $work_time
     * @param $website
     * @param $instagram
     * @param $can_buy_ur
     * @param $can_accept_cert
     * @param $status
     * @return Partner
     * @throws \yii\base\Exception
     */
    public static function create(
        $name,
        $category_id,
        $logo,
        $header_photo,
        Images $photos,
        $description,
        $adresses,
        $work_time,
        $website,
        $instagram,
        $can_buy_ur,
        $can_accept_cert,
        $status): self
    {
        $partner = new static();
        $partner->name = $name;
        $partner->category_id = $category_id;
        $partner->logo = $logo;
        $partner->header_photo = $header_photo;
        $partner->photos = $photos;
        $partner->description = $description;
        $partner->adresses = $adresses;
        $partner->work_time = $work_time;
        $partner->website = $website;
        $partner->instagram = $instagram;
        $partner->can_buy_ur = $can_buy_ur;
        $partner->can_accept_cert = $can_accept_cert;
        $partner->token = \Yii::$app->security->generateRandomString();
        $partner->status = $status;

        return $partner;
    }

    /**
     * @param $name
     * @param $category_id
     * @param $logo
     * @param $header_photo
     * @param Images $photos
     * @param $description
     * @param $adresses
     * @param $work_time
     * @param $website
     * @param $instagram
     * @param $can_buy_ur
     * @param $can_accept_cert
     * @param $status
     * @throws \yii\base\Exception
     */
    public function edit(
        $name,
        $category_id,
        $logo,
        $header_photo,
        Images $photos,
        $description,
        $adresses,
        $work_time,
        $website,
        $instagram,
        $can_buy_ur,
        $can_accept_cert,
        $status): void
    {
        $this->name = $name;
        $this->category_id = $category_id;
        $this->logo = $logo;
        $this->header_photo = $header_photo;
        $this->photos = $photos;
        $this->description = $description;
        $this->adresses = $adresses;
        $this->work_time = $work_time;
        $this->website = $website;
        $this->instagram = $instagram;
        $this->can_buy_ur = $can_buy_ur;
        $this->can_accept_cert = $can_accept_cert;
        $this->token = \Yii::$app->security->generateRandomString();
        $this->created_at = time();
        $this->status = $status;
    }

    /**
     * @param $avg_invoice
     */
    public function addAvgInvoice($avg_invoice):void
    {
        $this->avg_invoice = $avg_invoice;
    }

    /**
     * @return array
     */
    public function behaviors():array
    {
        return [
            [
                'class' => AttachmentsBehaviour::class,
                'fields' => ['photos'=>'photos']
            ],
            [
                'class' => SaveRelationsBehavior::class,
                'relations' => [
                    'sales','adresses'
                ]
            ]
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels():array
    {
        return [
            'name' => 'Название',
            'instagram' => 'Ссылка на инстаграм',
            'website' => 'Ссылка на сайт',
            'description' => 'Описание',
            'can_buy_ur' => 'Возможность купить карту UR',
            'can_accept_cert' => 'Возможность расчета сертификатом',
            'category_id' => 'Категория',
            'token' => 'Токен',
            'avg_invoice' => 'Средний чек',
        ];
    }

    # Relations

    /**
     * @return ActiveQuery
     */
    public function getAdresses():ActiveQuery
    {
        return $this->hasMany(PartnerAdres::class,['partner_id'=>'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getSales():ActiveQuery
    {
        return $this->hasMany(CardAndPartner::class,['partner_id'=>'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getHistory():ActiveQuery
    {
        return $this->hasMany(Sales::class,['partner_id'=>'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getCardTypes():ActiveQuery
    {
        return $this->hasMany(CardType::class,['id'=>'card_type_id'])->viaTable('stocks',['partner_id'=>'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getStocks():ActiveQuery
    {
        return $this->hasMany(CardAndPartner::class,['partner_id'=>'id']);
    }

    # Get things

    /**
     * @return bool
     */
    public function getFavorite():bool
    {
        if($favorite = UserFavorites::findOne(['user_id'=>\Yii::$app->user->id,'partner_id'=>$this->id])){
            return 1;
        }
        return 0;
    }

    /**
     * @param Rows $sales
     */
    public function addSales(Rows $sales):void
    {
        $this->sales = $sales->config;
    }


    /**
     * @return array
     */
    public function getCategory():array
    {
        if(!empty($this->category_id)){
            return PartnerCategory::find()->andFilterWhere(['in','id',$this->category_id])->all();
        }
        return [];
    }

    /**
     * @return array
     */
    public function fields():array
    {
        return [
            'id',
            'category_id',
            'can_buy_ur',
            'can_accept_cert',
            'name',
            'description',
            'website',
            'instagram',
            'token',
            'logo',
            'header_photo',
            'adresses',
            'work_time',
            'photos',
            'avg_invoice',
            'category',
            'favorite'=>function($model){
                return $this->getFavorite();
            },
            'stocks'=>function($model){
            /* @var $model Partner */
                return $model->getSales()->andFilterWhere(['status'=>1])->all();
            }
        ];
    }
}