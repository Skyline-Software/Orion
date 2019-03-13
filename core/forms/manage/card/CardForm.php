<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 27.11.2018
 * Time: 16:20
 */

namespace core\forms\manage\card;


use core\entities\card\Card;
use core\entities\card\CardType;
use yii\base\Model;

class CardForm extends Model
{
    public $type_id;
    public $number;
    public $code;
    public $user_id;
    public $status;

    public function __construct(Card $card = null, array $config = [])
    {
        if($card){
            $this->type_id = $card->type_id;
            $this->number = $card->number;
            $this->code = $card->code;
            $this->status = $card->status;
            $this->user_id = $card->user_id;
        }
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['type_id','user_id','status'],'integer'],
            [['number','code'],'string'],
            [['type_id'],'required'],
            [['type_id'],'exist','targetClass' => CardType::class,'targetAttribute' => 'id']
        ];
    }

    public function attributeLabels()
    {
        return [
            'type_id' => 'Тип',
            'type' => 'Тип',
            'number' => 'Номер',
            'code' => 'Код',
            'client' => 'Владелец',
            'user_id' => 'Владелец',
            'activated' => 'Когда активирована',
            'status' => 'Статус'
        ];
    }
}