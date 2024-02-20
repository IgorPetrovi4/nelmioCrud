<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240222121417 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Change amount type to decimal in salary table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE salary CHANGE amount amount NUMERIC(10, 2) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE salary CHANGE amount amount BIGINT NOT NULL');
    }
}
