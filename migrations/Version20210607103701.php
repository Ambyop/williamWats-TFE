<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210607103701 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE match_cancellation (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, matchs_id INT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_CE496C87A76ED395 (user_id), INDEX IDX_CE496C8788EB7468 (matchs_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE match_cancellation ADD CONSTRAINT FK_CE496C87A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE match_cancellation ADD CONSTRAINT FK_CE496C8788EB7468 FOREIGN KEY (matchs_id) REFERENCES match_list (id)');
        $this->addSql('DROP TABLE interclub');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE interclub (id INT AUTO_INCREMENT NOT NULL, date DATETIME NOT NULL, is_visitor TINYINT(1) NOT NULL, opponent VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, address VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE match_cancellation');
    }
}
