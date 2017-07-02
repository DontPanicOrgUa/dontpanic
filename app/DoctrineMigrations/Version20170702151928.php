<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170702151928 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE games DROP FOREIGN KEY FK_FF232B314C3A3BB');
        $this->addSql('DROP INDEX UNIQ_FF232B314C3A3BB ON games');
        $this->addSql('ALTER TABLE games DROP payment_id');
        $this->addSql('ALTER TABLE payments ADD game_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE payments ADD CONSTRAINT FK_65D29B32E48FD905 FOREIGN KEY (game_id) REFERENCES games (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_65D29B32E48FD905 ON payments (game_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE games ADD payment_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE games ADD CONSTRAINT FK_FF232B314C3A3BB FOREIGN KEY (payment_id) REFERENCES payments (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FF232B314C3A3BB ON games (payment_id)');
        $this->addSql('ALTER TABLE payments DROP FOREIGN KEY FK_65D29B32E48FD905');
        $this->addSql('DROP INDEX UNIQ_65D29B32E48FD905 ON payments');
        $this->addSql('ALTER TABLE payments DROP game_id');
    }
}
