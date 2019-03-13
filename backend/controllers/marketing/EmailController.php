<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 10.12.2018
 * Time: 13:16
 */

namespace backend\controllers\marketing;


use backend\forms\marketing\EmailConfigSearch;
use core\entities\marketing\EmailConfig;
use core\entities\marketing\RecipientList;
use core\forms\marketing\EmailConfigForm;
use core\repositories\NotFoundExeption;
use Yii;
use yii\web\Controller;

class EmailController extends Controller
{
    public function actionIndex()
    {
        $searchModel = new EmailConfigSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate(){
        $form = new EmailConfigForm();
        if($form->load(\Yii::$app->request->post()) && $form->validate()){
            try{
                $card = EmailConfig::create(
                    $form->title,
                    $form->subject,
                    $form->message,
                    $form->card_types,
                    $form->age,
                    $form->sex,
                    $form->partner_categories,
                    $form->partners,
                    $form->is_for_already_has_sale
                );
                $card->type = $form->type;
                $card->save();
                $card->setupRecipients();
                \Yii::$app->session->setFlash('success','Шаблон успешно создан');
                return $this->redirect(['view','id'=>$card->id]);
            }catch (\RuntimeException | NotFoundExeption $e){
                \Yii::$app->session->setFlash('error',$e->getMessage());
                \Yii::$app->errorHandler->logException($e);
                return $this->redirect('index');
            }
        }

        return $this->render('create',['model'=>$form]);
    }

    public function actionEdit($id){

        try{
            $model = $this->load($id);
        }catch (NotFoundExeption $e){
            \Yii::$app->session->setFlash('error',$e->getMessage());
            \Yii::$app->errorHandler->logException($e);
            return $this->redirect('index');
        }

        $form = new EmailConfigForm($model);
        if($form->load(\Yii::$app->request->post()) && $form->validate()){
            try{
                $model->edit(
                    $form->title,
                    $form->subject,
                    $form->message,
                    $form->card_types,
                    $form->age,
                    $form->sex,
                    $form->partner_categories,
                    $form->partners,
                    $form->is_for_already_has_sale
                );
                $model->type = $form->type;
                $model->save();
                $model->setupRecipients();


                \Yii::$app->session->setFlash('success','Шаблон успешно отредактирован');
                return $this->redirect(['view','id'=>$model->id]);
            }catch (\RuntimeException | NotFoundExeption $e){
                \Yii::$app->session->setFlash('error',$e->getMessage());
                \Yii::$app->errorHandler->logException($e);
                return $this->redirect('index');
            }
        }

        return $this->render('edit',['model'=>$form]);

    }

    public function actionDelete($id){
        try{
            $model = $this->load($id);
            $model->delete();
            \Yii::$app->session->setFlash('success','Шаблон успешно удален');
        }catch (\RuntimeException | NotFoundExeption $e){
            \Yii::$app->session->setFlash('error',$e->getMessage());
            \Yii::$app->errorHandler->logException($e);
        }

        return $this->redirect('index');
    }

    public function actionView($id){
        try{
            $cardType = $this->load($id);
        }catch (NotFoundExeption $e){
            \Yii::$app->session->setFlash('error',$e->getMessage());
            \Yii::$app->errorHandler->logException($e);
            return $this->redirect('index');
        }

        return $this->render('view',['model'=>$cardType]);
    }

    public function actionSend($id)
    {
        $template = $this->load($id);
        $recipients = RecipientList::find()
            ->where(['{{%recipient_list}}.config_id'=>$template->id,'{{%recipient_list}}.status'=>RecipientList::NOT_SEND])
            ->joinWith('user');
        foreach ($recipients->batch(50) as $recipientB){
            foreach ($recipientB as $recipient){
                $message = str_replace('{username}',$recipient->user->name,$template->message);
                $html = '<html><body>';
                $html .= $message;
                $html .= '<img style="display:none;" src="https://api.ulitsarubinshteina.ru/viewed?id='.$recipient->id.'">';
                $html .= '</body></html>';
                $headers = "From: " . Yii::$app->params['fromName'].'<'.Yii::$app->params['noReplyEmail'].'>' . "\r\n";
                $headers .= "MIME-Version: 1.0\r\n";
                $headers .= "Content-Type: text/html; charset=utf-8\r\n";
                $send = mail($recipient->user->email, $template->subject, $html, $headers);

                /*$email = Yii::$app->mailer->compose()
                    ->setTo($recipient->user->email)
                    ->setSubject($template->title)
                    ->setHtmlBody($message);*/
                if($send){
                    $recipient->status = RecipientList::SEND;
                    $recipient->update(false);
                }else{
                    $recipient->status = RecipientList::ERROR;
                    $recipient->update(false);
                }
            }


        }
        if($recipients->count() == 0){
            return $this->redirect(['/marketing/email/view','id'=>$id]);
        }else{
            return $this->redirect(['/marketing/email/send','id'=>$id]);
        }
    }



    public function load($id):? EmailConfig
    {
        if(!$model = EmailConfig::findOne(['id'=>$id])){
            throw new NotFoundExeption('Шаблон не найден');
        }

        return $model;
    }
}