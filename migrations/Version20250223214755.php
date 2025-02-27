<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250223214755 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE expedition_aventurier (expedition_id INT NOT NULL, aventurier_id INT NOT NULL, INDEX IDX_B01C7179576EF81E (expedition_id), INDEX IDX_B01C7179EDDC7141 (aventurier_id), PRIMARY KEY(expedition_id, aventurier_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE slider_item (id INT AUTO_INCREMENT NOT NULL, foire_id INT DEFAULT NULL, image_path VARCHAR(255) NOT NULL, alt_text VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, position INT NOT NULL, INDEX IDX_788595CE3749BC77 (foire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE expedition_aventurier ADD CONSTRAINT FK_B01C7179576EF81E FOREIGN KEY (expedition_id) REFERENCES expedition (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE expedition_aventurier ADD CONSTRAINT FK_B01C7179EDDC7141 FOREIGN KEY (aventurier_id) REFERENCES aventurier (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE slider_item ADD CONSTRAINT FK_788595CE3749BC77 FOREIGN KEY (foire_id) REFERENCES foire (id)');
        $this->addSql('ALTER TABLE commentaire CHANGE id_post_id id_post_id INT NOT NULL, CHANGE id_user_id id_user_id INT NOT NULL');
        $this->addSql('ALTER TABLE expedition CHANGE relique_final relique_finale VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE post CHANGE media_url media_url VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD reset_token VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE expedition_aventurier DROP FOREIGN KEY FK_B01C7179576EF81E');
        $this->addSql('ALTER TABLE expedition_aventurier DROP FOREIGN KEY FK_B01C7179EDDC7141');
        $this->addSql('ALTER TABLE slider_item DROP FOREIGN KEY FK_788595CE3749BC77');
        $this->addSql('DROP TABLE expedition_aventurier');
        $this->addSql('DROP TABLE slider_item');
        $this->addSql('ALTER TABLE commentaire CHANGE id_post_id id_post_id INT DEFAULT NULL, CHANGE id_user_id id_user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE expedition CHANGE relique_finale relique_final VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE post CHANGE media_url media_url VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user DROP reset_token');
    }
}
