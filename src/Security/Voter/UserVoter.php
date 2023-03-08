<?php

namespace App\Security\Voter;


use App\Entity\User;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class UserVoter extends Voter
{
    public const DELETE = 'USER_DELETE';
    public const VIEW = 'USER_VIEW';
    public const CREATE = 'USER_CREATE';

    private Security $security;



    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html


        if ( in_array($attribute, [self::DELETE,self::VIEW]) ) 
        {
            return $subject instanceof User;
        }
        elseif(in_array($attribute, [self::CREATE]))
        {
            return is_array($subject);
        }
        else
        {
            return false;
        }

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
            case self::DELETE:
                // logic to determine if the user can DELETE
                if ($this->security->isGranted('ROLE_SUPER_ADMIN')) {
                    return true;
                }
                elseif($this->security->isGranted('ROLE_ADMIN') and $user->getCustomer()->getId() ===  $subject->getCustomer()->getId())
                {
                     return true;
                }

                break;
            case self::VIEW:
                // logic to determine if the user can VIEW
                
                if ($this->security->isGranted('ROLE_SUPER_ADMIN')) {
                    return true;
                }
                elseif( ($this->security->isGranted('ROLE_ADMIN') or $this->security->isGranted('ROLE_CLIENT')) and $user->getCustomer()->getId() ===  $subject->getCustomer()->getId())
                {
                     return true;
                }
                
                break;
            case self::CREATE:
                // logic to determine if the user can CREATE

                if ($this->security->isGranted('ROLE_SUPER_ADMIN')) {
                    return true;
                }
                elseif($this->security->isGranted('ROLE_ADMIN') and $user->getCustomer()->getId() === $subject['customer'])
                {
                  return true;
                }

                
                break;
        }

        return false;
    }
}
