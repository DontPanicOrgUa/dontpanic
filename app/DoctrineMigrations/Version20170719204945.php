<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170719204945 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE rooms CHANGE enable_client_mail_notification enable_client_mail_notification TINYINT(1) DEFAULT \'1\' NOT NULL, CHANGE enable_client_sms_notification enable_client_sms_notification TINYINT(1) DEFAULT \'1\' NOT NULL, CHANGE enable_client_sms_reminder enable_client_sms_reminder TINYINT(1) DEFAULT \'1\' NOT NULL, CHANGE enable_manager_mail_notification enable_manager_mail_notification TINYINT(1) DEFAULT \'1\' NOT NULL, CHANGE enable_manager_sms_notification enable_manager_sms_notification TINYINT(1) DEFAULT \'1\' NOT NULL, CHANGE enable_manager_smsreminder enable_manager_smsreminder TINYINT(1) DEFAULT \'1\' NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE rooms CHANGE enable_client_mail_notification enable_client_mail_notification TINYINT(1) NOT NULL, CHANGE enable_client_sms_notification enable_client_sms_notification TINYINT(1) NOT NULL, CHANGE enable_client_sms_reminder enable_client_sms_reminder TINYINT(1) NOT NULL, CHANGE enable_manager_mail_notification enable_manager_mail_notification TINYINT(1) NOT NULL, CHANGE enable_manager_sms_notification enable_manager_sms_notification TINYINT(1) NOT NULL, CHANGE enable_manager_smsreminder enable_manager_smsreminder TINYINT(1) NOT NULL');
    }
}
