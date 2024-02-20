<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240225195051 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add currency code to salary table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE salary ADD currency_code VARCHAR(3) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE salary DROP currency_code');
    }
}
