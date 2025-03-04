<?php

namespace App\Security\Voter;

use App\Enum\RolesEnum;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\User;

final class UserVoter extends Voter
{
    public const EDIT = 'USER_EDIT';
    public const VIEW = 'USER_VIEW';
    public const DELETE = 'USER_DELETE';
    public const CREATE  = 'USER_CREATE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        if(!in_array($attribute, [self::EDIT, self::VIEW, self::DELETE,self::CREATE])){
            return false;
        }

        return $subject instanceof User || $subject === null;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::EDIT:
            case self::VIEW:
            case self::DELETE:
            case self::CREATE:
                return in_array(RolesEnum::admin->value,$user->getRoles());
        }

        return false;
    }
}
