<?php

namespace DneCustomJsCss;

use DneCustomJsCss\Components\CompilerPass\ReplaceThemeCompilerPass;
use Shopware\Components\Plugin;
use Shopware\Components\Plugin\Context\ActivateContext;
use Shopware\Components\Plugin\Context\InstallContext;
use Shopware\Components\Plugin\Context\UninstallContext;
use Shopware\Components\Plugin\Context\UpdateContext;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class DneCustomJsCss
 */
class DneCustomJsCss extends Plugin
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        $container->setParameter('dne_custom_js_css.plugin_dir', $this->getPath());

        parent::build($container);

        $container->addCompilerPass(new ReplaceThemeCompilerPass());
    }

    /**
     * @param InstallContext $context
     */
    public function install(InstallContext $context)
    {
        $sql = file_get_contents($this->getPath() . '/Resources/sql/install.sql');

        $this->container->get('shopware.db')->query($sql);
    }

    /**
     * @param ActivateContext $context
     */
    public function activate(ActivateContext $context)
    {
        $context->scheduleClearCache(InstallContext::CACHE_LIST_ALL);
    }

    /**
     * @param UpdateContext $context
     */
    public function update(UpdateContext $context)
    {
        $context->scheduleClearCache(InstallContext::CACHE_LIST_ALL);
    }

    /**
     * @param UninstallContext $context
     */
    public function uninstall(UninstallContext $context)
    {
        $sql = file_get_contents($this->getPath() . '/Resources/sql/uninstall.sql');

        $this->container->get('dbal_connection')->query($sql);
    }
}
