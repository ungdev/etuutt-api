<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210413133341 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE assos (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', description_short_traduction_code VARCHAR(100) DEFAULT NULL, description_traduction_code VARCHAR(100) DEFAULT NULL, login VARCHAR(50) NOT NULL, name VARCHAR(100) NOT NULL, mail VARCHAR(100) NOT NULL, phone_number VARCHAR(30) DEFAULT NULL, website VARCHAR(100) DEFAULT NULL, logo VARCHAR(100) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_66242775AA08CB10 (login), INDEX IDX_6624277510DA6E15 (description_short_traduction_code), INDEX IDX_6624277570CCC9A7 (description_traduction_code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE badges (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', description_traduction_code VARCHAR(100) DEFAULT NULL, serie VARCHAR(50) DEFAULT NULL, level SMALLINT DEFAULT NULL, name VARCHAR(100) NOT NULL, picture VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_78F6539A70CCC9A7 (description_traduction_code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users_badges (badge_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', user_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_7711DD0AF7A2C2FC (badge_id), INDEX IDX_7711DD0AA76ED395 (user_id), PRIMARY KEY(badge_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE traductions (code VARCHAR(100) NOT NULL, french LONGTEXT DEFAULT NULL, english LONGTEXT DEFAULT NULL, spanish LONGTEXT DEFAULT NULL, german LONGTEXT DEFAULT NULL, chinese LONGTEXT DEFAULT NULL, PRIMARY KEY(code)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_etuutt_team (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', user_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', role LONGTEXT DEFAULT NULL, INDEX IDX_954AE479A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_etuutt_team_semesters (user_etuutt_team_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', semester_code VARCHAR(10) NOT NULL, INDEX IDX_E0F53156917A081C (user_etuutt_team_id), INDEX IDX_E0F53156867741B7 (semester_code), PRIMARY KEY(user_etuutt_team_id, semester_code)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE assos ADD CONSTRAINT FK_6624277510DA6E15 FOREIGN KEY (description_short_traduction_code) REFERENCES traductions (code)');
        $this->addSql('ALTER TABLE assos ADD CONSTRAINT FK_6624277570CCC9A7 FOREIGN KEY (description_traduction_code) REFERENCES traductions (code)');
        $this->addSql('ALTER TABLE badges ADD CONSTRAINT FK_78F6539A70CCC9A7 FOREIGN KEY (description_traduction_code) REFERENCES traductions (code)');
        $this->addSql('ALTER TABLE users_badges ADD CONSTRAINT FK_7711DD0AF7A2C2FC FOREIGN KEY (badge_id) REFERENCES badges (id)');
        $this->addSql('ALTER TABLE users_badges ADD CONSTRAINT FK_7711DD0AA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE user_etuutt_team ADD CONSTRAINT FK_954AE479A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE user_etuutt_team_semesters ADD CONSTRAINT FK_E0F53156917A081C FOREIGN KEY (user_etuutt_team_id) REFERENCES user_etuutt_team (id)');
        $this->addSql('ALTER TABLE user_etuutt_team_semesters ADD CONSTRAINT FK_E0F53156867741B7 FOREIGN KEY (semester_code) REFERENCES semesters (code)');
        $this->addSql('DROP TABLE asso');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE users_badges DROP FOREIGN KEY FK_7711DD0AF7A2C2FC');
        $this->addSql('ALTER TABLE assos DROP FOREIGN KEY FK_6624277510DA6E15');
        $this->addSql('ALTER TABLE assos DROP FOREIGN KEY FK_6624277570CCC9A7');
        $this->addSql('ALTER TABLE badges DROP FOREIGN KEY FK_78F6539A70CCC9A7');
        $this->addSql('ALTER TABLE user_etuutt_team_semesters DROP FOREIGN KEY FK_E0F53156917A081C');
        $this->addSql('DROP TABLE assos');
        $this->addSql('DROP TABLE badges');
        $this->addSql('DROP TABLE users_badges');
        $this->addSql('DROP TABLE traductions');
        $this->addSql('DROP TABLE user_etuutt_team');
        $this->addSql('DROP TABLE user_etuutt_team_semesters');
    }
}
