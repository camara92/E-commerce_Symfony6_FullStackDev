<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221226115700 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categories CHANGE category_order category_order INT  NULL');
        $this->addSql('ALTER TABLE users ADD reset_token VARCHAR(255) NOT NULL, CHANGE is_verified is_verified TINYINT(1)  NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categories CHANGE category_order category_order INT DEFAULT NULL');
        $this->addSql('ALTER TABLE users DROP reset_token, CHANGE is_verified is_verified TINYINT(1) DEFAULT NULL');
    }
}
