<?php

namespace App\Http\__CAPITALIZED_AREA__\Voter;

use App\Domain\__DOMAIN__\Entity\__ENTITY__;
use AppoloDev\SFToolboxBundle\Security\Authorization\AbstractVoter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class __ENTITY__Voter extends AbstractVoter
{
    public const LIST = '__LOWER_AREA_____PREFIX___list';
    public const ADD = '__LOWER_AREA_____PREFIX___add';
    public const EDIT = '__LOWER_AREA_____PREFIX___edit';
    public const DELETE = '__LOWER_AREA_____PREFIX___delete';
    public const EXPORT = '__LOWER_AREA_____PREFIX___export';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return match ($attribute) {
            self::LIST, self::ADD, self::EXPORT => true,
            self::EDIT, self::DELETE => $subject instanceof __ENTITY__,
            default => false,
        };
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        // TODO: Implements
        return true;
    }
}
