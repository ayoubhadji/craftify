<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250301141126 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE art (id INT AUTO_INCREMENT NOT NULL, foire_id INT NOT NULL, titre VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, image VARCHAR(255) NOT NULL, INDEX IDX_FC35D6543749BC77 (foire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE aventurier (id INT AUTO_INCREMENT NOT NULL, nom_code VARCHAR(255) NOT NULL, quetes_terminees LONGTEXT NOT NULL, artefact_possede LONGTEXT NOT NULL, compagne_creatif VARCHAR(255) NOT NULL, badge_legendaire VARCHAR(255) NOT NULL, signe_distinctif LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE aventurier_expedition (aventurier_id INT NOT NULL, expedition_id INT NOT NULL, INDEX IDX_B6D71EA5EDDC7141 (aventurier_id), INDEX IDX_B6D71EA5576EF81E (expedition_id), PRIMARY KEY(aventurier_id, expedition_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, id_client_id INT DEFAULT NULL, date_commande DATETIME NOT NULL, statut VARCHAR(255) NOT NULL, total DOUBLE PRECISION NOT NULL, INDEX IDX_6EEAA67D99DED506 (id_client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande_produit (commande_id INT NOT NULL, produit_id INT NOT NULL, INDEX IDX_DF1E9E8782EA2E54 (commande_id), INDEX IDX_DF1E9E87F347EFB (produit_id), PRIMARY KEY(commande_id, produit_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, id_post_id INT NOT NULL, id_user_id INT NOT NULL, contenu LONGTEXT NOT NULL, date_commentaire DATETIME NOT NULL, nmb_like INT NOT NULL, INDEX IDX_67F068BC9514AA5C (id_post_id), INDEX IDX_67F068BC79F37AE5 (id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evenement (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME NOT NULL, lieu VARCHAR(255) NOT NULL, capacite INT NOT NULL, type_evenement VARCHAR(255) NOT NULL, prix DOUBLE PRECISION NOT NULL, organisateur VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, date_creation DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE expedition (id INT AUTO_INCREMENT NOT NULL, nom_expedition VARCHAR(255) NOT NULL, univers VARCHAR(100) NOT NULL, cart_tresor_url VARCHAR(255) NOT NULL, quetes_dispo LONGTEXT NOT NULL, objet_magique LONGTEXT NOT NULL, gardien_artisanaux LONGTEXT NOT NULL, duree_mystique VARCHAR(50) NOT NULL, secret_cache LONGTEXT NOT NULL, relique_finale VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE expedition_aventurier (expedition_id INT NOT NULL, aventurier_id INT NOT NULL, INDEX IDX_B01C7179576EF81E (expedition_id), INDEX IDX_B01C7179EDDC7141 (aventurier_id), PRIMARY KEY(expedition_id, aventurier_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE foire (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME NOT NULL, lieu VARCHAR(255) NOT NULL, capacite_max INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', prix DOUBLE PRECISION NOT NULL, rate VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE panier (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, produit_id INT NOT NULL, quantity INT NOT NULL, INDEX IDX_24CC0DF2A76ED395 (user_id), INDEX IDX_24CC0DF2F347EFB (produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE participation (id INT AUTO_INCREMENT NOT NULL, id_user_id INT DEFAULT NULL, id_evenement_id INT DEFAULT NULL, prix DOUBLE PRECISION NOT NULL, statut VARCHAR(255) NOT NULL, INDEX IDX_AB55E24F79F37AE5 (id_user_id), UNIQUE INDEX UNIQ_AB55E24F2C115A61 (id_evenement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, id_user_id INT NOT NULL, type_post VARCHAR(255) NOT NULL, contenu LONGTEXT NOT NULL, media_url VARCHAR(255) DEFAULT NULL, date_publication DATETIME NOT NULL, tranche_dage VARCHAR(255) NOT NULL, nmb_like INT DEFAULT NULL, INDEX IDX_5A8A6C8D79F37AE5 (id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit (id INT AUTO_INCREMENT NOT NULL, artisan_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, prix NUMERIC(10, 2) NOT NULL, stock INT DEFAULT NULL, img_url VARCHAR(255) DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_29A5EC275ED3C7B7 (artisan_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE slider_item (id INT AUTO_INCREMENT NOT NULL, foire_id INT DEFAULT NULL, image_path VARCHAR(255) NOT NULL, alt_text VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, position INT NOT NULL, INDEX IDX_788595CE3749BC77 (foire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, sexe VARCHAR(10) NOT NULL, date_naissance DATETIME NOT NULL, date_join DATETIME NOT NULL, tel VARCHAR(20) NOT NULL, address VARCHAR(255) DEFAULT NULL, fiscal VARCHAR(255) DEFAULT NULL, reset_token VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE art ADD CONSTRAINT FK_FC35D6543749BC77 FOREIGN KEY (foire_id) REFERENCES foire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE aventurier_expedition ADD CONSTRAINT FK_B6D71EA5EDDC7141 FOREIGN KEY (aventurier_id) REFERENCES aventurier (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE aventurier_expedition ADD CONSTRAINT FK_B6D71EA5576EF81E FOREIGN KEY (expedition_id) REFERENCES expedition (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D99DED506 FOREIGN KEY (id_client_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commande_produit ADD CONSTRAINT FK_DF1E9E8782EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commande_produit ADD CONSTRAINT FK_DF1E9E87F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC9514AA5C FOREIGN KEY (id_post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC79F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE expedition_aventurier ADD CONSTRAINT FK_B01C7179576EF81E FOREIGN KEY (expedition_id) REFERENCES expedition (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE expedition_aventurier ADD CONSTRAINT FK_B01C7179EDDC7141 FOREIGN KEY (aventurier_id) REFERENCES aventurier (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF2F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24F79F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24F2C115A61 FOREIGN KEY (id_evenement_id) REFERENCES evenement (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D79F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC275ED3C7B7 FOREIGN KEY (artisan_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE slider_item ADD CONSTRAINT FK_788595CE3749BC77 FOREIGN KEY (foire_id) REFERENCES foire (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE art DROP FOREIGN KEY FK_FC35D6543749BC77');
        $this->addSql('ALTER TABLE aventurier_expedition DROP FOREIGN KEY FK_B6D71EA5EDDC7141');
        $this->addSql('ALTER TABLE aventurier_expedition DROP FOREIGN KEY FK_B6D71EA5576EF81E');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D99DED506');
        $this->addSql('ALTER TABLE commande_produit DROP FOREIGN KEY FK_DF1E9E8782EA2E54');
        $this->addSql('ALTER TABLE commande_produit DROP FOREIGN KEY FK_DF1E9E87F347EFB');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC9514AA5C');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC79F37AE5');
        $this->addSql('ALTER TABLE expedition_aventurier DROP FOREIGN KEY FK_B01C7179576EF81E');
        $this->addSql('ALTER TABLE expedition_aventurier DROP FOREIGN KEY FK_B01C7179EDDC7141');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF2A76ED395');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF2F347EFB');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24F79F37AE5');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24F2C115A61');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D79F37AE5');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC275ED3C7B7');
        $this->addSql('ALTER TABLE slider_item DROP FOREIGN KEY FK_788595CE3749BC77');
        $this->addSql('DROP TABLE art');
        $this->addSql('DROP TABLE aventurier');
        $this->addSql('DROP TABLE aventurier_expedition');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE commande_produit');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE evenement');
        $this->addSql('DROP TABLE expedition');
        $this->addSql('DROP TABLE expedition_aventurier');
        $this->addSql('DROP TABLE foire');
        $this->addSql('DROP TABLE panier');
        $this->addSql('DROP TABLE participation');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE slider_item');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
