<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260926153717 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id BLOB NOT NULL, code VARCHAR(50) NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_64C19C177153098 ON category (code)');
        $this->addSql('CREATE TABLE monster (id BLOB NOT NULL, level INTEGER NOT NULL, price INTEGER NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, available BOOLEAN DEFAULT 1 NOT NULL, sick BOOLEAN DEFAULT 0 NOT NULL, category_id BLOB NOT NULL, PRIMARY KEY (id), CONSTRAINT FK_245EC6F412469DE2 FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_245EC6F412469DE2 ON monster (category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE monster');
    }
}
