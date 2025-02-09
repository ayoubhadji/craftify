<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250208160836 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE art ADD id_foire_id INT DEFAULT NULL, ADD description LONGTEXT NOT NULL, CHANGE id_foire image VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE art ADD CONSTRAINT FK_FC35D654679250E2 FOREIGN KEY (id_foire_id) REFERENCES foire (id)');
        $this->addSql('CREATE INDEX IDX_FC35D654679250E2 ON art (id_foire_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE art DROP FOREIGN KEY FK_FC35D654679250E2');
        $this->addSql('DROP INDEX IDX_FC35D654679250E2 ON art');
        $this->addSql('ALTER TABLE art DROP id_foire_id, DROP description, CHANGE image id_foire VARCHAR(255) NOT NULL');
    }
}
