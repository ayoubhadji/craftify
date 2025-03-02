<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250302151240 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commande_produit (commande_id INT NOT NULL, produit_id INT NOT NULL, INDEX IDX_DF1E9E8782EA2E54 (commande_id), INDEX IDX_DF1E9E87F347EFB (produit_id), PRIMARY KEY(commande_id, produit_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE expedition_aventurier (expedition_id INT NOT NULL, aventurier_id INT NOT NULL, INDEX IDX_B01C7179576EF81E (expedition_id), INDEX IDX_B01C7179EDDC7141 (aventurier_id), PRIMARY KEY(expedition_id, aventurier_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE panier (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, produit_id INT NOT NULL, quantity INT NOT NULL, INDEX IDX_24CC0DF2A76ED395 (user_id), INDEX IDX_24CC0DF2F347EFB (produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE slider_item (id INT AUTO_INCREMENT NOT NULL, foire_id INT DEFAULT NULL, image_path VARCHAR(255) NOT NULL, alt_text VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, position INT NOT NULL, INDEX IDX_788595CE3749BC77 (foire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commande_produit ADD CONSTRAINT FK_DF1E9E8782EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commande_produit ADD CONSTRAINT FK_DF1E9E87F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE expedition_aventurier ADD CONSTRAINT FK_B01C7179576EF81E FOREIGN KEY (expedition_id) REFERENCES expedition (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE expedition_aventurier ADD CONSTRAINT FK_B01C7179EDDC7141 FOREIGN KEY (aventurier_id) REFERENCES aventurier (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF2F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE slider_item ADD CONSTRAINT FK_788595CE3749BC77 FOREIGN KEY (foire_id) REFERENCES foire (id)');
        $this->addSql('ALTER TABLE commentaire CHANGE id_post_id id_post_id INT NOT NULL, CHANGE id_user_id id_user_id INT NOT NULL');
        $this->addSql('ALTER TABLE expedition CHANGE relique_final relique_finale VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE post CHANGE media_url media_url VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27FB594760');
        $this->addSql('DROP INDEX IDX_29A5EC27FB594760 ON produit');
        $this->addSql('ALTER TABLE produit CHANGE id_artisan_id artisan_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC275ED3C7B7 FOREIGN KEY (artisan_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_29A5EC275ED3C7B7 ON produit (artisan_id)');
        $this->addSql('ALTER TABLE user ADD reset_token VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande_produit DROP FOREIGN KEY FK_DF1E9E8782EA2E54');
        $this->addSql('ALTER TABLE commande_produit DROP FOREIGN KEY FK_DF1E9E87F347EFB');
        $this->addSql('ALTER TABLE expedition_aventurier DROP FOREIGN KEY FK_B01C7179576EF81E');
        $this->addSql('ALTER TABLE expedition_aventurier DROP FOREIGN KEY FK_B01C7179EDDC7141');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF2A76ED395');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF2F347EFB');
        $this->addSql('ALTER TABLE slider_item DROP FOREIGN KEY FK_788595CE3749BC77');
        $this->addSql('DROP TABLE commande_produit');
        $this->addSql('DROP TABLE expedition_aventurier');
        $this->addSql('DROP TABLE panier');
        $this->addSql('DROP TABLE slider_item');
        $this->addSql('ALTER TABLE commentaire CHANGE id_post_id id_post_id INT DEFAULT NULL, CHANGE id_user_id id_user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE expedition CHANGE relique_finale relique_final VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE post CHANGE media_url media_url VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC275ED3C7B7');
        $this->addSql('DROP INDEX IDX_29A5EC275ED3C7B7 ON produit');
        $this->addSql('ALTER TABLE produit CHANGE artisan_id id_artisan_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27FB594760 FOREIGN KEY (id_artisan_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_29A5EC27FB594760 ON produit (id_artisan_id)');
        $this->addSql('ALTER TABLE user DROP reset_token');
    }
}
