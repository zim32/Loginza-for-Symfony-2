<?php

namespace Zim32\LoginzaBundle\DependencyInjection\Security;

use Symfony\Component\Security\Core\User\UserInterface;

class LoginzaUser {
    
    public function equals(UserInterface $user){
        return $this->uid === $user->getUid();
    }
	
	public function __sleep(){
        return array('id', 'username', 'roles', 'uid', 'password', 'salt');
    }
}