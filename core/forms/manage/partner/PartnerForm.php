<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 27.11.2018
 * Time: 19:59
 */

namespace core\forms\manage\partner;


use core\entities\card\CardType;
use core\entities\Image;
use core\entities\partner\Partner;
use core\entities\Rows;
use core\forms\ImageForm;
use core\forms\MultipleInputForm;
use elisdn\compositeForm\CompositeForm;

/**
 * Class PartnerForm
 * @package core\forms\manage\partner
 * @property ImageForm $logo;
 * @property ImageForm $header_photo;
 * @property ImageForm $photos;
 * @property MultipleInputForm $adresses;
 * @property MultipleInputForm $work_time;
 */
class PartnerForm extends CompositeForm
{
    public $name;
    public $category_id;
    public $description;
    public $website;
    public $instagram;
    public $can_buy_ur;
    public $can_accept_cert;
    public $sales;
    public $status;
    public $avg_invoice;

    private $types = [];

    public function __construct(Partner $partner = null, array $config = [])
    {
        if($partner){
            $this->setAttributes($partner->getAttributes());
            $this->logo = new ImageForm('LogoForm', new Image($partner->logo));
            $this->header_photo = new ImageForm('HeaderPhotoForm', new Image($partner->header_photo));
            $this->adresses = new MultipleInputForm('AdressesForm', new Rows($partner->adresses));
            $this->work_time = new MultipleInputForm('WorkTimeForm',new Rows($partner->work_time['config']));
            $this->photos = new ImageForm('PhotosForm', $partner->photos);
        }else{
            $this->logo = new ImageForm('LogoForm');
            $this->header_photo = new ImageForm('HeaderPhotoForm');
            $this->adresses = new MultipleInputForm('AdressesForm');
            $this->work_time = new MultipleInputForm('WorkTimeForm');
            $this->photos = new ImageForm('PhotosForm');
        }

        $this->setTypes($partner);
        parent::__construct($config);
    }

    private function setTypes(Partner $partner = null){
        if($partner){
            $this->fillTypesFromAr($partner);
            $this->sales = new MultipleInputForm('SalesForm',new Rows($this->types));
        }else{
            $this->fillTypesDefault();
            $this->sales = new MultipleInputForm('SalesForm', new Rows($this->types));
        }
    }

    public function rules()
    {
        return [
            [['name','instagram','website','description'],'string'],
            [['can_buy_ur','can_accept_cert','avg_invoice'],'integer'],
            [['status'],'safe'],
            [['category_id'],'each','rule'=>['integer']]
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Название',
            'instagram' => 'Ссылка на инстаграм',
            'website' => 'Ссылка на сайт',
            'description' => 'Описание',
            'can_buy_ur' => 'Возможность купить карту UR',
            'can_accept_cert' => 'Возможность расчета сертификатом',
            'category_id' => 'Категория',
            'status' => 'Статус',
            'avg_invoice' => 'Средний чек',
        ];
    }

    public function internalForms()
    {
        return ['logo','header_photo','photos','adresses','work_time','sales'];
    }


    /**
     * @param Partner $partner
     */
    private function fillTypesFromAr(Partner $partner): void
    {
        $current = [];
        $old = [];
        foreach ($partner->sales as $type) {
            $current[$type->card_type_id] =[
                'card_type_id' => $type->card_type_id,
                'discount' => $type->discount,
                'status' => $type->status,
                'hot' => $type->hot,
                'description' => $type->description
            ];
        }
        $cardTypes = CardType::find()->all();
        foreach ($cardTypes as $type) {
            $old[$type->id] = [
                'card_type_id' => $type->id,
                'discount' => 0,
                'status' => 0
            ];
        }
        $mustBeAdded = array_diff_key($old,$current);
        $this->types = array_merge($current,$mustBeAdded);

    }

    private function fillTypesDefault(): void
    {
        $cardTypes = CardType::find()->all();
        foreach ($cardTypes as $type) {
            array_push($this->types, [
                'card_type_id' => $type->id,
                'discount' => 0,
                'status' => 0,
                'hot' => 0,
                'description' => '',
            ]);
        }
    }
}