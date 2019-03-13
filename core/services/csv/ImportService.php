<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 28.11.2018
 * Time: 17:48
 */

namespace core\services\csv;


use core\entities\card\Card;
use core\entities\card\CardAndPartner;
use League\Csv\Reader;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

class ImportService
{
    public function run(UploadedFile $file, $type):array
    {
        $csv = Reader::createFromPath($file->tempName)->setDelimiter(';')->setHeaderOffset(0);
        switch ($type){
            case Card::class:
                return $this->cards($csv);
                break;
            case CardAndPartner::class:
                return $this->cardsAndPartners($csv);
                break;
        }
    }

    private function cards($data):array
    {
        $allRows = count($data);
        $addedRows = 0;
        $nonAddedRows = 0;
        foreach ($data as $index=>$row) {
            $type_id = ArrayHelper::getValue($row, 'type_id');
            $number = ArrayHelper::getValue($row, 'number');
            if(is_null($type_id)){
                throw new Exception("Столбик type_id, отсутсвует");
            }
            if(is_null($number)){
                throw new Exception("Столбик number, отсутсвует");
            }
            if(is_null($type_id) || empty($type_id)){
                throw new Exception("Строка {$index} столбик type_id пуст");
            }
            if(is_null($number) || empty($number)){
                throw new Exception("Строка {$index} столбик number пуст");
            }

            if(!Card::find()->where(['number'=>$number])->one()){
                $card = Card::createFromCsv(
                    ArrayHelper::getValue($row, 'type_id'),
                    ArrayHelper::getValue($row, 'number')
                );
                $card->save();
                $addedRows++;
            }else{
                $nonAddedRows++;
            }


        }

        return [
            'all' => $allRows,
            'added' => $addedRows,
            'non' => $nonAddedRows
        ];
    }

    private function cardsAndPartners($data):array
    {
        $allRows = count($data);
        $addedRows = 0;
        $nonAddedRows = 0;
        foreach ($data as $index=>$row) {
            $type_id = ArrayHelper::getValue($row, 'card_type_id');
            $discount = ArrayHelper::getValue($row, 'discount');
            $description = ArrayHelper::getValue($row, 'description');
            $partner_id = ArrayHelper::getValue($row, 'partner_id');
            $status = ArrayHelper::getValue($row, 'status');
            if(is_null($type_id)){
                throw new Exception("Столбик type_id, отсутсвует");
            }
            if(is_null($number)){
                throw new Exception("Столбик number, отсутсвует");
            }
            if(is_null($type_id) || empty($type_id)){
                throw new Exception("Строка {$index} столбик type_id пуст");
            }
            if(is_null($number) || empty($number)){
                throw new Exception("Строка {$index} столбик number пуст");
            }

            if(!Card::find()->where(['number'=>$number])->one()){
                $card = Card::createFromCsv(
                    ArrayHelper::getValue($row, 'type_id'),
                    ArrayHelper::getValue($row, 'number')
                );
                $card->save();
                $addedRows++;
            }else{
                $nonAddedRows++;
            }


        }

        return [
            'all' => $allRows,
            'added' => $addedRows,
            'non' => $nonAddedRows
        ];
    }
}