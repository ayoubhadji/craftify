<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250224024843 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE aventuriers_expeditions (aventurier_id INT NOT NULL, expedition_id INT NOT NULL, INDEX IDX_B30043E6EDDC7141 (aventurier_id), INDEX IDX_B30043E6576EF81E (expedition_id), PRIMARY KEY(aventurier_id, expedition_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE aventuriers_expeditions ADD CONSTRAINT FK_B30043E6EDDC7141 FOREIGN KEY (aventurier_id) REFERENCES aventurier (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE aventuriers_expeditions ADD CONSTRAINT FK_B30043E6576EF81E FOREIGN KEY (expedition_id) REFERENCES expedition (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY report_ibfk_1');
        $this->addSql('DROP TABLE photo');
        $this->addSql('DROP TABLE report');
        $this->addSql('DROP TABLE task');
        $this->addSql('ALTER TABLE aventurier CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE nom nom VARCHAR(255) NOT NULL, CHANGE prenom prenom VARCHAR(255) NOT NULL, CHANGE email email VARCHAR(255) NOT NULL, CHANGE date_inscription date_inscription DATETIME NOT NULL, CHANGE statut statut VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE expedition DROP FOREIGN KEY expedition_ibfk_1');
        $this->addSql('ALTER TABLE expedition DROP FOREIGN KEY expedition_ibfk_1');
        $this->addSql('ALTER TABLE expedition CHANGE titre titre VARCHAR(255) NOT NULL, CHANGE objectif objectif LONGTEXT NOT NULL, CHANGE date_debut date_debut DATE NOT NULL, CHANGE date_fin date_fin DATE NOT NULL, CHANGE statut statut VARCHAR(50) NOT NULL, CHANGE distance_parcourue distance_parcourue DOUBLE PRECISION NOT NULL, CHANGE aventurier_id aventurier_id INT NOT NULL, CHANGE rapport rapport LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE expedition ADD CONSTRAINT FK_692907EEDDC7141 FOREIGN KEY (aventurier_id) REFERENCES aventurier (id)');
        $this->addSql('DROP INDEX aventurier_id ON expedition');
        $this->addSql('CREATE INDEX IDX_692907EEDDC7141 ON expedition (aventurier_id)');
        $this->addSql('ALTER TABLE expedition ADD CONSTRAINT expedition_ibfk_1 FOREIGN KEY (aventurier_id) REFERENCES aventurier (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE foire CHANGE created_at created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE messenger_messages CHANGE created_at created_at DATETIME NOT NULL, CHANGE available_at available_at DATETIME NOT NULL, CHANGE delivered_at delivered_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE photo (id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL, url TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, expedition_id INT DEFAULT NULL, date_prise DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE report (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, contenu TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, expedition_id INT DEFAULT NULL, date_creation DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, liens_photos JSON DEFAULT NULL, INDEX expedition_id (expedition_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE task (id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL, titre VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, description TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, date_limite DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, statut VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, avancement INT DEFAULT NULL, expedition_id INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT report_ibfk_1 FOREIGN KEY (expedition_id) REFERENCES expedition (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE aventuriers_expeditions DROP FOREIGN KEY FK_B30043E6EDDC7141');
        $this->addSql('ALTER TABLE aventuriers_expeditions DROP FOREIGN KEY FK_B30043E6576EF81E');
        $this->addSql('DROP TABLE aventuriers_expeditions');
        $this->addSql('ALTER TABLE aventurier CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL, CHANGE nom nom VARCHAR(100) DEFAULT NULL, CHANGE prenom prenom VARCHAR(100) DEFAULT NULL, CHANGE email email VARCHAR(100) DEFAULT NULL, CHANGE date_inscription date_inscription DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE statut statut VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE expedition DROP FOREIGN KEY FK_692907EEDDC7141');
        $this->addSql('ALTER TABLE expedition DROP FOREIGN KEY FK_692907EEDDC7141');
        $this->addSql('ALTER TABLE expedition CHANGE titre titre VARCHAR(255) DEFAULT NULL, CHANGE objectif objectif TEXT DEFAULT NULL, CHANGE date_debut date_debut DATE DEFAULT NULL, CHANGE date_fin date_fin DATE DEFAULT NULL, CHANGE statut statut VARCHAR(50) DEFAULT NULL, CHANGE distance_parcourue distance_parcourue NUMERIC(10, 2) DEFAULT NULL, CHANGE rapport rapport TEXT DEFAULT NULL, CHANGE aventurier_id aventurier_id INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE expedition ADD CONSTRAINT expedition_ibfk_1 FOREIGN KEY (aventurier_id) REFERENCES aventurier (id) ON DELETE CASCADE');
        $this->addSql('DROP INDEX idx_692907eeddc7141 ON expedition');
        $this->addSql('CREATE INDEX aventurier_id ON expedition (aventurier_id)');
        $this->addSql('ALTER TABLE expedition ADD CONSTRAINT FK_692907EEDDC7141 FOREIGN KEY (aventurier_id) REFERENCES aventurier (id)');
        $this->addSql('ALTER TABLE foire CHANGE created_at created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE messenger_messages CHANGE created_at created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE available_at available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE delivered_at delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }
}
