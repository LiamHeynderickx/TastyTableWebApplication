<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240518195014 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
//        $this->addSql('CREATE TABLE comments (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, post_id_id INT NOT NULL, comment VARCHAR(255) NOT NULL, INDEX IDX_5F9E962A9D86650F (user_id_id), INDEX IDX_5F9E962AE85F12B8 (post_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
//        $this->addSql('CREATE TABLE followers (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, follower_id_id INT NOT NULL, INDEX IDX_8408FDA79D86650F (user_id_id), INDEX IDX_8408FDA7E8DDDA11 (follower_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
//        $this->addSql('CREATE TABLE following (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, following_id_id INT NOT NULL, INDEX IDX_71BF8DE39D86650F (user_id_id), INDEX IDX_71BF8DE33CF8336F (following_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
//        $this->addSql('CREATE TABLE posts (id INT AUTO_INCREMENT NOT NULL, creator_id_id INT NOT NULL, recipe_id_id INT DEFAULT NULL, INDEX IDX_885DBAFAF05788E9 (creator_id_id), INDEX IDX_885DBAFA69574A48 (recipe_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
//        $this->addSql('CREATE TABLE recipes (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, recipe_name VARCHAR(100) NOT NULL, recipe_description VARCHAR(255) DEFAULT NULL, picture LONGBLOB DEFAULT NULL, cost INT NOT NULL, ingredients JSON NOT NULL, time INT NOT NULL, calories DOUBLE PRECISION DEFAULT NULL, fat DOUBLE PRECISION DEFAULT NULL, carbs DOUBLE PRECISION DEFAULT NULL, protein DOUBLE PRECISION DEFAULT NULL, servings INT NOT NULL, diet VARCHAR(100) NOT NULL, type VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
//        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
//        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AE85F12B8 FOREIGN KEY (post_id_id) REFERENCES posts (id)');
//        $this->addSql('ALTER TABLE followers ADD CONSTRAINT FK_8408FDA79D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
//        $this->addSql('ALTER TABLE followers ADD CONSTRAINT FK_8408FDA7E8DDDA11 FOREIGN KEY (follower_id_id) REFERENCES user (id)');
//        $this->addSql('ALTER TABLE following ADD CONSTRAINT FK_71BF8DE39D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
//        $this->addSql('ALTER TABLE following ADD CONSTRAINT FK_71BF8DE33CF8336F FOREIGN KEY (following_id_id) REFERENCES user (id)');
//        $this->addSql('ALTER TABLE posts ADD CONSTRAINT FK_885DBAFAF05788E9 FOREIGN KEY (creator_id_id) REFERENCES user (id)');
//        $this->addSql('ALTER TABLE posts ADD CONSTRAINT FK_885DBAFA69574A48 FOREIGN KEY (recipe_id_id) REFERENCES recipes (id)');
//        $this->addSql('ALTER TABLE user CHANGE email email VARCHAR(255) NOT NULL, CHANGE password password VARCHAR(255) NOT NULL, CHANGE username username VARCHAR(255) NOT NULL, CHANGE name name VARCHAR(255) NOT NULL, CHANGE surname surname VARCHAR(255) NOT NULL, CHANGE dietPreference dietPreference VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        //$this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A9D86650F');
        //$this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962AE85F12B8');
        //$this->addSql('ALTER TABLE followers DROP FOREIGN KEY FK_8408FDA79D86650F');
        //$this->addSql('ALTER TABLE followers DROP FOREIGN KEY FK_8408FDA7E8DDDA11');
        //$this->addSql('ALTER TABLE following DROP FOREIGN KEY FK_71BF8DE39D86650F');
        //$this->addSql('ALTER TABLE following DROP FOREIGN KEY FK_71BF8DE33CF8336F');
        //$this->addSql('ALTER TABLE posts DROP FOREIGN KEY FK_885DBAFAF05788E9');
        //$this->addSql('ALTER TABLE posts DROP FOREIGN KEY FK_885DBAFA69574A48');
        //$this->addSql('DROP TABLE comments');
        //$this->addSql('DROP TABLE followers');
        //$this->addSql('DROP TABLE following');
        //$this->addSql('DROP TABLE posts');
        //$this->addSql('DROP TABLE recipes');
        //$this->addSql('ALTER TABLE user CHANGE email email VARCHAR(100) NOT NULL, CHANGE password password VARCHAR(100) NOT NULL, CHANGE username username VARCHAR(100) NOT NULL, CHANGE name name VARCHAR(100) NOT NULL, CHANGE surname surname VARCHAR(100) NOT NULL, CHANGE dietPreference dietPreference INT DEFAULT NULL');
    }
}
