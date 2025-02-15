<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250215174306 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE art (id INT AUTO_INCREMENT NOT NULL, foire_id INT NOT NULL, titre VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, image VARCHAR(255) NOT NULL, INDEX IDX_FC35D6543749BC77 (foire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE aventurier_expedition (aventurier_id INT NOT NULL, expedition_id INT NOT NULL, INDEX IDX_B6D71EA5EDDC7141 (aventurier_id), INDEX IDX_B6D71EA5576EF81E (expedition_id), PRIMARY KEY(aventurier_id, expedition_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, id_post_id INT DEFAULT NULL, id_user_id INT DEFAULT NULL, contenu LONGTEXT NOT NULL, date_commentaire DATETIME NOT NULL, nmb_like INT NOT NULL, INDEX IDX_67F068BC9514AA5C (id_post_id), INDEX IDX_67F068BC79F37AE5 (id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evenement (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME NOT NULL, lieu VARCHAR(255) NOT NULL, capacite INT NOT NULL, type_evenement VARCHAR(255) NOT NULL, prix DOUBLE PRECISION NOT NULL, organisateur VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, date_creation DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE foire (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME NOT NULL, lieu VARCHAR(255) NOT NULL, capacite_max INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', prix DOUBLE PRECISION NOT NULL, rate VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE participation (id INT AUTO_INCREMENT NOT NULL, id_user_id INT DEFAULT NULL, id_evenement_id INT DEFAULT NULL, prix DOUBLE PRECISION NOT NULL, statut VARCHAR(255) NOT NULL, INDEX IDX_AB55E24F79F37AE5 (id_user_id), UNIQUE INDEX UNIQ_AB55E24F2C115A61 (id_evenement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, id_user_id INT NOT NULL, type_post VARCHAR(255) NOT NULL, contenu LONGTEXT NOT NULL, media_url VARCHAR(255) NOT NULL, date_publication DATETIME NOT NULL, tranche_dage VARCHAR(255) NOT NULL, nmb_like INT DEFAULT NULL, INDEX IDX_5A8A6C8D79F37AE5 (id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, sexe VARCHAR(10) NOT NULL, date_naissance DATETIME NOT NULL, date_join DATETIME NOT NULL, tel VARCHAR(20) NOT NULL, address VARCHAR(255) DEFAULT NULL, fiscal VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE art ADD CONSTRAINT FK_FC35D6543749BC77 FOREIGN KEY (foire_id) REFERENCES foire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE aventurier_expedition ADD CONSTRAINT FK_B6D71EA5EDDC7141 FOREIGN KEY (aventurier_id) REFERENCES aventurier (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE aventurier_expedition ADD CONSTRAINT FK_B6D71EA5576EF81E FOREIGN KEY (expedition_id) REFERENCES expedition (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC9514AA5C FOREIGN KEY (id_post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC79F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24F79F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24F2C115A61 FOREIGN KEY (id_evenement_id) REFERENCES evenement (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D79F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commande ADD id_client_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D99DED506 FOREIGN KEY (id_client_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_6EEAA67D99DED506 ON commande (id_client_id)');
        $this->addSql('ALTER TABLE produit ADD id_artisan_id INT DEFAULT NULL, ADD updated_at DATETIME DEFAULT NULL, DROP id_produit, CHANGE description description LONGTEXT DEFAULT NULL, CHANGE prix prix NUMERIC(10, 2) NOT NULL, CHANGE img_url img_url VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27FB594760 FOREIGN KEY (id_artisan_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_29A5EC27FB594760 ON produit (id_artisan_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D99DED506');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27FB594760');
        $this->addSql('ALTER TABLE art DROP FOREIGN KEY FK_FC35D6543749BC77');
        $this->addSql('ALTER TABLE aventurier_expedition DROP FOREIGN KEY FK_B6D71EA5EDDC7141');
        $this->addSql('ALTER TABLE aventurier_expedition DROP FOREIGN KEY FK_B6D71EA5576EF81E');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC9514AA5C');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC79F37AE5');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24F79F37AE5');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24F2C115A61');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D79F37AE5');
        $this->addSql('DROP TABLE art');
        $this->addSql('DROP TABLE aventurier_expedition');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE evenement');
        $this->addSql('DROP TABLE foire');
        $this->addSql('DROP TABLE participation');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP INDEX IDX_6EEAA67D99DED506 ON commande');
        $this->addSql('ALTER TABLE commande DROP id_client_id');
        $this->addSql('DROP INDEX IDX_29A5EC27FB594760 ON produit');
        $this->addSql('ALTER TABLE produit ADD id_produit INT NOT NULL, DROP id_artisan_id, DROP updated_at, CHANGE description description LONGTEXT NOT NULL, CHANGE prix prix DOUBLE PRECISION NOT NULL, CHANGE img_url img_url VARCHAR(255) NOT NULL');
    }
}
