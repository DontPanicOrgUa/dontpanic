<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170610214709 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE prices (id INT AUTO_INCREMENT NOT NULL, blank_id INT DEFAULT NULL, players VARCHAR(255) NOT NULL, price INT NOT NULL, day_of_week VARCHAR(255) NOT NULL, INDEX IDX_E4CB6D592B7727CD (blank_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE genres (id INT AUTO_INCREMENT NOT NULL, name_ru VARCHAR(255) NOT NULL, name_en VARCHAR(255) NOT NULL, name_de VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE currencies (id INT AUTO_INCREMENT NOT NULL, currency VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cities (id INT AUTO_INCREMENT NOT NULL, name_ru VARCHAR(255) NOT NULL, name_en VARCHAR(255) NOT NULL, name_de VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE customers (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, secondname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE timezones (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE blanks (id INT AUTO_INCREMENT NOT NULL, room_id INT DEFAULT NULL, time TIME NOT NULL, INDEX IDX_26EA711854177093 (room_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rooms (id INT AUTO_INCREMENT NOT NULL, city_id INT NOT NULL, currency_id INT NOT NULL, timezone_id INT NOT NULL, title_ru VARCHAR(255) NOT NULL, title_en VARCHAR(255) NOT NULL, title_de VARCHAR(255) NOT NULL, description_ru LONGTEXT NOT NULL, description_en LONGTEXT NOT NULL, description_de LONGTEXT NOT NULL, logo VARCHAR(255) DEFAULT NULL, background VARCHAR(255) DEFAULT NULL, coordinates VARCHAR(255) NOT NULL, address_ru VARCHAR(255) NOT NULL, address_en VARCHAR(255) NOT NULL, address_de VARCHAR(255) NOT NULL, difficulty INT NOT NULL, time_max INT NOT NULL, players_min INT NOT NULL, players_max INT NOT NULL, age_min INT NOT NULL, enabled TINYINT(1) NOT NULL, slug VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_7CA11A96989D9B62 (slug), INDEX IDX_7CA11A968BAC62AF (city_id), INDEX IDX_7CA11A9638248176 (currency_id), INDEX IDX_7CA11A963FE997DE (timezone_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payments (id INT AUTO_INCREMENT NOT NULL, amount DOUBLE PRECISION NOT NULL, currency VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, order_id VARCHAR(255) NOT NULL, version INT NOT NULL, action VARCHAR(255) NOT NULL, language VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `users` (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, username_canonical VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, email_canonical VARCHAR(180) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', phone VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1483A5E992FC23A8 (username_canonical), UNIQUE INDEX UNIQ_1483A5E9A0D96FBF (email_canonical), UNIQUE INDEX UNIQ_1483A5E9C05FB297 (confirmation_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE prices ADD CONSTRAINT FK_E4CB6D592B7727CD FOREIGN KEY (blank_id) REFERENCES blanks (id)');
        $this->addSql('ALTER TABLE blanks ADD CONSTRAINT FK_26EA711854177093 FOREIGN KEY (room_id) REFERENCES rooms (id)');
        $this->addSql('ALTER TABLE rooms ADD CONSTRAINT FK_7CA11A968BAC62AF FOREIGN KEY (city_id) REFERENCES cities (id)');
        $this->addSql('ALTER TABLE rooms ADD CONSTRAINT FK_7CA11A9638248176 FOREIGN KEY (currency_id) REFERENCES currencies (id)');
        $this->addSql('ALTER TABLE rooms ADD CONSTRAINT FK_7CA11A963FE997DE FOREIGN KEY (timezone_id) REFERENCES timezones (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE rooms DROP FOREIGN KEY FK_7CA11A9638248176');
        $this->addSql('ALTER TABLE rooms DROP FOREIGN KEY FK_7CA11A968BAC62AF');
        $this->addSql('ALTER TABLE rooms DROP FOREIGN KEY FK_7CA11A963FE997DE');
        $this->addSql('ALTER TABLE prices DROP FOREIGN KEY FK_E4CB6D592B7727CD');
        $this->addSql('ALTER TABLE blanks DROP FOREIGN KEY FK_26EA711854177093');
        $this->addSql('DROP TABLE prices');
        $this->addSql('DROP TABLE genres');
        $this->addSql('DROP TABLE currencies');
        $this->addSql('DROP TABLE cities');
        $this->addSql('DROP TABLE customers');
        $this->addSql('DROP TABLE timezones');
        $this->addSql('DROP TABLE blanks');
        $this->addSql('DROP TABLE rooms');
        $this->addSql('DROP TABLE payments');
        $this->addSql('DROP TABLE `users`');
    }
}
