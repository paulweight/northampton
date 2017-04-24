<?php

namespace Spacecraft\Northampton\CludoSearch\Integration;

use Jadu\Response\HtmlResponse;
use Jadu\Service\Container;
use JaduFramework\Service\AbstractLocator;

class CludoServiceLocator extends AbstractLocator
{
    /**
     * @var Container
     */
    protected $serviceContainer;

    /**
     * ServiceLocator constructor.
     *
     * @param Container $serviceContainer
     */
    public function __construct(Container $serviceContainer)
    {
        parent::__construct($serviceContainer);

        $this->serviceContainer = $serviceContainer;

        $this->boot();
    }

    private function boot()
    {
        $router = $this->serviceContainer->getRouter();

        $router->add(new \Jadu_Route(
            'GET',
            '/jadu/integrations/spacecraft/cludosearch',
            'Spacecraft\Northampton\CludoSearch\Integration\Controller\ManageController::index'
        ));
        $router->add(new \Jadu_Route(
            'POST',
            '/jadu/integrations/spacecraft/cludosearch',
            'Spacecraft\Northampton\CludoSearch\Integration\Controller\ManageController::store'
        ));

        $instance = $this->serviceContainer
            ->getInjector()
            ->make(CludoSearchIntegration::class);

        $this->serviceContainer
            ->getIntegrationsContainer()
            ->register($instance);

        HtmlResponse::addPath(__DIR__ . '/Views', 'integrations.spacecraft.cludosearch');
    }

    /**
     * Ability to init the service
     *
     * @return mixed
     */
    public function init()
    {
    }
}
