<?php
/**
 * Created by PhpStorm.
 * user: Mopkau
 * Date: 27.08.2018
 * Time: 14:30
 */

namespace core\forms\manage\user\customer\billing;


use core\entities\user\customer\Profile;
use core\entities\user\User;
use yii\base\Model;

/**
 * Class RefillForm
 * @package core\forms\manage\user\customer\billing
 * @property Profile $customer
 * @property int $amount
 */
class RefillForm extends Model
{
    public $amount;
    public $user;

    public function __construct(User $ar, array $config = [])
    {
        $this->user = $ar;
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['amount'],'integer']
        ];
    }
}