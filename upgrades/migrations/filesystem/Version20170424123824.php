<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20170424123824 extends AbstractMigration
{
    const CONFIG_FILE = 'config/bundles.xml';

    private $bundles = [
        [
            'app' => 'frontend',
            'class' => 'Spacecraft\Bundle\FrontendBundle\SpacecraftFrontendBundle',
        ],
    ];

    public function up(Schema $schema)
    {
        $config = $this->version->getConfiguration();
        $configPath = $config->getJaduPath().'/'.self::CONFIG_FILE;

        if (!file_exists($configPath)) {
            $xml = <<<'XML'
<?xml version="1.0" encoding="utf-8" ?>
<bundles xmlns:config="http://www.jadu.co.uk/schema/config">
    <core config:type="array"></core>
    <cc config:type="array"></cc>
    <frontend config:type="array"></frontend>
</bundles>
XML;

            file_put_contents($configPath, $xml);
        }

        $xml = simplexml_load_file($configPath);

        foreach ($this->bundles as $bundle) {
            $this->addBundle($xml, $bundle['app'], $bundle['class'], isset($bundle['env']) ? $bundle['env'] : null);
        }

        file_put_contents($configPath, $xml->asXML());
    }

    public function down(Schema $schema)
    {
        $jaduPath = rtrim($this->version->getConfiguration()->getJaduPath(), '/');

        if (is_file($jaduPath.'/config/bundles.xml')) {
            unlink($jaduPath.'/config/bundles.xml');
        }
    }

    private function addBundle(SimpleXMLElement $xml, $app, $bundleClass, $env = null)
    {
        $appElement = $xml->{$app};

        $found = false;
        foreach ($appElement->item as $itemElement) {
            if (((string) $itemElement) === $bundleClass) {
                $found = true;
                break;
            }
        }

        if (!$found) {
            $itemElement = $appElement->addChild('item', $bundleClass);
            if ($env !== null) {
                $itemElement['env'] = $env;
            }
        }
    }
}
