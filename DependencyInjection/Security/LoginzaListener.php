<?php

namespace Zim32\LoginzaBundle\DependencyInjection\Security;

use Symfony\Component\Security\Http\Firewall\ListenerInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\DependencyInjection\Container;

class LoginzaListener implements ListenerInterface  {

    protected $securityContext;
    protected $authenticationManager;
    protected $container;

    public function __construct(SecurityContextInterface $securityContext, AuthenticationManagerInterface $authenticationManager, Container $container)
    {
        $this->securityContext = $securityContext;
        $this->authenticationManager = $authenticationManager;
        $this->container = $container;
    }

    public function handle(GetResponseEvent $event){
        $request = $event->getRequest();
        if($request->request->has('token') !== false){
            $loginzaToken = $request->request->get('token');
            $signature = md5($loginzaToken.$this->container->getParameter('security.loginza.secret_key'));
            $ch = curl_init("http://loginza.ru/api/authinfo?token={$loginzaToken}&id={$this->container->getParameter('security.loginza.widget_id')}&sig={$signature}");
            curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($ch);
            $decoded = json_decode($result,true);

            if(empty($decoded)) throw new AuthenticationException("Wrong loginza responce format");
            if(isset($decoded['error_message'])) throw new AuthenticationException($decoded['error_message']);
            
            $user = new User($decoded['name']['first_name'], $decoded['uid'], $roles = array('ROLE_USER'));

            $token = new LoginzaToken($user->getRoles());
            $token->setUser($user);
            $token->setAuthenticated(true);
            $token->setAttribute('loginza_info', $decoded);
            $this->securityContext->setToken($token);
        }
    }
}
