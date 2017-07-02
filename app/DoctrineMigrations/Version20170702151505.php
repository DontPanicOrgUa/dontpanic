<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170702151505 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE games (id INT AUTO_INCREMENT NOT NULL, room_id INT NOT NULL, customer_id INT NOT NULL, payment_id INT DEFAULT NULL, datetime DATETIME NOT NULL, booking_data VARCHAR(255) NOT NULL, result INT NOT NULL, photo VARCHAR(255) NOT NULL, INDEX IDX_FF232B3154177093 (room_id), INDEX IDX_FF232B319395C3F3 (customer_id), UNIQUE INDEX UNIQ_FF232B314C3A3BB (payment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE games ADD CONSTRAINT FK_FF232B3154177093 FOREIGN KEY (room_id) REFERENCES rooms (id)');
        $this->addSql('ALTER TABLE games ADD CONSTRAINT FK_FF232B319395C3F3 FOREIGN KEY (customer_id) REFERENCES customers (id)');
        $this->addSql('ALTER TABLE games ADD CONSTRAINT FK_FF232B314C3A3BB FOREIGN KEY (payment_id) REFERENCES payments (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE games');
    }
}
