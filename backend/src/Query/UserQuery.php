<?php

namespace App\Query;

use DateTime;
use Symfony\Component\HttpFoundation\Request;

class UserQuery
{
    /**
     * @var int|null
     */
    public $isActive;

    /**
     * @var int|null
     */
    public $isMember;

    /**
     * @var DateTime|null
     */
    public $lastLoginFrom;

    /**
     * @var DateTime|null
     */
    public $lastLoginTo;

    /**
     * @var array|null
     */
    public $userTypes;

    public function applyFromRequest(Request $request): UserQuery
    {
        //todo validator
        $isActive = $request->query->get('isActive');
        //if `isActive` is empty string, intval will change it to `0`, so we need to check this
        if ($isActive !== null && $isActive !== '') {
            $this->isActive = intval($isActive);
        }

        $isMember = $request->query->get('isMember');
        if ($isMember !== null && $isMember !== '') {
            $this->isMember = intval($isMember);
        }

        $lastLoginFrom = $request->query->get('lastLoginFrom');
        if ($lastLoginFrom) {
            $this->lastLoginFrom = new DateTime($lastLoginFrom);
        }

        $lastLoginTo = $request->query->get('lastLoginTo');
        if ($lastLoginTo) {
            $this->lastLoginTo = new DateTime($lastLoginTo);
        }

        $userTypes = $request->query->get('userTypes');
        if ($userTypes !== null && $userTypes !== '') {
            $this->userTypes = explode(',', $userTypes);
        }

        return $this;
    }
}
