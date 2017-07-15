<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170715181306 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE notifications (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, event VARCHAR(255) NOT NULL, recipient VARCHAR(255) NOT NULL, title_ru VARCHAR(255) NOT NULL, title_en VARCHAR(255) NOT NULL, title_de VARCHAR(255) NOT NULL, message_ru LONGTEXT NOT NULL, message_en LONGTEXT NOT NULL, message_de LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE messages');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE messages (id INT AUTO_INCREMENT NOT NULL, room_id INT DEFAULT NULL, type VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, title_ru VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, title_en VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, title_de VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, message_ru LONGTEXT NOT NULL COLLATE utf8_unicode_ci, message_en LONGTEXT NOT NULL COLLATE utf8_unicode_ci, message_de LONGTEXT NOT NULL COLLATE utf8_unicode_ci, INDEX IDX_DB021E9654177093 (room_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E9654177093 FOREIGN KEY (room_id) REFERENCES rooms (id)');
        $this->addSql('DROP TABLE notifications');
    }
}
