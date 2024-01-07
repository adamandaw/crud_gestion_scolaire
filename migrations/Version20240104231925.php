<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240104231925 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        // $this->addSql('ALTER TABLE absence ADD CONSTRAINT FK_765AE0C93D865311 FOREIGN KEY (planning_id) REFERENCES planning (id)');
        // $this->addSql('ALTER TABLE absence RENAME INDEX idx_765ae0c97ecf78b0 TO IDX_765AE0C93D865311');
        // $this->addSql('ALTER TABLE annee_scolaire ADD period_inscription_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        // $this->addSql('ALTER TABLE professeur CHANGE id id INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE absence DROP FOREIGN KEY FK_765AE0C93D865311');
        $this->addSql('ALTER TABLE absence RENAME INDEX idx_765ae0c93d865311 TO IDX_765AE0C97ECF78B0');
        $this->addSql('ALTER TABLE annee_scolaire DROP period_inscription_at');
        $this->addSql('ALTER TABLE professeur CHANGE id id INT AUTO_INCREMENT NOT NULL');
    }
}
