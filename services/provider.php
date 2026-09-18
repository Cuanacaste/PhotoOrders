<?php
defined('_JEXEC') or die;

use Joomla\CMS\Extension\Service\Provider\ComponentDispatcherFactory;
use Joomla\CMS\Extension\Service\Provider\MVCFactory;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use YourVendor\Component\Photoorders\Site\Extension\PhotoordersComponent;

return new class implements ServiceProviderInterface {
    public function register(Container $container): void
    {
        $container->registerServiceProvider(new ComponentDispatcherFactory('\\YourVendor\\Component\\Photoorders'));
        $container->registerServiceProvider(new MVCFactory('\\YourVendor\\Component\\Photoorders'));

        $container->set(
            PhotoordersComponent::class,
            function (Container $container) {
                $component = new PhotoordersComponent($container->get(ComponentDispatcherFactoryInterface::class));
                $component->setMVCFactory($container->get(MVCFactoryInterface::class));
                return $component;
            }
        );
    }
};