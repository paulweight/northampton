<?php

namespace Spacecraft\Northampton\CludoSearch\Integration;

use Jadu\Integrations\AbstractIntegration;

class CludoSearchIntegration extends AbstractIntegration
{
    /**
     * @return string
     */
    public function getTitle()
    {
        return 'Cludo Search';
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'Use Cludo for your site search';
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return '/jadu/custom/images/cludo_logo.png';
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return SECURE_JADU_PATH . '/integrations/spacecraft/cludosearch';
    }

    /**
     * @return string
     */
    public function getMachineName()
    {
        return 'cludosearch';
    }

    /**
     * @return bool
     */
    public function galaxySiteEnabled()
    {
        return true;
    }
}
