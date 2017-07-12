<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20170712143712 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->updateConstant('FORCE_SECURE', 'true');
        $this->updateConstant('TEST_COOKIE_SECURE_CONNECTION', 'true');

        $blogUrl = $this->getConstantValue('BLOG_ROOT_URL');
        $blogUrl = str_replace('http://', 'https://', $blogUrl);
        $this->updateConstant('BLOG_ROOT_URL', $blogUrl);

        $blogImagesUrl = $this->getConstantValue('BLOG_IMAGES_URL');
        $blogImagesUrl = str_replace('http://', 'https://', $blogImagesUrl);
        $this->updateConstant('BLOG_IMAGES_URL', $blogImagesUrl);
    }

    public function down(Schema $schema)
    {
        $this->updateConstant('FORCE_SECURE', 'false');
        $this->updateConstant('TEST_COOKIE_SECURE_CONNECTION', 'false');

        $blogUrl = $this->getConstantValue('BLOG_ROOT_URL');
        $blogUrl = str_replace('https://', 'http://', $blogUrl);
        $this->updateConstant('BLOG_ROOT_URL', $blogUrl);

        $blogImagesUrl = $this->getConstantValue('BLOG_IMAGES_URL');
        $blogImagesUrl = str_replace('https://', 'http://', $blogImagesUrl);
        $this->updateConstant('BLOG_IMAGES_URL', $blogImagesUrl);
    }

    /**
     * Check if a constant exists in the database
     *
     * @param $name string The name of the constant
     *
     * @return bool If the constant exists in the database
     */
    protected function constantExists($name)
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder->select('COUNT(c.id)')
            ->from('JaduConstants', 'c')
            ->where('c.name = :name')
            ->setParameter('name', $name);
        $constantExists = (bool) $queryBuilder->execute()->fetchColumn();

        return $constantExists;
    }

    /**
     * Get the value for a constant in the database
     *
     * @param $name string The name of the constant
     *
     * @return mixed The value of the constant
     */
    protected function getConstantValue($name)
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder->select('value')
            ->from('JaduConstants')
            ->where('name = :name')
            ->setParameter('name', $name);

        return $queryBuilder->execute()->fetchColumn();
    }

    /**
     * Update an existing constant in the database
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
}
