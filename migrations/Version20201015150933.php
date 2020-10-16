<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201015150933 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, full_name LONGTEXT NOT NULL, first_name LONGTEXT NOT NULL, login LONGTEXT NOT NULL, password LONGTEXT NOT NULL, mail LONGTEXT NOT NULL, phone_number LONGTEXT NOT NULL, sex LONGTEXT NOT NULL, nationality LONGTEXT NOT NULL, address LONGTEXT NOT NULL, postal_code LONGTEXT NOT NULL, city LONGTEXT NOT NULL, country LONGTEXT NOT NULL, birthday DATE NOT NULL, personal_mail LONGTEXT NOT NULL, surnom LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', facebook LONGTEXT NOT NULL, twitter LONGTEXT NOT NULL, is_student TINYINT(1) NOT NULL, is_staff_utt TINYINT(1) NOT NULL, store_roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', bde_membership_start LONGTEXT NOT NULL, bde_membership_end DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, first_login TINYINT(1) DEFAULT NULL, is_keeping_accout TINYINT(1) DEFAULT NULL, is_deleting_everything TINYINT(1) DEFAULT NULL, linkedin VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE student (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, last_name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, date_of_birth DATETIME NOT NULL, ue LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:array)\', association LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:array)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE user');
    }
}
