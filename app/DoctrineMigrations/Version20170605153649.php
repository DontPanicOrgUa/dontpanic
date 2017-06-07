<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170605153649 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE blank (id INT AUTO_INCREMENT NOT NULL, room_id INT DEFAULT NULL, time TIME NOT NULL, INDEX IDX_3C2BC46554177093 (room_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE blank ADD CONSTRAINT FK_3C2BC46554177093 FOREIGN KEY (room_id) REFERENCES rooms (id)');
        $this->addSql('DROP TABLE sample');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE sample (id INT AUTO_INCREMENT NOT NULL, room_id INT DEFAULT NULL, time TIME NOT NULL, INDEX IDX_F10B76C354177093 (room_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sample ADD CONSTRAINT FK_F10B76C354177093 FOREIGN KEY (room_id) REFERENCES rooms (id)');
        $this->addSql('DROP TABLE blank');
    }
}
