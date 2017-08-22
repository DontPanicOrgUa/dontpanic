<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170822223306 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE rooms DROP client_mail_notification, DROP client_sms_notification, DROP client_sms_reminder, DROP manager_mail_notification, DROP manager_sms_notification, DROP manager_smsreminder');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE rooms ADD client_mail_notification TINYINT(1) DEFAULT \'1\' NOT NULL, ADD client_sms_notification TINYINT(1) DEFAULT \'1\' NOT NULL, ADD client_sms_reminder TINYINT(1) DEFAULT \'1\' NOT NULL, ADD manager_mail_notification TINYINT(1) DEFAULT \'1\' NOT NULL, ADD manager_sms_notification TINYINT(1) DEFAULT \'1\' NOT NULL, ADD manager_smsreminder TINYINT(1) DEFAULT \'1\' NOT NULL');
    }
}
