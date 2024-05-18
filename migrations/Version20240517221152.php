<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240517221152 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
//        $this->addSql('ALTER TABLE posts DROP FOREIGN KEY FK_885DBAFA69574A48');
//        $this->addSql('ALTER TABLE posts DROP FOREIGN KEY FK_885DBAFA9D86650F');
//        $this->addSql('DROP TABLE recipes');
//        $this->addSql('DROP TABLE posts');
//        $this->addSql('ALTER TABLE comments CHANGE user_id user_id VARCHAR(255) NOT NULL, CHANGE post_id post_id VARCHAR(255) NOT NULL');
//        $this->addSql('ALTER TABLE followers CHANGE user_id user_id VARCHAR(100) NOT NULL, CHANGE follower_id follower_id VARCHAR(100) NOT NULL');
//        $this->addSql('ALTER TABLE following CHANGE user_id user_id VARCHAR(100) NOT NULL, CHANGE following_id following_id VARCHAR(100) NOT NULL');
//        $this->addSql('ALTER TABLE saved_recipes ADD is_my_recipe TINYINT(1) NOT NULL');
//        $this->addSql('ALTER TABLE user CHANGE email email VARCHAR(255) NOT NULL, CHANGE password password VARCHAR(255) NOT NULL, CHANGE username username VARCHAR(255) NOT NULL, CHANGE name name VARCHAR(255) NOT NULL, CHANGE surname surname VARCHAR(255) NOT NULL, CHANGE dietPreference dietPreference VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
//        $this->addSql('CREATE TABLE recipes (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, recipe_name VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, recipe_description VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, picture LONGBLOB DEFAULT NULL, cost INT NOT NULL, ingredients JSON NOT NULL, time INT NOT NULL, calories DOUBLE PRECISION DEFAULT NULL, fat DOUBLE PRECISION DEFAULT NULL, carbs DOUBLE PRECISION DEFAULT NULL, protein DOUBLE PRECISION DEFAULT NULL, servings INT NOT NULL, diet VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, type VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
//        $this->addSql('CREATE TABLE posts (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, recipe_id_id INT NOT NULL, INDEX IDX_885DBAFA9D86650F (user_id_id), UNIQUE INDEX UNIQ_885DBAFA69574A48 (recipe_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
//        $this->addSql('ALTER TABLE posts ADD CONSTRAINT FK_885DBAFA69574A48 FOREIGN KEY (recipe_id_id) REFERENCES recipes (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
//        $this->addSql('ALTER TABLE posts ADD CONSTRAINT FK_885DBAFA9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
//        $this->addSql('ALTER TABLE comments CHANGE user_id user_id INT NOT NULL, CHANGE post_id post_id INT NOT NULL');
//        $this->addSql('ALTER TABLE saved_recipes DROP is_my_recipe');
//        $this->addSql('ALTER TABLE user CHANGE email email VARCHAR(100) NOT NULL, CHANGE password password VARCHAR(100) NOT NULL, CHANGE username username VARCHAR(100) NOT NULL, CHANGE name name VARCHAR(100) NOT NULL, CHANGE surname surname VARCHAR(100) NOT NULL, CHANGE dietPreference dietPreference INT DEFAULT NULL');
//        $this->addSql('ALTER TABLE following CHANGE user_id user_id INT NOT NULL, CHANGE following_id following_id INT NOT NULL');
//        $this->addSql('ALTER TABLE followers CHANGE user_id user_id INT NOT NULL, CHANGE follower_id follower_id INT NOT NULL');
    }
}
