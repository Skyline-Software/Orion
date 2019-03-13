<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 03.12.2018
 * Time: 12:57
 */

namespace core\forms\manage\cert;


use core\entities\cert\Cert;
use core\entities\Rows;
use core\forms\MultipleInputForm;
use elisdn\compositeForm\CompositeForm;

/**
 * Class CertForm
 * @package core\forms\manage\cert
 * @property MultipleInputForm $certs
 */
class CertForm extends CompositeForm
{
    public function __construct(array $config = [])
    {
        $this->certs = new MultipleInputForm('CertsForm', new Rows(Cert::find()->all()));
        parent::__construct($config);
    }

    public function internalForms()
    {
        return ['certs'];
    }
}