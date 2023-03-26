<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230326142843 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Category data.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("INSERT INTO category (code) VALUES ('wild_squirrel')");
        $this->addSql("INSERT INTO category (code) VALUES ('shapeshifter_chicken')");
        $this->addSql("INSERT INTO category (code) VALUES ('lol_cat')");
        $this->addSql("INSERT INTO category (code) VALUES ('caribou_avenger')");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("DELETE FROM category where code = 'wild_squirrel'");
        $this->addSql("DELETE FROM category where code = 'shapeshifter_chicken'");
        $this->addSql("DELETE FROM category where code = 'lol_cat'");
        $this->addSql("DELETE FROM category where code = 'caribou_avenger'");
    }
}
