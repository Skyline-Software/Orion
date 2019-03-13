<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 27.11.2018
 * Time: 14:34
 */

namespace core\entities\card;


use core\entities\behaviours\ImageBehaviour;
use core\entities\Image;
use core\entities\partner\Partner;
use core\entities\Rows;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class CardType
 * @package core\entities\card
 * @property string $name;
 * @property string $description;
 * @property Image $photo;
 * @property string $support_phone;
 * @property integer $price;
 * @property string $validity;
 * @property CardAndPartner[] $sales
 */
class CardType extends ActiveRecord
{
    public static function create($name,$description, $photo, $support_phone, $price, $validity): self
    {
        $type = new static();
        $type->name = $name;
        $type->description = $description;
        $type->photo = $photo;
        $type->support_phone = $support_phone;
        $type->price = $price;
        $type->validity = $validity;

        return $type;
    }

    public function edit($name,$description, $photo, $support_phone, $price, $validity): void
    {
        $this->name = $name;
        $this->description = $description;
        $this->photo = $photo;
        $this->support_phone = $support_phone;
        $this->price = $price;
        $this->validity = $validity;
    }

    public function addPartners():void
    {
        $partners = $this->getSales()->select('partner_id')->asArray()->all();
        $newPartnersFind = Partner::find()->andFilterWhere(['not in','id',$partners]);

        if($newPartnersFind->count() > 0){
            foreach ($newPartnersFind->all() as $newPartner){
                $cardAndPartner = CardAndPartner::create(
                    $this->id, 0,'', $newPartner->id,0
                );
                $cardAndPartner->save();
            }
        }
    }

    public function getCards():ActiveQuery
    {
        return $this->hasMany(Card::class,['type_id'=>'id']);
    }

    public function getSales():ActiveQuery
    {
        return $this->hasMany(CardAndPartner::class,['card_type_id'=>'id']);
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'description' => 'Описание',
            'photo' => 'Фото',
            'support_phone' => 'Телефон поддержки',
            'price' => 'Стоимость/руб.',
            'validity' => 'Срок действия/дней',

        ];
    }

    public function fields()
    {
        return ['id','name','description','photo','support_phone','price','validity'];
    }

}