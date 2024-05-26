<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240526130334 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962AE85F12B8');
        $this->addSql('DROP INDEX IDX_5F9E962AE85F12B8 ON comments');
        $this->addSql('ALTER TABLE comments CHANGE post_id_id recipe_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A69574A48 FOREIGN KEY (recipe_id_id) REFERENCES recipes (id)');
        $this->addSql('CREATE INDEX IDX_5F9E962A69574A48 ON comments (recipe_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A69574A48');
        $this->addSql('DROP INDEX IDX_5F9E962A69574A48 ON comments');
        $this->addSql('ALTER TABLE comments CHANGE recipe_id_id post_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AE85F12B8 FOREIGN KEY (post_id_id) REFERENCES posts (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_5F9E962AE85F12B8 ON comments (post_id_id)');
    }
}
