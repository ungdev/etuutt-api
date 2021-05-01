<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210413161232 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE asso_keywords (asso_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', keyword_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_AA7F99C2792C8628 (asso_id), INDEX IDX_AA7F99C2115D4552 (keyword_id), PRIMARY KEY(asso_id, keyword_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE keywords (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(30) NOT NULL, UNIQUE INDEX UNIQ_AA5FB55E5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE asso_keywords ADD CONSTRAINT FK_AA7F99C2792C8628 FOREIGN KEY (asso_id) REFERENCES assos (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE asso_keywords ADD CONSTRAINT FK_AA7F99C2115D4552 FOREIGN KEY (keyword_id) REFERENCES keywords (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE asso_keywords DROP FOREIGN KEY FK_AA7F99C2115D4552');
        $this->addSql('DROP TABLE asso_keywords');
        $this->addSql('DROP TABLE keywords');
    }
}
