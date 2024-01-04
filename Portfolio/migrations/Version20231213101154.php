<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231213101154 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE api_error_codes_integer_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE storage_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE api_error_codes (integer INT NOT NULL, storage_id INT DEFAULT NULL, code INT NOT NULL, message TEXT NOT NULL, http_status_code INT DEFAULT NULL, PRIMARY KEY(integer))');
        $this->addSql('CREATE INDEX IDX_A0F140895CC5DB90 ON api_error_codes (storage_id)');
        $this->addSql('CREATE TABLE storage (id INT NOT NULL, hot BOOLEAN NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE api_error_codes ADD CONSTRAINT FK_A0F140895CC5DB90 FOREIGN KEY (storage_id) REFERENCES storage (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE api_error_codes_integer_seq CASCADE');
        $this->addSql('DROP SEQUENCE storage_id_seq CASCADE');
        $this->addSql('ALTER TABLE api_error_codes DROP CONSTRAINT FK_A0F140895CC5DB90');
        $this->addSql('DROP TABLE api_error_codes');
        $this->addSql('DROP TABLE storage');
    }
}
