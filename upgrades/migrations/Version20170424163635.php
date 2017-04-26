<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20170424163635 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->updateConstant('SEARCH_ADAPTER', '\Spacecraft\Northampton\CludoSearch\Adapter\CludoSearchAdapter');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->updateConstant('SEARCH_ADAPTER', '');
    }

    /**
     * Check if a constant exists in the database.
     *
     * @param $name string The name of the constant
     *
     * @return bool If the constant exists in the database
     */
    private function constantExists($name)
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder->select('COUNT(id)')
            ->from('JaduConstants')
            ->where('name = :name')
            ->setParameter('name', $name);
        $constantExists = (bool)$queryBuilder->execute()->fetchColumn();

        return $constantExists;
    }

    /**
     * Get the value for a constant in the database.
     *
     * @param $name string The name of the constant
     *
     * @return mixed The value of the constant
     */
    private function getConstantValue($name)
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder->select('value')
            ->from('JaduConstants')
            ->where('name = :name')
            ->setParameter('name', $name);

        return $queryBuilder->execute()->fetchColumn();
    }

    /**
     * Update an existing constant in the database.
     *
     * @param $name string The name of the constant
     * @param $value string The value of the constant
     */
    private function updateConstant($name, $value)
    {
        $constantExists = $this->constantExists($name);

        $this->abortIf(!$constantExists, $name . ' constant does not exist');

        if ($constantExists) {
            $params = [
                'name' => $name,
                'value' => $value,
            ];
            $this->addSql('UPDATE JaduConstants SET value = :value WHERE name = :name', $params);
        }
    }

    /**
     * Add a custom constant to the database.
     *
     * This will insert a new constant if it doesn't exist or will update the existing constant if it already exists.
     *
     * @param string $name
     * @param string $value
     * @param string $description
     * @param int $editable
     * @param int $moduleId
     * @param string $type
     */
    private function addConstant($name, $value, $description = '', $editable = 1, $moduleId = -1, $type = 'string')
    {
        $constantExists = $this->constantExists($name);
        $params = [
            'name' => $name,
            'value' => $value,
            'description' => $description,
            'editable' => $editable,
            'moduleID' => $moduleId,
            'type' => $type,
        ];

        if ($constantExists) {
            $this->addSql('UPDATE JaduConstants SET value = :value, description = :description, editable = :editable, moduleID = :moduleID, type = :type WHERE name = :name', $params);
        } else {
            $this->addSql('INSERT INTO JaduConstants (name, value, description, editable, moduleID, type) VALUES (:name, :value, :description, :editable, :moduleID, :type)', $params);
        }
    }

    /**
     * Remove a constant from the database.
     *
     * @param string $name
     */
    private function removeConstant($name)
    {
        $this->addSql('DELETE FROM JaduConstants WHERE name = :name', ['name' => $name]);
    }
}
