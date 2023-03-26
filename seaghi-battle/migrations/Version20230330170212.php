<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230330170212 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Effect data.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("INSERT INTO effect (code) VALUES ('serious_injury')");
        $this->addSql("INSERT INTO effect (code) VALUES ('badass')");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("DELETE FROM effect where code = 'serious_injury'");
        $this->addSql("DELETE FROM effect where code = 'badass'");
    }
}
