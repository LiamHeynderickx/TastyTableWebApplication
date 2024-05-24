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
//final class Version20240519153045 extends AbstractMigration
//{
//    public function getDescription(): string
//    {
//        return 'Add ingredientsAmounts, ingredientsUnits, and instructions columns to recipes table';
//    }
//
//    public function up(Schema $schema): void
//    {
//        //$this->addSql('ALTER TABLE recipes ADD ingredientsAmounts JSON DEFAULT NULL');
//        //$this->addSql('ALTER TABLE recipes ADD ingredientsUnits JSON DEFAULT NULL');
//        //$this->addSql('ALTER TABLE recipes ADD instructions JSON NOT NULL');
//    }
//
//    public function down(Schema $schema): void
//    {
//        $this->addSql('ALTER TABLE recipes DROP ingredientsAmounts');
//        $this->addSql('ALTER TABLE recipes DROP ingredientsUnits');
//        $this->addSql('ALTER TABLE recipes DROP instructions');
//    }
//}
