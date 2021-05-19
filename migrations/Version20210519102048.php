<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210519102048 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE interclub (id INT AUTO_INCREMENT NOT NULL, date DATETIME NOT NULL, is_visitor TINYINT(1) NOT NULL, opponent VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE teams (id INT AUTO_INCREMENT NOT NULL, division VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(100) NOT NULL, pseudo VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, lastname VARCHAR(100) NOT NULL, firstname VARCHAR(100) NOT NULL, birthday DATE NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, last_log_at DATETIME NOT NULL, roles VARCHAR(255) NOT NULL, ranking VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_teams (user_id INT NOT NULL, teams_id INT NOT NULL, INDEX IDX_7400D900A76ED395 (user_id), INDEX IDX_7400D900D6365F12 (teams_id), PRIMARY KEY(user_id, teams_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_teams ADD CONSTRAINT FK_7400D900A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_teams ADD CONSTRAINT FK_7400D900D6365F12 FOREIGN KEY (teams_id) REFERENCES teams (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_teams DROP FOREIGN KEY FK_7400D900D6365F12');
        $this->addSql('ALTER TABLE user_teams DROP FOREIGN KEY FK_7400D900A76ED395');
        $this->addSql('DROP TABLE interclub');
        $this->addSql('DROP TABLE teams');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_teams');
    }
}
