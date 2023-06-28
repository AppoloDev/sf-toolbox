<?php

namespace App\Http\##AREA##\Voter;

use App\Domain\##DOMAIN##\Entity\##ENTITY##;
use AppoloDev\SFToolboxBundle\Security\Authorization\AbstractVoter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class ##ENTITY##Voter extends AbstractVoter
{
    public const LIST = '##AREALOWER##_##PREFIX##_list';
    public const ADD = '##AREALOWER##_##PREFIX##_add';
    public const EDIT = '##AREALOWER##_##PREFIX##_edit';
    public const DELETE = '##AREALOWER##_##PREFIX##_delete';
    public const EXPORT = '##AREALOWER##_##PREFIX##_export';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return match ($attribute) {
            self::LIST, self::ADD, self::EXPORT => true,
            self::EDIT, self::DELETE => $subject instanceof ##ENTITY##,
            default => false,
        };
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        // TODO: Implements
        return true;
    }
}
