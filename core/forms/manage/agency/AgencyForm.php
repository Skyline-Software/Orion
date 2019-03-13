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

    public function __construct(Agency $agency = null, array $config = [])
    {
        if($agency){
            $this->name = $agency->name;
            $this->web_site = $agency->web_site;
            $this->status = $agency->status;
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
            [['status'],'integer']
        ];
    }

    public function attributeLabels()
    {
        return [
            'name'=>'Название',
            'logo'=>'Логотип',
            'web_site'=>'Веб-сайт',
            'status'=>'Статус',
        ];
    }

    public function internalForms()
    {
        return ['logo'];
    }
}