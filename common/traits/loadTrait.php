<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 10.12.2018
 * Time: 20:55
 */

namespace common\traits;


use core\entities\user\User;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

trait loadTrait
{
    use ImageTrait;

    public function loadUser()
    {

        $user = User::findIdentity(\Yii::$app->user->id);
        return $user;
    }

    public function loadUserBy(array $params)
    {
        return User::findOne($params);
    }

    public function loadParam($paramName)
    {
        $param = ArrayHelper::getValue($_POST, $paramName);

        if (is_null($param)) {
            return ['status' => 'fail', 'code' => strtoupper($paramName) . '_NOT_SET'];
        }

        return $param;
    }

    public function paramGuad($param)
    {
        if (is_array($param)) {
            return $param;
        }
    }

    public function loadPhoto($w = 100, $h = 100)
    {
        if (ArrayHelper::getValue($_FILES, 'photo')) {
            $upload = UploadedFile::getInstanceByName('photo');
            $name = \Yii::$app->security->generateRandomString(18);
            $hash = md5($name);
            $dirs = str_split($hash, 2);
            $structure = '../../storage/web/source/' . implode('/', [$dirs[0], $dirs[1], $dirs[2]]);
            mkdir($structure, 0777, true);
            $path = implode('/', [$dirs[0], $dirs[1], $dirs[2], substr($hash, 6) . '.' . $upload->extension]);
            if($upload->saveAs('../../storage/web/source/' . $path)){
                switch ($upload->type){
                    case 'image/png':
                        $this->resize_image_png('../../storage/web/source/' . $path, 100, 100);
                        break;
                    case 'image/svg+xml':
                        break;
                    default:
                        $this->resize_image_jpeg('../../storage/web/source/' . $path, 100, 100);
                }
                $_POST['photo'] = $path;
            }

        }
    }



}