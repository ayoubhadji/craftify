<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250208133919 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE aventurier_expedition (aventurier_id INT NOT NULL, expedition_id INT NOT NULL, INDEX IDX_B6D71EA5EDDC7141 (aventurier_id), INDEX IDX_B6D71EA5576EF81E (expedition_id), PRIMARY KEY(aventurier_id, expedition_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE aventurier_expedition ADD CONSTRAINT FK_B6D71EA5EDDC7141 FOREIGN KEY (aventurier_id) REFERENCES aventurier (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE aventurier_expedition ADD CONSTRAINT FK_B6D71EA5576EF81E FOREIGN KEY (expedition_id) REFERENCES expedition (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE aventurier_expedition DROP FOREIGN KEY FK_B6D71EA5EDDC7141');
        $this->addSql('ALTER TABLE aventurier_expedition DROP FOREIGN KEY FK_B6D71EA5576EF81E');
        $this->addSql('DROP TABLE aventurier_expedition');
    }
}
