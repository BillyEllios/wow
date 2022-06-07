<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220607080518 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE classe_race (classe_id INT NOT NULL, race_id INT NOT NULL, PRIMARY KEY(classe_id, race_id))');
        $this->addSql('CREATE INDEX IDX_C47CCE498F5EA509 ON classe_race (classe_id)');
        $this->addSql('CREATE INDEX IDX_C47CCE496E59D40D ON classe_race (race_id)');
        $this->addSql('ALTER TABLE classe_race ADD CONSTRAINT FK_C47CCE498F5EA509 FOREIGN KEY (classe_id) REFERENCES classe (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE classe_race ADD CONSTRAINT FK_C47CCE496E59D40D FOREIGN KEY (race_id) REFERENCES race (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE classe_race');
    }
}
