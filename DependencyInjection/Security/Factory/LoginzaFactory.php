<?php
namespace Zim32\LoginzaBundle\DependencyInjection\Security\Factory;

use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SecurityFactoryInterface;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class LoginzaFactory implements SecurityFactoryInterface {

	public function create(ContainerBuilder $container, $id, $config, $userProvider, $defaultEntryPoint){
        $providerId = 'security.authentication.provider.loginza.'.$id;
        $container
            ->setDefinition($providerId, new DefinitionDecorator('security.authentication.provider.dao'))
            ->replaceArgument(0, new Reference($userProvider))
            ->replaceArgument(2, $id)
        ;

        $entryPointId = $this->createEntryPoint($container, $id, $config, $defaultEntryPoint);

        $listenerId = 'security.authentication.listener.loginza.'.$id;
        $listener = $container->setDefinition($listenerId, new DefinitionDecorator('loginza.security.authentication.listener'));

        $container->setParameter('security.loginza.login_route', $config['login_route']);
        $container->setParameter('security.loginza.token_route', $config['token_route']);
        $container->setParameter('security.loginza.secret_key', $config['secret_key']);
        $container->setParameter('security.loginza.widget_id', $config['widget_id']);
        $container->setParameter('security.loginza.entity', isset($config['entity'])?$config['entity']:false);

        return array($providerId, $listenerId, $entryPointId);
	}

    protected function createEntryPoint($container, $id, $config, $defaultEntryPoint)
    {
        if (null !== $defaultEntryPoint) {
            return $defaultEntryPoint;
        }

        $entryPointId = 'security.authentication.loginza_entry_point.'.$id;
        $container
            ->setDefinition($entryPointId, new DefinitionDecorator('security.authentication.loginza_entry_point'))
            ->addArgument($config)
        ;

        return $entryPointId;
    }

	public function addConfiguration(NodeDefinition $node) {
		$node
            ->children()
                ->scalarNode('login_route')->isRequired()->end()
            ->end()
            ->children()
                ->scalarNode('token_route')->isRequired()->end()
            ->end()
            ->children()
                ->scalarNode('secret_key')->isRequired()->end()
            ->end()
            ->children()
                ->scalarNode('widget_id')->isRequired()->end()
            ->end()
            ->children()
                ->scalarNode('entity')->end()
            ->end()
           ;
	}

    public function getPosition()
    {
        return 'http';
    }

    public function getKey()
    {
        return 'loginza';
    }

	protected function createAuthProvider(ContainerBuilder $container, $id, $config, $userProviderId){
		return;
	}

}
