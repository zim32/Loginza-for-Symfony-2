<?php

namespace Zim32\LoginzaBundle\DependencyInjection\Security;

use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoginzaEntryPoint implements AuthenticationEntryPointInterface {

    protected $config = array();
    protected $container;

    public function __construct(ContainerInterface $container, array $conf){
        $this->config = $conf;
        $this->container = $container;
    }

    public function start(Request $request, AuthenticationException $authException = null){
        if($authException !== null){
            if(!is_a($authException, 'Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException')){
                $this->container->get('session')->setFlash('error', $authException->getMessage());
            }
        }
        $redirect_url = $this->container->get('router')->generate($this->config['login_route'], array(), false);
        $response = new RedirectResponse($redirect_url);
        return $response;
     }
}