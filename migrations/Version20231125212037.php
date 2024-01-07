<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231125212037 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        // $this->addSql('CREATE TABLE attacher_de_classe (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        // $this->addSql('CREATE TABLE responsable_programme (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        // $this->addSql('ALTER TABLE attacher_de_classe ADD CONSTRAINT FK_1C4EE7BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        // $this->addSql('ALTER TABLE responsable_programme ADD CONSTRAINT FK_C09AE8E9BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        // $this->addSql('ALTER TABLE professeur CHANGE id id INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE attacher_de_classe DROP FOREIGN KEY FK_1C4EE7BF396750');
        $this->addSql('ALTER TABLE responsable_programme DROP FOREIGN KEY FK_C09AE8E9BF396750');
        $this->addSql('DROP TABLE attacher_de_classe');
        $this->addSql('DROP TABLE responsable_programme');
        $this->addSql('ALTER TABLE professeur CHANGE id id INT AUTO_INCREMENT NOT NULL');
    }
}
