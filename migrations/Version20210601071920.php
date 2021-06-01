<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210601071920 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE stage (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, begin_at DATETIME NOT NULL, end_at DATETIME NOT NULL, price DOUBLE PRECISION NOT NULL, start_hours VARCHAR(255) NOT NULL, end_hours VARCHAR(255) NOT NULL, days JSON NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stage_user (stage_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_4F46D9DC2298D193 (stage_id), INDEX IDX_4F46D9DCA76ED395 (user_id), PRIMARY KEY(stage_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE stage_user ADD CONSTRAINT FK_4F46D9DC2298D193 FOREIGN KEY (stage_id) REFERENCES stage (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE stage_user ADD CONSTRAINT FK_4F46D9DCA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE stage_user DROP FOREIGN KEY FK_4F46D9DC2298D193');
        $this->addSql('DROP TABLE stage');
        $this->addSql('DROP TABLE stage_user');
    }
}
