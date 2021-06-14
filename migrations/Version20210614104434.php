<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210614104434 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE articles (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(100) NOT NULL, content LONGTEXT NOT NULL, image VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, is_enabled TINYINT(1) NOT NULL, is_integration TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ch_cookieconsent_log (id INT AUTO_INCREMENT NOT NULL, ip_address VARCHAR(255) NOT NULL, cookie_consent_key VARCHAR(255) NOT NULL, cookie_name VARCHAR(255) NOT NULL, cookie_value VARCHAR(255) NOT NULL, timestamp DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE club_lists (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE match_cancellation (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, matchs_id INT NOT NULL, created_at DATETIME NOT NULL, content VARCHAR(255) NOT NULL, INDEX IDX_CE496C87A76ED395 (user_id), INDEX IDX_CE496C8788EB7468 (matchs_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE match_list (id INT AUTO_INCREMENT NOT NULL, team_id INT NOT NULL, location_id INT NOT NULL, date DATETIME NOT NULL, updated_at DATETIME NOT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_25A7496A296CD8AE (team_id), INDEX IDX_25A7496A64D218E (location_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE match_list_user (match_list_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_D1EC450B4A0849E4 (match_list_id), INDEX IDX_D1EC450BA76ED395 (user_id), PRIMARY KEY(match_list_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stage (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, begin_at DATETIME NOT NULL, end_at DATETIME NOT NULL, price DOUBLE PRECISION NOT NULL, start_hours VARCHAR(255) NOT NULL, end_hours VARCHAR(255) NOT NULL, days JSON NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stage_user (stage_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_4F46D9DC2298D193 (stage_id), INDEX IDX_4F46D9DCA76ED395 (user_id), PRIMARY KEY(stage_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE teams (id INT AUTO_INCREMENT NOT NULL, division VARCHAR(255) NOT NULL, name VARCHAR(100) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, is_enabled TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, team_id INT DEFAULT NULL, email VARCHAR(100) NOT NULL, password VARCHAR(255) NOT NULL, lastname VARCHAR(100) NOT NULL, firstname VARCHAR(100) NOT NULL, phone_number VARCHAR(60) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, birthday DATE DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, last_log_at DATETIME NOT NULL, roles JSON NOT NULL, ranking VARCHAR(255) DEFAULT NULL, is_disabled TINYINT(1) NOT NULL, token VARCHAR(255) DEFAULT NULL, token_validity DATETIME DEFAULT NULL, verified_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D649296CD8AE (team_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE match_cancellation ADD CONSTRAINT FK_CE496C87A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE match_cancellation ADD CONSTRAINT FK_CE496C8788EB7468 FOREIGN KEY (matchs_id) REFERENCES match_list (id)');
        $this->addSql('ALTER TABLE match_list ADD CONSTRAINT FK_25A7496A296CD8AE FOREIGN KEY (team_id) REFERENCES teams (id)');
        $this->addSql('ALTER TABLE match_list ADD CONSTRAINT FK_25A7496A64D218E FOREIGN KEY (location_id) REFERENCES club_lists (id)');
        $this->addSql('ALTER TABLE match_list_user ADD CONSTRAINT FK_D1EC450B4A0849E4 FOREIGN KEY (match_list_id) REFERENCES match_list (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE match_list_user ADD CONSTRAINT FK_D1EC450BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE stage_user ADD CONSTRAINT FK_4F46D9DC2298D193 FOREIGN KEY (stage_id) REFERENCES stage (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE stage_user ADD CONSTRAINT FK_4F46D9DCA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649296CD8AE FOREIGN KEY (team_id) REFERENCES teams (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE match_list DROP FOREIGN KEY FK_25A7496A64D218E');
        $this->addSql('ALTER TABLE match_cancellation DROP FOREIGN KEY FK_CE496C8788EB7468');
        $this->addSql('ALTER TABLE match_list_user DROP FOREIGN KEY FK_D1EC450B4A0849E4');
        $this->addSql('ALTER TABLE stage_user DROP FOREIGN KEY FK_4F46D9DC2298D193');
        $this->addSql('ALTER TABLE match_list DROP FOREIGN KEY FK_25A7496A296CD8AE');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649296CD8AE');
        $this->addSql('ALTER TABLE match_cancellation DROP FOREIGN KEY FK_CE496C87A76ED395');
        $this->addSql('ALTER TABLE match_list_user DROP FOREIGN KEY FK_D1EC450BA76ED395');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE stage_user DROP FOREIGN KEY FK_4F46D9DCA76ED395');
        $this->addSql('DROP TABLE articles');
        $this->addSql('DROP TABLE ch_cookieconsent_log');
        $this->addSql('DROP TABLE club_lists');
        $this->addSql('DROP TABLE match_cancellation');
        $this->addSql('DROP TABLE match_list');
        $this->addSql('DROP TABLE match_list_user');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE stage');
        $this->addSql('DROP TABLE stage_user');
        $this->addSql('DROP TABLE teams');
        $this->addSql('DROP TABLE user');
    }
}
