<?php

namespace Zim32\LoginzaBundle\DependencyInjection\Security;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\HttpFoundation\Session;

class LoginzaUserProvider implements UserProviderInterface {

    protected $session;

    public function __construct(Session $session){
        $this->session = $session;
    }

    public function loadUserByUsername($username){
        throw new \Exception("Not supported");
    }

    public function refreshUser(UserInterface $user){
        if (!$user instanceof UserInterface) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        return $user;
    }
    
    public function supportsClass($class){
         return $class === 'Symfony\Component\Security\Core\User\User';
    }
    
}