<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 22.08.2018
 * Time: 19:49
 */

namespace core\forms;


use core\entities\Rows;
use yii\base\Model;

/**
 * Class BlocksForm
 * @package company\forms\consulting\internal
 * @property array $rows
 */
class MultipleInputForm extends Model
{
    public $config;
    public $lang;
    public $formName;

    private $_ar;

    public function formName()
    {
        return $this->formName;
    }

    public function __construct($formName = 'Rows', Rows $rows = null, array $config = [])
    {
        $this->formName = $formName;
        if($rows){
            $this->config = $rows->config;
        }
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['config'],'safe']
        ];
    }

    public function getId():int
    {
        return $this->_ar->id;
    }
}