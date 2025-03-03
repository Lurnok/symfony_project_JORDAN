<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\Client;
use App\Enum\RolesEnum;

final class ClientVoter extends Voter
{
    public const EDIT = 'CLIENT_EDIT';
    public const VIEW = 'CLIENT_VIEW';
    public const CREATE = 'CLIENT_CREATE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!in_array($attribute, [self::EDIT, self::VIEW, self::CREATE])){
            return false;
        }

        return $subject === null || $subject instanceof Client;
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
            case self::CREATE:
                return in_array(RolesEnum::admin->value, $user->getRoles()) 
                        || in_array(RolesEnum::manager->value, $user->getRoles()) ;
        }

        return false;
    }
}
