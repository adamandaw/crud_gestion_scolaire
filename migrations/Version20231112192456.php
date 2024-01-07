<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231112192456 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        // $this->addSql('CREATE TABLE classe_module (classe_id INT NOT NULL, module_id INT NOT NULL, INDEX IDX_246FE9E28F5EA509 (classe_id), INDEX IDX_246FE9E2AFC2B591 (module_id), PRIMARY KEY(classe_id, module_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        // $this->addSql('ALTER TABLE classe_module ADD CONSTRAINT FK_246FE9E28F5EA509 FOREIGN KEY (classe_id) REFERENCES classe (id) ON DELETE CASCADE');
        // $this->addSql('ALTER TABLE classe_module ADD CONSTRAINT FK_246FE9E2AFC2B591 FOREIGN KEY (module_id) REFERENCES module (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE classe_module DROP FOREIGN KEY FK_246FE9E28F5EA509');
        $this->addSql('ALTER TABLE classe_module DROP FOREIGN KEY FK_246FE9E2AFC2B591');
        $this->addSql('DROP TABLE classe_module');
    }
}
