<?php

namespace Zim32\LoginzaBundle\DependencyInjection\Security;

use Symfony\Component\Security\Core\User\UserInterface;

class LoginzaUser {
    
    public function equals(UserInterface $user){
        return $this->uid === $user->getUid();
    }
}