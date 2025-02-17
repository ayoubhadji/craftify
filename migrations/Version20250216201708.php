<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250216201708 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE slider_item ADD foire_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE slider_item ADD CONSTRAINT FK_788595CE3749BC77 FOREIGN KEY (foire_id) REFERENCES foire (id)');
        $this->addSql('CREATE INDEX IDX_788595CE3749BC77 ON slider_item (foire_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE slider_item DROP FOREIGN KEY FK_788595CE3749BC77');
        $this->addSql('DROP INDEX IDX_788595CE3749BC77 ON slider_item');
        $this->addSql('ALTER TABLE slider_item DROP foire_id');
    }
}
