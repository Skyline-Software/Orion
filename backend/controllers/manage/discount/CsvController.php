<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 28.11.2018
 * Time: 17:58
 */

namespace backend\controllers\manage\card;


use core\entities\card\Card;
use core\entities\card\CardAndPartner;
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
        $cards = CardAndPartner::find()->select(['card_type_id','discount','description','partner_id','status'])->asArray()->all();
        return $this->exportService->run($cards);
    }

    public function actionImport()
    {
        $form = new ImportForm();
        if($form->load(\Yii::$app->request->post())){
            try {
                $form->file = UploadedFile::getInstance($form, 'file');
                $result = $this->importService->run($form->file, CardAndPartner::class);
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