<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220607111310 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE personnage DROP CONSTRAINT fk_6aea486d9e225b24');
        $this->addSql('ALTER TABLE personnage DROP CONSTRAINT fk_6aea486d99ae984c');
        $this->addSql('DROP INDEX idx_6aea486d9e225b24');
        $this->addSql('DROP INDEX idx_6aea486d99ae984c');
        $this->addSql('ALTER TABLE personnage ADD race_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE personnage ADD classe_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE personnage DROP races_id');
        $this->addSql('ALTER TABLE personnage DROP classes_id');
        $this->addSql('ALTER TABLE personnage ADD CONSTRAINT FK_6AEA486D6E59D40D FOREIGN KEY (race_id) REFERENCES race (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE personnage ADD CONSTRAINT FK_6AEA486D8F5EA509 FOREIGN KEY (classe_id) REFERENCES classe (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_6AEA486D6E59D40D ON personnage (race_id)');
        $this->addSql('CREATE INDEX IDX_6AEA486D8F5EA509 ON personnage (classe_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE personnage DROP CONSTRAINT FK_6AEA486D6E59D40D');
        $this->addSql('ALTER TABLE personnage DROP CONSTRAINT FK_6AEA486D8F5EA509');
        $this->addSql('DROP INDEX IDX_6AEA486D6E59D40D');
        $this->addSql('DROP INDEX IDX_6AEA486D8F5EA509');
        $this->addSql('ALTER TABLE personnage ADD races_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE personnage ADD classes_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE personnage DROP race_id');
        $this->addSql('ALTER TABLE personnage DROP classe_id');
        $this->addSql('ALTER TABLE personnage ADD CONSTRAINT fk_6aea486d9e225b24 FOREIGN KEY (classes_id) REFERENCES classe (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE personnage ADD CONSTRAINT fk_6aea486d99ae984c FOREIGN KEY (races_id) REFERENCES race (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_6aea486d9e225b24 ON personnage (classes_id)');
        $this->addSql('CREATE INDEX idx_6aea486d99ae984c ON personnage (races_id)');
    }
}
