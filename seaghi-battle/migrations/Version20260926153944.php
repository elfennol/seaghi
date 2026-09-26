<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Symfony\Component\Uid\Uuid;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260926153944 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("INSERT INTO effect (id, code) VALUES (:id, 'serious_injury')", ['id' => Uuid::v7()->toBinary()]);
        $this->addSql("INSERT INTO effect (id, code) VALUES (:id, 'badass')", ['id' => Uuid::v7()->toBinary()]);
    }

    public function down(Schema $schema): void
    {
        $this->addSql("DELETE FROM effect where code = 'serious_injury'");
        $this->addSql("DELETE FROM effect where code = 'badass'");
    }
}
