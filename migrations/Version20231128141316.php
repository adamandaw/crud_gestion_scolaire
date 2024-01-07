<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231128141316 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        // $this->addSql('CREATE TABLE re_inscription (id INT AUTO_INCREMENT NOT NULL, etudiant_id INT DEFAULT NULL, classe_id INT NOT NULL, annee_scolaire_id INT NOT NULL, create_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', montant DOUBLE PRECISION NOT NULL, is_archived TINYINT(1) NOT NULL, INDEX IDX_B29EE525DDEAB1A3 (etudiant_id), INDEX IDX_B29EE5258F5EA509 (classe_id), INDEX IDX_B29EE5259331C741 (annee_scolaire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        // $this->addSql('ALTER TABLE re_inscription ADD CONSTRAINT FK_B29EE525DDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES etudiant (id)');
        // $this->addSql('ALTER TABLE re_inscription ADD CONSTRAINT FK_B29EE5258F5EA509 FOREIGN KEY (classe_id) REFERENCES classe (id)');
        // $this->addSql('ALTER TABLE re_inscription ADD CONSTRAINT FK_B29EE5259331C741 FOREIGN KEY (annee_scolaire_id) REFERENCES annee_scolaire (id)');
        // $this->addSql('ALTER TABLE inscription CHANGE etudiant_id etudiant_id INT NOT NULL');
        // $this->addSql('ALTER TABLE professeur CHANGE id id INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE re_inscription DROP FOREIGN KEY FK_B29EE525DDEAB1A3');
        $this->addSql('ALTER TABLE re_inscription DROP FOREIGN KEY FK_B29EE5258F5EA509');
        $this->addSql('ALTER TABLE re_inscription DROP FOREIGN KEY FK_B29EE5259331C741');
        $this->addSql('DROP TABLE re_inscription');
        $this->addSql('ALTER TABLE inscription CHANGE etudiant_id etudiant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE professeur CHANGE id id INT AUTO_INCREMENT NOT NULL');
    }
}
