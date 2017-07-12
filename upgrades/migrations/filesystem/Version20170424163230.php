<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Migrations\Version;
use Doctrine\DBAL\Schema\Schema;

class Version20170424163230 extends AbstractMigration
{
    protected $configPath;

    public function __construct(Version $version)
    {
        parent::__construct($version);

        $jaduPath = $this->version->getConfiguration()->getJaduPath();
        $this->configPath = $jaduPath . '/config/services.xml';
    }

    public function getServices()
    {
        return [
            'integrations.spacecraft.cludosearch' => 'Spacecraft\Northampton\CludoSearch\Integration\CludoServiceLocator',
        ];
    }

    public function preUp(Schema $schema)
    {
        // Create xml config file from the .example file if it doesn't exist
        if (!file_exists($this->configPath)) {
            $exampleConfigPath = $this->configPath . '.example';
            $this->abortIf(!is_file($exampleConfigPath), 'Couldn\'t find ' . $exampleConfigPath);

            copy($exampleConfigPath, $this->configPath);
        }
    }

    public function up(Schema $schema)
    {
        $services = $this->getServices();

        $xml = simplexml_load_file($this->configPath);

        foreach ($services as $service => $class) {
            if (!$xml->xpath('/system/services/item[@key="' . $service . '"]')) {
                $elements = $xml->xpath('/system/services');
                $element = $elements[0];
                $child = $element->addChild('item', $class);
                $child->addAttribute('key', $service);
            }
        }

        if (file_exists($this->configPath)) {
            $xml->asXML($this->configPath);
        }
    }

    public function down(Schema $schema)
    {
        $services = $this->getServices();

        if (file_exists($this->configPath)) {
            $string = file_get_contents($this->configPath);

            foreach ($services as $service => $class) {
                $search = '<item key="' . $service . '">' . $class . '</item>';
                $string = str_replace($search, '', $string);
            }

            file_put_contents($this->configPath, $string);
        }
    }
}
