<?php

namespace Magenta\Bundle\CBookAdminBundle\DependencyInjection;

use Doctrine\Common\Annotations\AnnotationReader;
use Magenta\Bundle\CBookAdminBundle\Admin\BaseAdmin;
use Magenta\Bundle\CBookAdminBundle\Admin\BaseCRUDAdminController;
use ProxyManager\FileLocator\FileLocator;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

class MagentaCBookAdminExtension extends ConfigurableExtension
{
    // note that this method is called loadInternal and not load
    protected function loadInternal(array $mergedConfig, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new \Symfony\Component\Config\FileLocator(__DIR__ . '/../Resources/config'));

//		$container->registerForAutoconfiguration(BaseCRUDAdminController::class)
//		          ->addTag('controller.service_arguments');

        $loader->load('admin.yaml');

        $definitions = [];
        foreach (get_declared_classes() as $class) {
            if (is_subclass_of($class, CRUDController::class)) {
                if ($container->hasDefinition($class)) {
                    $container->getDefinition($class)->addTag('controller.service_arguments');
                }
            } elseif (is_subclass_of($class, BaseAdmin::class)) {
                if (empty($class::AUTO_CONFIG)) {
                    continue;
                }
                $className = explode('\\', str_replace('Admin', '', $class));

                $def = new Definition();
                $def->setClass($class);
                $def->addTag('sonata.admin', [
                    'manager_type' => 'orm',
                    'label' => strtolower(end($className)),
                    'label_translator_strategy' => 'sonata.admin.label.strategy.underscore'
                ]);

                $code = $class;
                if (empty($entity = $class::ENTITY)) {
                    $entity = str_replace('Admin\\', 'Entity\\', $code);
                    $entity = str_replace('AdminBundle', 'ModelBundle', $entity);
                    $entity = str_replace('Admin', '', $entity);
                }

                if (empty($controller = $class::CONTROLLER)) {
                    $controller = $class . 'Controller';
                    if (!class_exists($controller)) {
                        $controller = BaseCRUDAdminController::class;
                    }
                }

                if (!empty($templates = $class::TEMPLATES)) {
                    foreach ($templates as $name => $template) {
                        $def->addMethodCall('setTemplate', [$name, $template]);
                    }
                }

                $def->setArguments([$code, $entity, $controller]);

                $definitions[$code] = $def;
            }
        }

        $container->addDefinitions($definitions);
        foreach (get_declared_classes() as $class) {
            if (is_subclass_of($class, CRUDController::class)) {
            } elseif (is_subclass_of($class, BaseAdmin::class)) {
                if (empty($class::AUTO_CONFIG)) {
                    continue;
                }
                $className = explode('\\', str_replace('Admin', '', $class));
                $def = $container->getDefinition($class);
                if (!empty($children = $class::CHILDREN)) {
                    foreach ($children as $child => $property) {
                        $def->addMethodCall('addChild', [$container->getDefinition($child), $property]);
                    }
                }
            }
        }

//		var_dump($container->getDefinitions());
    }
}
