<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241029173230 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE voiture DROP FOREIGN KEY FK_E9E2810F527C7FEB');
        $this->addSql('DROP INDEX IDX_E9E2810F527C7FEB ON voiture');
        $this->addSql('ALTER TABLE voiture DROP images_id_id, CHANGE coverImage cover_image VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE voiture ADD images_id_id INT DEFAULT NULL, CHANGE cover_image coverImage VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE voiture ADD CONSTRAINT FK_E9E2810F527C7FEB FOREIGN KEY (images_id_id) REFERENCES image (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_E9E2810F527C7FEB ON voiture (images_id_id)');
    }
}
