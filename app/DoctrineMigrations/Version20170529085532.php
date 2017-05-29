<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170529085532 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE rooms (id INT AUTO_INCREMENT NOT NULL, title_ru VARCHAR(255) NOT NULL, title_en VARCHAR(255) NOT NULL, title_de VARCHAR(255) NOT NULL, description_ru LONGTEXT NOT NULL, description_en LONGTEXT NOT NULL, description_de LONGTEXT NOT NULL, logo VARCHAR(255) NOT NULL, background VARCHAR(255) NOT NULL, coordinates VARCHAR(255) NOT NULL, address_ru VARCHAR(255) NOT NULL, address_en VARCHAR(255) NOT NULL, address_de VARCHAR(255) NOT NULL, difficulty INT NOT NULL, time_max INT NOT NULL, players_min INT NOT NULL, players_max INT NOT NULL, age_min INT NOT NULL, enabled TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE rooms');
    }
}
