<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 13.03.2019
 * Time: 16:34
 */

namespace core\repositories\agency;


use core\entities\agency\Agency;
use core\repositories\NotFoundExeption;

class AgencyRepository
{
    public function get($id):Agency
    {
        if(!$user =  Agency::findOne(['id'=>$id])){
            throw new NotFoundExeption('Агенство не найдено');
        }
        return $user;
    }

    public function save(Agency $agency): void
    {
        if(!$agency->save()){
            throw new \RuntimeException('Saving error.');
        }
    }

    public function delete(Agency $agency): void
    {
        if(!$agency->delete()){
            throw new \RuntimeException('Deleting error.');
        }
    }
}