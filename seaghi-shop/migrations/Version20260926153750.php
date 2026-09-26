<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Symfony\Component\Uid\Uuid;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260926153750 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("INSERT INTO category (id, code) VALUES (:id, 'wild_squirrel')", ['id' => Uuid::v7()->toBinary()]);
        $this->addSql("INSERT INTO category (id, code) VALUES (:id, 'shapeshifter_chicken')", ['id' => Uuid::v7()->toBinary()]);
        $this->addSql("INSERT INTO category (id, code) VALUES (:id, 'lol_cat')", ['id' => Uuid::v7()->toBinary()]);
        $this->addSql("INSERT INTO category (id, code) VALUES (:id, 'caribou_avenger')", ['id' => Uuid::v7()->toBinary()]);
    }

    public function down(Schema $schema): void
    {
        $this->addSql("DELETE FROM category where code = 'wild_squirrel'");
        $this->addSql("DELETE FROM category where code = 'shapeshifter_chicken'");
        $this->addSql("DELETE FROM category where code = 'lol_cat'");
        $this->addSql("DELETE FROM category where code = 'caribou_avenger'");
    }
}
