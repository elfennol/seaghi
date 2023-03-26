<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230330170055 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE effect (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, code VARCHAR(50) NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B66091F277153098 ON effect (code)');
        $this->addSql('CREATE TABLE monster (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, current_health INTEGER DEFAULT 0 NOT NULL, max_health INTEGER DEFAULT 0 NOT NULL, defense INTEGER DEFAULT 0 NOT NULL)');
        $this->addSql('CREATE TABLE monster_effect (monster_id INTEGER NOT NULL, effect_id INTEGER NOT NULL, PRIMARY KEY(monster_id, effect_id), CONSTRAINT FK_B0A0DCD1C5FF1223 FOREIGN KEY (monster_id) REFERENCES monster (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_B0A0DCD1F5E9B83B FOREIGN KEY (effect_id) REFERENCES effect (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_B0A0DCD1C5FF1223 ON monster_effect (monster_id)');
        $this->addSql('CREATE INDEX IDX_B0A0DCD1F5E9B83B ON monster_effect (effect_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE effect');
        $this->addSql('DROP TABLE monster');
        $this->addSql('DROP TABLE monster_effect');
    }
}
