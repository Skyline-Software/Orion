<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 13.03.2019
 * Time: 16:05
 */

namespace core\forms\manage\agency;


use core\entities\agency\Agency;
use core\entities\Image;
use core\forms\ImageForm;
use elisdn\compositeForm\CompositeForm;
use Yii;

/**
 * Class AgencyForm
 * @package core\forms\manage\agency
 * @property string $name
 * @property string $web_site
 * @property integer $status
 * @property ImageForm $logo
 */
class AgencyForm extends CompositeForm
{
    public $name;
    public $web_site;
    public $status;
    public $agent_price;
    public $agent_metrik;

    public function __construct(Agency $agency = null, array $config = [])
    {
        if($agency){
            $this->name = $agency->name;
            $this->web_site = $agency->web_site;
            $this->status = $agency->status;
            $this->agent_price = $agency->agent_price;
            $this->agent_metrik = $agency->agent_metrik;
            $this->logo = new ImageForm('LogoForm',new Image($agency->logo));
        }else{
            $this->logo = new ImageForm('LogoForm');
        }
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['name','web_site'],'string'],
            [['status'],'integer'],
            [['agent_metrik','agent_price'],'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'name'=>Yii::t('backend','Название'),
            'logo'=>Yii::t('backend','Логотип'),
            'web_site'=>Yii::t('backend','Веб-сайт'),
            'status'=>Yii::t('backend','Статус'),
        ];
    }

    public function internalForms()
    {
        return ['logo'];
    }
}