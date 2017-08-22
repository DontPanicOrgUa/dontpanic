<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170822185529 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE rewards (id INT AUTO_INCREMENT NOT NULL, currency_id INT DEFAULT NULL, customer_id INT DEFAULT NULL, discount_id INT DEFAULT NULL, game_id INT DEFAULT NULL, `float` VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX IDX_E9221E3738248176 (currency_id), INDEX IDX_E9221E379395C3F3 (customer_id), INDEX IDX_E9221E374C7C611F (discount_id), INDEX IDX_E9221E37E48FD905 (game_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE rewards ADD CONSTRAINT FK_E9221E3738248176 FOREIGN KEY (currency_id) REFERENCES currencies (id)');
        $this->addSql('ALTER TABLE rewards ADD CONSTRAINT FK_E9221E379395C3F3 FOREIGN KEY (customer_id) REFERENCES customers (id)');
        $this->addSql('ALTER TABLE rewards ADD CONSTRAINT FK_E9221E374C7C611F FOREIGN KEY (discount_id) REFERENCES discounts (id)');
        $this->addSql('ALTER TABLE rewards ADD CONSTRAINT FK_E9221E37E48FD905 FOREIGN KEY (game_id) REFERENCES games (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE rewards');
    }
}
