<?php
/**
 * Created by PhpStorm.
 * User: Mopkau
 * Date: 17.09.2018
 * Time: 15:30
 */

namespace core\useCase\agency;


use core\entities\agency\Agency;
use core\entities\user\User;
use core\repositories\agency\AgencyRepository;
use core\repositories\user\UserRepository;

class StatusService
{
    private $agencyRepository;

    public function __construct(AgencyRepository $agencyRepository)
    {
        $this->agencyRepository = $agencyRepository;
    }

    public function activateAgency($id):? Agency
    {
        $agency = $this->agencyRepository->get($id);
        $agency->activate();
        $this->agencyRepository->save($agency);

        return $agency;
    }

    public function deactivateAgency($id):? Agency
    {
        $agency = $this->agencyRepository->get($id);
        $agency->deactivate();
        $this->agencyRepository->save($agency);

        return $agency;
    }

}