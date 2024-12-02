<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240220205335 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates user and salary tables and adds some columns to user table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE salary (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, amount BIGINT NOT NULL, payment_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_9413BB71A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE salary ADD CONSTRAINT FK_9413BB71A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD name VARCHAR(255) DEFAULT NULL, ADD surname VARCHAR(255) DEFAULT NULL, ADD employment_date DATE DEFAULT NULL, ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE salary DROP FOREIGN KEY FK_9413BB71A76ED395');
        $this->addSql('DROP TABLE salary');
        $this->addSql('ALTER TABLE user DROP name, DROP surname, DROP employment_date, DROP created_at, DROP updated_at');
    }
}
