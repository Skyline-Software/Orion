<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 06.12.2018
 * Time: 15:07
 */

namespace core\entities\buy;


use core\entities\card\Card;
use core\entities\card\UserCard;
use core\entities\cert\Cert;
use core\entities\cert\UserCert;
use yii\db\ActiveRecord;

class Buy extends ActiveRecord
{
    public static function tableName()
    {
        return 'buy_history';
    }

    public static function cert(Cert $cert, UserCert $userCert, $user_id):self
    {
        $bCert = new static();
        $bCert->class = UserCert::class;
        $bCert->user_id = $user_id;
        $bCert->class_id = $userCert->id;
        $bCert->created_at = time();

        return $bCert;

    }

    public static function card(Card $card, $user_id):self
    {
        $bCard = new static();
        $bCard->class = UserCard::class;
        $bCard->user_id = $user_id;
        $bCard->card_number = $card->number;
        $bCard->created_at = time();

        return $bCard;

    }
}