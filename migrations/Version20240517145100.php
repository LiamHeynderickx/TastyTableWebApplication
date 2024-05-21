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
//final class Version20240517145100 extends AbstractMigration
//{
//    public function getDescription(): string
//    {
//        return '';
//    }
//
//    public function up(Schema $schema): void
//    {
//        // this up() migration is auto-generated, please modify it to your needs
//        //$this->addSql('CREATE TABLE comments (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, post_id INT NOT NULL, comment VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
//        //$this->addSql('CREATE TABLE followers (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, follower_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
//        //$this->addSql('CREATE TABLE following (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, following_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
//        //$this->addSql('CREATE TABLE posts (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, recipe_id_id INT NOT NULL, INDEX IDX_885DBAFA9D86650F (user_id_id), UNIQUE INDEX UNIQ_885DBAFA69574A48 (recipe_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
//        //$this->addSql('CREATE TABLE saved_recipes (id INT AUTO_INCREMENT NOT NULL, user_id VARCHAR(100) NOT NULL, recipe_id VARCHAR(100) NOT NULL, is_api TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
//        //$this->addSql('ALTER TABLE posts ADD CONSTRAINT FK_885DBAFA9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
//        //$this->addSql('ALTER TABLE posts ADD CONSTRAINT FK_885DBAFA69574A48 FOREIGN KEY (recipe_id_id) REFERENCES recipes (id)');
//        //$this->addSql('ALTER TABLE user CHANGE email email VARCHAR(255) NOT NULL, CHANGE password password VARCHAR(255) NOT NULL, CHANGE username username VARCHAR(255) NOT NULL, CHANGE name name VARCHAR(255) NOT NULL, CHANGE surname surname VARCHAR(255) NOT NULL, CHANGE dietPreference dietPreference VARCHAR(255) NOT NULL');
//    }
//
//    public function down(Schema $schema): void
//    {
//        // this down() migration is auto-generated, please modify it to your needs
//        $this->addSql('ALTER TABLE posts DROP FOREIGN KEY FK_885DBAFA9D86650F');
//        $this->addSql('ALTER TABLE posts DROP FOREIGN KEY FK_885DBAFA69574A48');
//        $this->addSql('DROP TABLE comments');
//        $this->addSql('DROP TABLE followers');
//        $this->addSql('DROP TABLE following');
//        $this->addSql('DROP TABLE posts');
//        $this->addSql('DROP TABLE saved_recipes');
//        $this->addSql('ALTER TABLE user CHANGE email email VARCHAR(100) NOT NULL, CHANGE password password VARCHAR(100) NOT NULL, CHANGE username username VARCHAR(100) NOT NULL, CHANGE name name VARCHAR(100) NOT NULL, CHANGE surname surname VARCHAR(100) NOT NULL, CHANGE dietPreference dietPreference INT DEFAULT NULL');
//    }
//}
