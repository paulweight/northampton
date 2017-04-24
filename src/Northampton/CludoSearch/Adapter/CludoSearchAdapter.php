<?php

namespace Spacecraft\Northampton\CludoSearch\Adapter;

use Jadu\Service\Search\Adapter\AbstractAdapter;
use Jadu\Service\Search\AdapterInterface;
use Jadu\Symfony\DependencyInjection\ServiceLocator;
use Jadu\UI\Pages\ControlCenter\Utilities\Integrations as IntegrationApi;

class CludoSearchAdapter extends AbstractAdapter implements AdapterInterface
{
    /**
     * @var \Symfony\Bundle\FrameworkBundle\Templating\EngineInterface
     */
    protected $templating;

    public function __construct()
    {
        parent::__construct();

        $this->templating = ServiceLocator::get('templating');
    }

    /**
     * Performs a search with the current settings.
     *
     * @return \RupaSearchResult
     */
    public function search()
    {
        // no search results are returned as all processing happens with Cludo via JavaScript
        return new \RupaSearchResult();
    }

    /**
     * @return string
     */
    public function getSearchBox()
    {
        $api = new IntegrationApi();
        $customerId = $api->getIntegrationValue('spacecraft-cludosearch-customer-id');
        $engineId = $api->getIntegrationValue('spacecraft-cludosearch-engine-id');

        $params = [
            'customerId' => $customerId,
            'engineId' => $engineId,
        ];

        return $this->templating->render('SpacecraftFrontendBundle:CludoSearch:searchbox.html.twig', $params);
    }

    /**
     * @return string
     */
    public function getSearchResults()
    {
        // Search results are returned in a page overlay via ajax, therefore there is no separate results page
        return '';
    }

    /**
     * @return string
     */
    public function getSearchScript()
    {
        $api = new IntegrationApi();
        $customerId = $api->getIntegrationValue('spacecraft-cludosearch-customer-id');
        $engineId = $api->getIntegrationValue('spacecraft-cludosearch-engine-id');
        $theme = $api->getIntegrationValue('spacecraft-cludosearch-theme');

        $params = [
            'customerId' => $customerId,
            'engineId' => $engineId,
            'theme' => $theme,
        ];

        return $this->templating->render('SpacecraftFrontendBundle:CludoSearch:searchscript.html.twig', $params);
    }
}
