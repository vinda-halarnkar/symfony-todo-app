<?php
namespace App\Security;

use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use App\Entity\User;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof User) {
            return;
        }

        if (!$user->getIsVerified()) {
            throw new CustomUserMessageAccountStatusException('Your account is not verified.');
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
        // Additional checks post auth if needed
    }
}
