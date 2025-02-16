<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250216121033 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, id_post_id INT DEFAULT NULL, id_user_id INT DEFAULT NULL, contenu LONGTEXT NOT NULL, date_commentaire DATETIME NOT NULL, nmb_like INT NOT NULL, INDEX IDX_67F068BC9514AA5C (id_post_id), INDEX IDX_67F068BC79F37AE5 (id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evenement (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME NOT NULL, lieu VARCHAR(255) NOT NULL, capacite INT NOT NULL, type_evenement VARCHAR(255) NOT NULL, prix DOUBLE PRECISION NOT NULL, organisateur VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, date_creation DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE participation (id INT AUTO_INCREMENT NOT NULL, id_user_id INT DEFAULT NULL, id_evenement_id INT DEFAULT NULL, prix DOUBLE PRECISION NOT NULL, statut VARCHAR(255) NOT NULL, INDEX IDX_AB55E24F79F37AE5 (id_user_id), UNIQUE INDEX UNIQ_AB55E24F2C115A61 (id_evenement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, id_user_id INT NOT NULL, type_post VARCHAR(255) NOT NULL, contenu LONGTEXT NOT NULL, media_url VARCHAR(255) NOT NULL, date_publication DATETIME NOT NULL, tranche_dage VARCHAR(255) NOT NULL, nmb_like INT DEFAULT NULL, INDEX IDX_5A8A6C8D79F37AE5 (id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC9514AA5C FOREIGN KEY (id_post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC79F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24F79F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24F2C115A61 FOREIGN KEY (id_evenement_id) REFERENCES evenement (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D79F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('DROP TABLE participant');
        $this->addSql('ALTER TABLE art ADD foire_id INT NOT NULL, ADD description LONGTEXT NOT NULL, CHANGE id_foire image VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE art ADD CONSTRAINT FK_FC35D6543749BC77 FOREIGN KEY (foire_id) REFERENCES foire (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_FC35D6543749BC77 ON art (foire_id)');
        $this->addSql('ALTER TABLE expedition CHANGE relique_final relique_finale VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE foire ADD rate VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE produit ADD updated_at DATETIME DEFAULT NULL, DROP id_produit, CHANGE description description LONGTEXT DEFAULT NULL, CHANGE prix prix NUMERIC(10, 2) NOT NULL, CHANGE img_url img_url VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD fiscal VARCHAR(255) DEFAULT NULL, CHANGE sexe sexe VARCHAR(10) NOT NULL, CHANGE tel tel VARCHAR(20) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE participant (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC9514AA5C');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC79F37AE5');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24F79F37AE5');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24F2C115A61');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D79F37AE5');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE evenement');
        $this->addSql('DROP TABLE participation');
        $this->addSql('DROP TABLE post');
        $this->addSql('ALTER TABLE art DROP FOREIGN KEY FK_FC35D6543749BC77');
        $this->addSql('DROP INDEX IDX_FC35D6543749BC77 ON art');
        $this->addSql('ALTER TABLE art DROP foire_id, DROP description, CHANGE image id_foire VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE expedition CHANGE relique_finale relique_final VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE foire DROP rate');
        $this->addSql('ALTER TABLE produit ADD id_produit INT NOT NULL, DROP updated_at, CHANGE description description LONGTEXT NOT NULL, CHANGE prix prix DOUBLE PRECISION NOT NULL, CHANGE img_url img_url VARCHAR(255) NOT NULL');
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74 ON user');
        $this->addSql('ALTER TABLE user DROP fiscal, CHANGE sexe sexe VARCHAR(255) NOT NULL, CHANGE tel tel INT NOT NULL');
    }
}
