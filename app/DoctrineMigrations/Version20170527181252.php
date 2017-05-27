<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170527181252 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE genres ADD name_ru VARCHAR(255) NOT NULL, ADD name_en VARCHAR(255) NOT NULL, ADD name_de VARCHAR(255) NOT NULL, DROP title_ru, DROP title_en, DROP title_de');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE genres ADD title_ru VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, ADD title_en VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, ADD title_de VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, DROP name_ru, DROP name_en, DROP name_de');
    }
}
