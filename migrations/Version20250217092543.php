<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250217092543 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE slider_item (id INT AUTO_INCREMENT NOT NULL, foire_id INT DEFAULT NULL, image_path VARCHAR(255) NOT NULL, alt_text VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, position INT NOT NULL, INDEX IDX_788595CE3749BC77 (foire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE slider_item ADD CONSTRAINT FK_788595CE3749BC77 FOREIGN KEY (foire_id) REFERENCES foire (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE slider_item DROP FOREIGN KEY FK_788595CE3749BC77');
        $this->addSql('DROP TABLE slider_item');
    }
}
