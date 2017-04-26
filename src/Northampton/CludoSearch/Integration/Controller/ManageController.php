<?php

namespace Spacecraft\Northampton\CludoSearch\Integration\Controller;

use Jadu\Integrations\AbstractIntegrationController;
use Jadu\Response\RedirectResponse;
use Jadu\UI\Pages\ControlCenter\Utilities\Integrations as IntegrationApi;

class ManageController extends AbstractIntegrationController
{
    /**
     * @param IntegrationApi $api
     */
    public function __construct(IntegrationApi $api)
    {
        parent::__construct($api);

        require_once 'JaduConstantsFunctions.php';
        require_once 'utilities/JaduAdminPageActions.php';
    }

    public function index()
    {
        if ($this->canAccessPage()) {
            $params = array_merge(
                [
                    'tab_visibility' => 'is-selected',
                    'errors' => [],
                    'csrfToken' => $this->jadu->getCSRFToken()->getToken(),
                ],
                $this->defaultParams);

            $values = $this->getValues();

            $params = array_merge(['values' => $values], $params);

            return $this->response('@integrations.spacecraft.cludosearch/index.html.twig', $params);
        }

        return $this->redirectToError();
    }

    /**
     * @return array
     */
    protected function getValues()
    {
        return [
            'spacecraft-cludosearch-customer-id' => $this->api->getIntegrationValue('spacecraft-cludosearch-customer-id'),
            'spacecraft-cludosearch-engine-id' => $this->api->getIntegrationValue('spacecraft-cludosearch-engine-id'),
            'spacecraft-cludosearch-theme' => $this->api->getIntegrationValue('spacecraft-cludosearch-theme'),
        ];
    }

    public function store()
    {
        if (
            $this->canAccessPage() &&
            $this->pageAccess->updateContent &&
            $this->jadu->getCSRFToken()->isValid($this->input->post('__token'))
        ) {
            $values = [
                'spacecraft-cludosearch-customer-id' => (int) $this->input->post('spacecraft-cludosearch-customer-id'),
                'spacecraft-cludosearch-engine-id' => (int) $this->input->post('spacecraft-cludosearch-engine-id'),
                'spacecraft-cludosearch-theme' => trim($this->input->post('spacecraft-cludosearch-theme')),
            ];

            try {
                $this->validateValues($values)->setValue($values);
                \newAdminPageAction(ADMIN_SUBMIT_UPDATE, 'JaduConstants', 'Updated Cludo Search Integration');

                $this->flash->set('success', 'Your changes have been saved successfully.');
            } catch (\Exception $e) {
                $this->flash->set('error', 'Please make sure all required fields have been filled in correctly.');
            }

            return new RedirectResponse($this->sitePrefix . '/jadu/integrations/spacecraft/cludosearch');
        }

        return $this->redirectToError();
    }

    /**
     * @param array $values
     */
    protected function setValue(array $values)
    {
        $this->api->setIntegrationValue('spacecraft-cludosearch-customer-id', array_get($values, 'spacecraft-cludosearch-customer-id'));
        $this->api->setIntegrationValue('spacecraft-cludosearch-engine-id', array_get($values, 'spacecraft-cludosearch-engine-id'));
        $this->api->setIntegrationValue('spacecraft-cludosearch-theme', array_get($values, 'spacecraft-cludosearch-theme'));
    }

    /**
     * @param array $values
     * @return $this
     * @throws \Exception
     */
    protected function validateValues($values)
    {
        $required = [
            'spacecraft-cludosearch-customer-id',
            'spacecraft-cludosearch-engine-id',
        ];

        foreach ($values as $key => $value) {
            if (in_array($key, $required) && !trim($value)) {
                throw new \Exception('Required value missing: ' . $key);
            }
        }

        return $this;
    }
}
