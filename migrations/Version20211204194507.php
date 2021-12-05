<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211204194507 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE video_seen DROP FOREIGN KEY FK_A3C557C429C1004E');
        $this->addSql('ALTER TABLE video_seen DROP FOREIGN KEY FK_A3C557C4A76ED395');
        $this->addSql('ALTER TABLE video_seen CHANGE user_id user_id INT DEFAULT NULL, CHANGE video_id video_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE video_seen ADD CONSTRAINT FK_A3C557C429C1004E FOREIGN KEY (video_id) REFERENCES video (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE video_seen ADD CONSTRAINT FK_A3C557C4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE video_seen DROP FOREIGN KEY FK_A3C557C4A76ED395');
        $this->addSql('ALTER TABLE video_seen DROP FOREIGN KEY FK_A3C557C429C1004E');
        $this->addSql('ALTER TABLE video_seen CHANGE user_id user_id INT NOT NULL, CHANGE video_id video_id INT NOT NULL');
        $this->addSql('ALTER TABLE video_seen ADD CONSTRAINT FK_A3C557C4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE video_seen ADD CONSTRAINT FK_A3C557C429C1004E FOREIGN KEY (video_id) REFERENCES video (id)');
    }
}
