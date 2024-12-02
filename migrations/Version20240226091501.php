<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240226091501 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add timestamps to entities and remove created_at and updated_at fields from User and Salary entities.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE timestamps (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', timezone VARCHAR(255) NOT NULL, entity_id INT NOT NULL, entity_class VARCHAR(255) NOT NULL, UNIQUE INDEX unique_entity (entity_id, entity_class), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE salary DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE user DROP created_at, DROP updated_at');
    }

    public function down(Schema $schema): void
    {

        $this->addSql('DROP TABLE timestamps');
        $this->addSql('ALTER TABLE user ADD created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, ADD updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE salary ADD created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, ADD updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
    }
}
