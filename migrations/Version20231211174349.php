<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231211174349 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        // $this->addSql('CREATE TABLE cours (id INT AUTO_INCREMENT NOT NULL, professeur_id INT NOT NULL, module_id INT NOT NULL, annee_scolaire_id INT NOT NULL, semestre_id INT NOT NULL, nbr_heure_global INT NOT NULL, nbr_heure_planifier INT NOT NULL, nbr_heure_effectuer INT NOT NULL, INDEX IDX_FDCA8C9CBAB22EE9 (professeur_id), INDEX IDX_FDCA8C9CAFC2B591 (module_id), INDEX IDX_FDCA8C9C9331C741 (annee_scolaire_id), INDEX IDX_FDCA8C9C5577AFDB (semestre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        // $this->addSql('CREATE TABLE participation (id INT AUTO_INCREMENT NOT NULL, cour_id INT NOT NULL, classe_id INT NOT NULL, INDEX IDX_AB55E24FB7942F03 (cour_id), INDEX IDX_AB55E24F8F5EA509 (classe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        // $this->addSql('CREATE TABLE planning (id INT AUTO_INCREMENT NOT NULL, cours_id INT NOT NULL, create_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', begin_at TIME NOT NULL COMMENT \'(DC2Type:time_immutable)\', end_at TIME NOT NULL COMMENT \'(DC2Type:time_immutable)\', is_archived TINYINT(1) NOT NULL, state TINYINT(1) NOT NULL, INDEX IDX_D499BFF67ECF78B0 (cours_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        // $this->addSql('CREATE TABLE semestre (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(40) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        // $this->addSql('ALTER TABLE cours ADD CONSTRAINT FK_FDCA8C9CBAB22EE9 FOREIGN KEY (professeur_id) REFERENCES professeur (id)');
        // $this->addSql('ALTER TABLE cours ADD CONSTRAINT FK_FDCA8C9CAFC2B591 FOREIGN KEY (module_id) REFERENCES module (id)');
        // $this->addSql('ALTER TABLE cours ADD CONSTRAINT FK_FDCA8C9C9331C741 FOREIGN KEY (annee_scolaire_id) REFERENCES annee_scolaire (id)');
        // $this->addSql('ALTER TABLE cours ADD CONSTRAINT FK_FDCA8C9C5577AFDB FOREIGN KEY (semestre_id) REFERENCES semestre (id)');
        // $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24FB7942F03 FOREIGN KEY (cour_id) REFERENCES cours (id)');
        // $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24F8F5EA509 FOREIGN KEY (classe_id) REFERENCES classe (id)');
        // $this->addSql('ALTER TABLE planning ADD CONSTRAINT FK_D499BFF67ECF78B0 FOREIGN KEY (cours_id) REFERENCES cours (id)');
        // $this->addSql('ALTER TABLE professeur CHANGE id id INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cours DROP FOREIGN KEY FK_FDCA8C9CBAB22EE9');
        $this->addSql('ALTER TABLE cours DROP FOREIGN KEY FK_FDCA8C9CAFC2B591');
        $this->addSql('ALTER TABLE cours DROP FOREIGN KEY FK_FDCA8C9C9331C741');
        $this->addSql('ALTER TABLE cours DROP FOREIGN KEY FK_FDCA8C9C5577AFDB');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24FB7942F03');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24F8F5EA509');
        $this->addSql('ALTER TABLE planning DROP FOREIGN KEY FK_D499BFF67ECF78B0');
        $this->addSql('DROP TABLE cours');
        $this->addSql('DROP TABLE participation');
        $this->addSql('DROP TABLE planning');
        $this->addSql('DROP TABLE semestre');
        $this->addSql('ALTER TABLE professeur CHANGE id id INT AUTO_INCREMENT NOT NULL');
    }
}
