<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170816192237 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pages ADD meta_title VARCHAR(255) DEFAULT NULL, ADD meta_description VARCHAR(255) DEFAULT NULL, ADD meta_keywords VARCHAR(255) DEFAULT NULL, CHANGE content_ru content_ru LONGTEXT NOT NULL, CHANGE content_en content_en LONGTEXT NOT NULL, CHANGE content_de content_de LONGTEXT NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pages DROP meta_title, DROP meta_description, DROP meta_keywords, CHANGE content_ru content_ru VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE content_en content_en VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE content_de content_de VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
    }
}
