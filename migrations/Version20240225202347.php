<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240225202347 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add indexes to user and salary tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE INDEX idx_amount_currency_code ON salary (amount, currency_code)');
        $this->addSql('CREATE INDEX idx_user_email ON user (email)');
        $this->addSql('CREATE INDEX idx_user_registration_date ON user (employment_date)');
        $this->addSql('ALTER TABLE user RENAME INDEX uniq_8d93d649e7927c74 TO uniq_user_email');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX idx_user_email ON user');
        $this->addSql('DROP INDEX idx_user_registration_date ON user');
        $this->addSql('ALTER TABLE user RENAME INDEX uniq_user_email TO UNIQ_8D93D649E7927C74');
        $this->addSql('DROP INDEX idx_amount_currency_code ON salary');
    }
}
