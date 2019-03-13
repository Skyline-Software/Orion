<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 07.03.2019
 * Time: 20:13
 */

namespace core\useCase\manage;


use core\entities\Images;
use core\entities\partner\Partner;
use core\entities\Rows;
use core\forms\manage\partner\PartnerForm;
use yii\helpers\ArrayHelper;

/**
 * Class PartnerManageService
 * @package core\useCase\manage
 */
class PartnerManageService
{
    /**
     * @param PartnerForm $form
     * @return Partner
     */
    public function create(PartnerForm $form): Partner
    {
        $partner = Partner::create(
            $form->name,
            $form->category_id,
            ArrayHelper::getValue($form->logo->config,'path'),
            ArrayHelper::getValue($form->header_photo->config,'path'),
            new Images($form->photos->config),
            $form->description,
            $this->prepareAddress($form),
            new Rows($form->work_time->config),
            $form->website,
            $form->instagram,
            $form->can_buy_ur,
            $form->can_accept_cert,
            $form->status
        );
        $partner->addSales(new Rows(ArrayHelper::getValue($_POST,'SalesForm.config')));
        $partner->addAvgInvoice($form->avg_invoice);

        /* todo: Вынести в репозиторий */
        $partner->save();
        return $partner;
    }

    /**
     * @param Partner $model
     * @param PartnerForm $form
     * @return Partner
     */
    public function edit(Partner $model, PartnerForm $form):Partner
    {
        $model->edit(
            $form->name,
            $form->category_id,
            ArrayHelper::getValue($form->logo->config,'path'),
            ArrayHelper::getValue($form->header_photo->config,'path'),
            new Images($form->photos->config),
            $form->description,
            $this->prepareAddress($form),
            new Rows($form->work_time->config),
            $form->website,
            $form->instagram,
            $form->can_buy_ur,
            $form->can_accept_cert,
            $form->status
        );
        $model->addSales(new Rows(ArrayHelper::getValue($_POST,'SalesForm.config')));
        $model->addAvgInvoice($form->avg_invoice);
        $model->save();

        return $model;
    }

    /**
     * @param PartnerForm $form
     * @return array
     */
    private function prepareAddress(PartnerForm $form): array
    {
        $preparedAdresses = array_map(function ($point) {
            $coords = explode('@', $point['coords']);
            return ['address' => ArrayHelper::getValue($point, 'address'), 'phone' => ArrayHelper::getValue($point, 'phone'), 'lat' => ArrayHelper::getValue($coords, 0), 'long' => ArrayHelper::getValue($coords, 1)];
        }, $form->adresses->config);
        return $preparedAdresses;
    }
}