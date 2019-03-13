<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 28.11.2018
 * Time: 17:58
 */

namespace backend\controllers\manage\card;


use core\entities\card\Card;
use core\services\csv\ExportService;
use core\services\csv\ImportForm;
use core\services\csv\ImportService;
use yii\web\Controller;
use yii\web\UploadedFile;

class CsvController extends Controller
{
    private $importService;
    private $exportService;

    public function __construct(string $id, $module, ImportService $importService, ExportService $exportService,array $config = [])
    {
        $this->importService = $importService;
        $this->exportService = $exportService;
        parent::__construct($id, $module, $config);
    }

    public function actionExport()
    {
        $cards = Card::find()->select(['type_id','number'])->asArray()->all();
        $this->exportService->run($cards);
        die();
    }

    public function actionImport()
    {
        $form = new ImportForm();
        if($form->load(\Yii::$app->request->post())){
            try {
                $form->file = UploadedFile::getInstance($form, 'file');
                $result = $this->importService->run($form->file, Card::class);
                \Yii::$app->session->setFlash('success',
                    "Данные успешно импортированы<br> Всего: {$result['all']}<br> Добавлено: {$result['added']}<br> Повторы: {$result['non']}"
                );
            } catch (\Exception $e) {
                \Yii::$app->session->setFlash('error',$e->getMessage());
                \Yii::$app->errorHandler->logException($e);
            }
        }

        return $this->render('import',['model'=>$form]);
    }
}