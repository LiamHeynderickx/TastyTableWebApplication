<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240526153547 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE INDEX IDX_5F9E962A9D86650F ON comments (user_id_id)');
        $this->addSql('CREATE INDEX IDX_5F9E962A69574A48 ON comments (recipe_id_id)');
//        $this->addSql('ALTER TABLE followers RENAME INDEX idx_8408fda79d86650f TO IDX_8408FDA7A76ED395');
//        $this->addSql('ALTER TABLE followers RENAME INDEX idx_8408fda7e8ddda11 TO IDX_8408FDA7AC24F853');
//        $this->addSql('ALTER TABLE following RENAME INDEX idx_71bf8de39d86650f TO IDX_71BF8DE3A76ED395');
//        $this->addSql('ALTER TABLE following RENAME INDEX idx_71bf8de33cf8336f TO IDX_71BF8DE31816E3A3');
//        $this->addSql('ALTER TABLE saved_recipes DROP FOREIGN KEY fk_user');
//        $this->addSql('ALTER TABLE saved_recipes ADD CONSTRAINT FK_29682D22A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
//        $this->addSql('ALTER TABLE saved_recipes RENAME INDEX fk_user TO IDX_29682D22A76ED395');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A9D86650F');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A69574A48');
        $this->addSql('DROP INDEX IDX_5F9E962A9D86650F ON comments');
        $this->addSql('DROP INDEX IDX_5F9E962A69574A48 ON comments');
        $this->addSql('ALTER TABLE comments ADD user_id INT NOT NULL, ADD recipe_id INT NOT NULL, DROP user_id_id, DROP recipe_id_id');
//        $this->addSql('ALTER TABLE saved_recipes DROP FOREIGN KEY FK_29682D22A76ED395');
//        $this->addSql('ALTER TABLE saved_recipes ADD CONSTRAINT fk_user FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE CASCADE');
//        $this->addSql('ALTER TABLE saved_recipes RENAME INDEX idx_29682d22a76ed395 TO fk_user');
//        $this->addSql('ALTER TABLE following RENAME INDEX idx_71bf8de3a76ed395 TO IDX_71BF8DE39D86650F');
//        $this->addSql('ALTER TABLE following RENAME INDEX idx_71bf8de31816e3a3 TO IDX_71BF8DE33CF8336F');
//        $this->addSql('ALTER TABLE followers RENAME INDEX idx_8408fda7ac24f853 TO IDX_8408FDA7E8DDDA11');
//        $this->addSql('ALTER TABLE followers RENAME INDEX idx_8408fda7a76ed395 TO IDX_8408FDA79D86650F');
    }
}
