<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170413201546 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');
        $this->addSql("insert into role(type, createdAt, modifiedAt) values ('Admin', NOW(), NOW());");
        $this->addSql("insert into message_user(role_id, username, password, createdAt, modifiedAt) values ((select role_id from role where type = 'Admin'), 'admin', '12345', NOW(), NOW());");

        // this up() migration is auto-generated, please modify it to your needs

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');
        $this->addSql("delete from message_user where role_id = (select role_id from role where type = 'Admin');");
        $this->addSql("delete from role where type = 'Admin';");

    }
}
