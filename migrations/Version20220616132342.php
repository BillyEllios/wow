<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220616132342 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE arme_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE classe_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE faction_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE personnage_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE race_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE arme (id INT NOT NULL, personnage_id INT DEFAULT NULL, name VARCHAR(32) NOT NULL, type VARCHAR(32) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_182073795E315342 ON arme (personnage_id)');
        $this->addSql('CREATE TABLE arme_classe (arme_id INT NOT NULL, classe_id INT NOT NULL, PRIMARY KEY(arme_id, classe_id))');
        $this->addSql('CREATE INDEX IDX_D69EE2F621D9C0A ON arme_classe (arme_id)');
        $this->addSql('CREATE INDEX IDX_D69EE2F68F5EA509 ON arme_classe (classe_id)');
        $this->addSql('CREATE TABLE classe (id INT NOT NULL, name VARCHAR(32) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE classe_race (classe_id INT NOT NULL, race_id INT NOT NULL, PRIMARY KEY(classe_id, race_id))');
        $this->addSql('CREATE INDEX IDX_C47CCE498F5EA509 ON classe_race (classe_id)');
        $this->addSql('CREATE INDEX IDX_C47CCE496E59D40D ON classe_race (race_id)');
        $this->addSql('CREATE TABLE faction (id INT NOT NULL, name VARCHAR(32) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE personnage (id INT NOT NULL, race_id INT DEFAULT NULL, classe_id INT DEFAULT NULL, pseudo VARCHAR(32) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6AEA486D6E59D40D ON personnage (race_id)');
        $this->addSql('CREATE INDEX IDX_6AEA486D8F5EA509 ON personnage (classe_id)');
        $this->addSql('CREATE TABLE race (id INT NOT NULL, faction_id INT NOT NULL, name VARCHAR(32) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DA6FBBAF4448F8DA ON race (faction_id)');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE arme ADD CONSTRAINT FK_182073795E315342 FOREIGN KEY (personnage_id) REFERENCES personnage (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE arme_classe ADD CONSTRAINT FK_D69EE2F621D9C0A FOREIGN KEY (arme_id) REFERENCES arme (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE arme_classe ADD CONSTRAINT FK_D69EE2F68F5EA509 FOREIGN KEY (classe_id) REFERENCES classe (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE classe_race ADD CONSTRAINT FK_C47CCE498F5EA509 FOREIGN KEY (classe_id) REFERENCES classe (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE classe_race ADD CONSTRAINT FK_C47CCE496E59D40D FOREIGN KEY (race_id) REFERENCES race (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE personnage ADD CONSTRAINT FK_6AEA486D6E59D40D FOREIGN KEY (race_id) REFERENCES race (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE personnage ADD CONSTRAINT FK_6AEA486D8F5EA509 FOREIGN KEY (classe_id) REFERENCES classe (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE race ADD CONSTRAINT FK_DA6FBBAF4448F8DA FOREIGN KEY (faction_id) REFERENCES faction (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE arme_classe DROP CONSTRAINT FK_D69EE2F621D9C0A');
        $this->addSql('ALTER TABLE arme_classe DROP CONSTRAINT FK_D69EE2F68F5EA509');
        $this->addSql('ALTER TABLE classe_race DROP CONSTRAINT FK_C47CCE498F5EA509');
        $this->addSql('ALTER TABLE personnage DROP CONSTRAINT FK_6AEA486D8F5EA509');
        $this->addSql('ALTER TABLE race DROP CONSTRAINT FK_DA6FBBAF4448F8DA');
        $this->addSql('ALTER TABLE arme DROP CONSTRAINT FK_182073795E315342');
        $this->addSql('ALTER TABLE classe_race DROP CONSTRAINT FK_C47CCE496E59D40D');
        $this->addSql('ALTER TABLE personnage DROP CONSTRAINT FK_6AEA486D6E59D40D');
        $this->addSql('DROP SEQUENCE arme_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE classe_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE faction_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE personnage_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE race_id_seq CASCADE');
        $this->addSql('DROP TABLE arme');
        $this->addSql('DROP TABLE arme_classe');
        $this->addSql('DROP TABLE classe');
        $this->addSql('DROP TABLE classe_race');
        $this->addSql('DROP TABLE faction');
        $this->addSql('DROP TABLE personnage');
        $this->addSql('DROP TABLE race');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
