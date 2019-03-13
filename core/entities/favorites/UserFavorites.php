<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 06.12.2018
 * Time: 17:00
 */

namespace core\entities\favorites;


use core\entities\partner\Partner;
use core\entities\user\User;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

class UserFavorites extends ActiveRecord
{
    public static function tableName()
    {
        return 'user_favorites_partner';
    }

    public static function add($user_id, $partner_id):self
    {
        $fav = new static();
        $fav->user_id = $user_id;
        $fav->partner_id = $partner_id;

        return $fav;
    }

    public static function remove($user_id,$partner_id)
    {
        return self::find()->where(['user_id'=>$user_id,'partner_id'=>$partner_id])->one()->delete();
    }

    public function getUser():ActiveQuery
    {
        return $this->hasOne(User::class,['id'=>'user_id']);
    }

    public function getPartner():ActiveQuery
    {
        return $this->hasOne(Partner::class,['id'=>'partner_id']);
    }

    public function fields()
    {
        return ['partner_id'];
    }
}