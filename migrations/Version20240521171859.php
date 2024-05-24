<?php
//
//declare(strict_types=1);
//
//namespace DoctrineMigrations;
//
//use Doctrine\DBAL\Schema\Schema;
//use Doctrine\Migrations\AbstractMigration;
//
///**
// * Auto-generated Migration: Please modify to your needs!
// */
//final class Version20240521171859 extends AbstractMigration
//{
//    public function getDescription(): string
//    {
//        return '';
//    }
//
//    public function up(Schema $schema): void
//    {
//        // this up() migration is auto-generated, please modify it to your needs
//        $this->addSql('CREATE TABLE ratings (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, recipe_id_id INT NOT NULL, rating INT NOT NULL, INDEX IDX_CEB607C99D86650F (user_id_id), INDEX IDX_CEB607C969574A48 (recipe_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
//        $this->addSql('ALTER TABLE ratings ADD CONSTRAINT FK_CEB607C99D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
//        $this->addSql('ALTER TABLE ratings ADD CONSTRAINT FK_CEB607C969574A48 FOREIGN KEY (recipe_id_id) REFERENCES recipes (id)');
//        $this->addSql('ALTER TABLE recipes ADD picture LONGBLOB DEFAULT NULL, DROP picture_path');
//        $this->addSql('ALTER TABLE saved_recipes DROP FOREIGN KEY FK_saved_recipes_recipe_id');
//        $this->addSql('ALTER TABLE saved_recipes DROP FOREIGN KEY FK_saved_recipes_user_id');
//        $this->addSql('DROP INDEX FK_saved_recipes_user_id ON saved_recipes');
//        $this->addSql('DROP INDEX FK_saved_recipes_recipe_id ON saved_recipes');
//        $this->addSql('ALTER TABLE user CHANGE email email VARCHAR(255) NOT NULL, CHANGE password password VARCHAR(255) NOT NULL, CHANGE username username VARCHAR(255) NOT NULL, CHANGE name name VARCHAR(255) NOT NULL, CHANGE surname surname VARCHAR(255) NOT NULL, CHANGE dietPreference dietPreference VARCHAR(255) NOT NULL');
//    }
//
//    public function down(Schema $schema): void
//    {
//        // this down() migration is auto-generated, please modify it to your needs
//        $this->addSql('ALTER TABLE ratings DROP FOREIGN KEY FK_CEB607C99D86650F');
//        $this->addSql('ALTER TABLE ratings DROP FOREIGN KEY FK_CEB607C969574A48');
//        $this->addSql('DROP TABLE ratings');
//        $this->addSql('ALTER TABLE recipes ADD picture_path VARCHAR(255) DEFAULT NULL, DROP picture');
//        $this->addSql('ALTER TABLE saved_recipes ADD CONSTRAINT FK_saved_recipes_recipe_id FOREIGN KEY (recipe_id) REFERENCES recipes (id) ON UPDATE NO ACTION ON DELETE CASCADE');
//        $this->addSql('ALTER TABLE saved_recipes ADD CONSTRAINT FK_saved_recipes_user_id FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE CASCADE');
//        $this->addSql('CREATE INDEX FK_saved_recipes_user_id ON saved_recipes (user_id)');
//        $this->addSql('CREATE INDEX FK_saved_recipes_recipe_id ON saved_recipes (recipe_id)');
//        $this->addSql('ALTER TABLE user CHANGE email email VARCHAR(100) NOT NULL, CHANGE password password VARCHAR(100) NOT NULL, CHANGE username username VARCHAR(100) NOT NULL, CHANGE name name VARCHAR(100) NOT NULL, CHANGE surname surname VARCHAR(100) NOT NULL, CHANGE dietPreference dietPreference INT DEFAULT NULL');
//    }
//}
