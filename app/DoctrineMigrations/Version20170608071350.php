<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170608071350 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE prices DROP FOREIGN KEY FK_E4CB6D592B7727CD');
        $this->addSql('ALTER TABLE prices ADD day_of_week VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE prices ADD CONSTRAINT FK_E4CB6D592B7727CD FOREIGN KEY (blank_id) REFERENCES blanks (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE prices DROP FOREIGN KEY FK_E4CB6D592B7727CD');
        $this->addSql('ALTER TABLE prices DROP day_of_week');
        $this->addSql('ALTER TABLE prices ADD CONSTRAINT FK_E4CB6D592B7727CD FOREIGN KEY (blank_id) REFERENCES prices (id)');
    }
}
