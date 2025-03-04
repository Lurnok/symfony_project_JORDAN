<?php

namespace App\Security\Voter;

use App\Enum\RolesEnum;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\Product;

final class ProductVoter extends Voter
{
    public const EDIT = 'PRODUCT_EDIT';
    public const VIEW = 'PRODUCT_VIEW';
    public const DELETE = 'PRODUCT_DELETE';
    public const CREATE = 'PRODUCT_CREATE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!in_array($attribute, [self::EDIT, self::VIEW, self::DELETE])){
            return false;
        } 
        return $subject instanceof Product || $subject === null;
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
                return in_array(RolesEnum::admin->value,$user->getRoles());
            case self::VIEW:
                return true;
            case self::DELETE:
                return in_array(RolesEnum::admin->value,$user->getRoles());
        }

        return false;
    }
}
