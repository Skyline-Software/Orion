<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 22.08.2018
 * Time: 19:49
 */

namespace core\forms;


use core\entities\Rows;
use core\helpers\AgencyHelper;
use Yii;
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
            [['config'],'safe'],
            ['config', 'checkIsAllow'],
        ];
    }

    public function checkIsAllow($attr,$param){
        foreach ($this->config as $row){
            if(array_search($row['agency_id'],AgencyHelper::getAllowedAgenciesIds()) === false){
                $this->addError('config', Yii::t('backend',"У вас нет прав для данного действия"));
            }
        }
    }

    public function getId():int
    {
        return $this->_ar->id;
    }
}