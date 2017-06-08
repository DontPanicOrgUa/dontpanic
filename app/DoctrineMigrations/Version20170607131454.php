<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170607131454 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE prices (id INT AUTO_INCREMENT NOT NULL, blank_id INT DEFAULT NULL, players VARCHAR(255) NOT NULL, price INT NOT NULL, INDEX IDX_E4CB6D592B7727CD (blank_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE blanks (id INT AUTO_INCREMENT NOT NULL, room_id INT DEFAULT NULL, time TIME NOT NULL, INDEX IDX_26EA711854177093 (room_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE prices ADD CONSTRAINT FK_E4CB6D592B7727CD FOREIGN KEY (blank_id) REFERENCES prices (id)');
        $this->addSql('ALTER TABLE blanks ADD CONSTRAINT FK_26EA711854177093 FOREIGN KEY (room_id) REFERENCES rooms (id)');
        $this->addSql('DROP TABLE blank');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE prices DROP FOREIGN KEY FK_E4CB6D592B7727CD');
        $this->addSql('CREATE TABLE blank (id INT AUTO_INCREMENT NOT NULL, room_id INT DEFAULT NULL, time TIME NOT NULL, INDEX IDX_3C2BC46554177093 (room_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE blank ADD CONSTRAINT FK_3C2BC46554177093 FOREIGN KEY (room_id) REFERENCES rooms (id)');
        $this->addSql('DROP TABLE prices');
        $this->addSql('DROP TABLE blanks');
    }
}
