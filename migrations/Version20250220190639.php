<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250220190639 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reaction (id INT AUTO_INCREMENT NOT NULL, id_user INT NOT NULL, id_post INT NOT NULL, type VARCHAR(10) NOT NULL, INDEX IDX_A4D707F76B3CA4B (id_user), INDEX IDX_A4D707F7D1AA708F (id_post), UNIQUE INDEX unique_reaction (id_user, id_post), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reaction ADD CONSTRAINT FK_A4D707F76B3CA4B FOREIGN KEY (id_user) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reaction ADD CONSTRAINT FK_A4D707F7D1AA708F FOREIGN KEY (id_post) REFERENCES post (id)');
        $this->addSql('ALTER TABLE commentaire CHANGE id_post_id id_post_id INT NOT NULL, CHANGE id_user_id id_user_id INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reaction DROP FOREIGN KEY FK_A4D707F76B3CA4B');
        $this->addSql('ALTER TABLE reaction DROP FOREIGN KEY FK_A4D707F7D1AA708F');
        $this->addSql('DROP TABLE reaction');
        $this->addSql('ALTER TABLE commentaire CHANGE id_post_id id_post_id INT DEFAULT NULL, CHANGE id_user_id id_user_id INT DEFAULT NULL');
    }
}
