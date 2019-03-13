<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 28.11.2018
 * Time: 17:48
 */

namespace core\services\csv;


use League\Csv\Writer;
use SplTempFileObject;

class ExportService
{
    public function run($data, $fileName = 'export')
    {
        $csv = Writer::createFromFileObject(new SplTempFileObject());
        $csv->setDelimiter(';');
        $csv->insertOne(array_keys($data[0]));
        foreach ($data as $row){
            $csv->insertOne(array_values($row));
        }
        $csv->output($fileName.'.csv');
    }
}