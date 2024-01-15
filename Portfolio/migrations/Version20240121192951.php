<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240121192951 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE account (id UUID NOT NULL, parent_id UUID DEFAULT NULL, storage_id UUID DEFAULT NULL, virtual BOOLEAN NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7D3656A4727ACA70 ON account (parent_id)');
        $this->addSql('CREATE INDEX IDX_7D3656A45CC5DB90 ON account (storage_id)');
        $this->addSql('COMMENT ON COLUMN account.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN account.parent_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN account.storage_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE api_error_codes (id UUID NOT NULL, storage_id UUID DEFAULT NULL, code INT NOT NULL, message TEXT NOT NULL, http_status_code INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A0F140895CC5DB90 ON api_error_codes (storage_id)');
        $this->addSql('COMMENT ON COLUMN api_error_codes.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN api_error_codes.storage_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE asset (id UUID NOT NULL, name VARCHAR(255) NOT NULL, ticker VARCHAR(255) NOT NULL, category VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN asset.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE "order" (id UUID NOT NULL, asset_id UUID DEFAULT NULL, quantity DOUBLE PRECISION NOT NULL, direction VARCHAR(255) NOT NULL, order_type VARCHAR(255) NOT NULL, input_time INT NOT NULL, transmition_time INT NOT NULL, status VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F52993985DA1941 ON "order" (asset_id)');
        $this->addSql('COMMENT ON COLUMN "order".id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN "order".asset_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE permission (id UUID NOT NULL, user_id UUID DEFAULT NULL, account_id UUID DEFAULT NULL, permissions JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E04992AAA76ED395 ON permission (user_id)');
        $this->addSql('CREATE INDEX IDX_E04992AA9B6B5FBA ON permission (account_id)');
        $this->addSql('COMMENT ON COLUMN permission.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN permission.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN permission.account_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE person (id UUID NOT NULL, username VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_34DCD176F85E0677 ON person (username)');
        $this->addSql('COMMENT ON COLUMN person.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE user_squad (user_id UUID NOT NULL, squad_id UUID NOT NULL, PRIMARY KEY(user_id, squad_id))');
        $this->addSql('CREATE INDEX IDX_2D1204BFA76ED395 ON user_squad (user_id)');
        $this->addSql('CREATE INDEX IDX_2D1204BFDF1B2C7C ON user_squad (squad_id)');
        $this->addSql('COMMENT ON COLUMN user_squad.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN user_squad.squad_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE squad (id UUID NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN squad.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE storage (id UUID NOT NULL, hot BOOLEAN NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_547A1B345E237E06 ON storage (name)');
        $this->addSql('COMMENT ON COLUMN storage.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE token (id UUID NOT NULL, permission_id UUID DEFAULT NULL, account_id UUID DEFAULT NULL, credentials JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5F37A13BFED90CCA ON token (permission_id)');
        $this->addSql('CREATE INDEX IDX_5F37A13B9B6B5FBA ON token (account_id)');
        $this->addSql('COMMENT ON COLUMN token.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN token.permission_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN token.account_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE transaction (id UUID NOT NULL, account_id UUID DEFAULT NULL, asset_id UUID DEFAULT NULL, order_id UUID DEFAULT NULL, quantity DOUBLE PRECISION NOT NULL, price DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_723705D19B6B5FBA ON transaction (account_id)');
        $this->addSql('CREATE INDEX IDX_723705D15DA1941 ON transaction (asset_id)');
        $this->addSql('CREATE INDEX IDX_723705D18D9F6D38 ON transaction (order_id)');
        $this->addSql('COMMENT ON COLUMN transaction.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN transaction.account_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN transaction.asset_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN transaction.order_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE account ADD CONSTRAINT FK_7D3656A4727ACA70 FOREIGN KEY (parent_id) REFERENCES account (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE account ADD CONSTRAINT FK_7D3656A45CC5DB90 FOREIGN KEY (storage_id) REFERENCES storage (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE api_error_codes ADD CONSTRAINT FK_A0F140895CC5DB90 FOREIGN KEY (storage_id) REFERENCES storage (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT FK_F52993985DA1941 FOREIGN KEY (asset_id) REFERENCES asset (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE permission ADD CONSTRAINT FK_E04992AAA76ED395 FOREIGN KEY (user_id) REFERENCES person (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE permission ADD CONSTRAINT FK_E04992AA9B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_squad ADD CONSTRAINT FK_2D1204BFA76ED395 FOREIGN KEY (user_id) REFERENCES person (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_squad ADD CONSTRAINT FK_2D1204BFDF1B2C7C FOREIGN KEY (squad_id) REFERENCES squad (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE token ADD CONSTRAINT FK_5F37A13BFED90CCA FOREIGN KEY (permission_id) REFERENCES permission (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE token ADD CONSTRAINT FK_5F37A13B9B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D19B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D15DA1941 FOREIGN KEY (asset_id) REFERENCES asset (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D18D9F6D38 FOREIGN KEY (order_id) REFERENCES "order" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE account DROP CONSTRAINT FK_7D3656A4727ACA70');
        $this->addSql('ALTER TABLE account DROP CONSTRAINT FK_7D3656A45CC5DB90');
        $this->addSql('ALTER TABLE api_error_codes DROP CONSTRAINT FK_A0F140895CC5DB90');
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT FK_F52993985DA1941');
        $this->addSql('ALTER TABLE permission DROP CONSTRAINT FK_E04992AAA76ED395');
        $this->addSql('ALTER TABLE permission DROP CONSTRAINT FK_E04992AA9B6B5FBA');
        $this->addSql('ALTER TABLE user_squad DROP CONSTRAINT FK_2D1204BFA76ED395');
        $this->addSql('ALTER TABLE user_squad DROP CONSTRAINT FK_2D1204BFDF1B2C7C');
        $this->addSql('ALTER TABLE token DROP CONSTRAINT FK_5F37A13BFED90CCA');
        $this->addSql('ALTER TABLE token DROP CONSTRAINT FK_5F37A13B9B6B5FBA');
        $this->addSql('ALTER TABLE transaction DROP CONSTRAINT FK_723705D19B6B5FBA');
        $this->addSql('ALTER TABLE transaction DROP CONSTRAINT FK_723705D15DA1941');
        $this->addSql('ALTER TABLE transaction DROP CONSTRAINT FK_723705D18D9F6D38');
        $this->addSql('DROP TABLE account');
        $this->addSql('DROP TABLE api_error_codes');
        $this->addSql('DROP TABLE asset');
        $this->addSql('DROP TABLE "order"');
        $this->addSql('DROP TABLE permission');
        $this->addSql('DROP TABLE person');
        $this->addSql('DROP TABLE user_squad');
        $this->addSql('DROP TABLE squad');
        $this->addSql('DROP TABLE storage');
        $this->addSql('DROP TABLE token');
        $this->addSql('DROP TABLE transaction');
    }
}
