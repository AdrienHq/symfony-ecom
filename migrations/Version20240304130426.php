<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240304130426 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recipes ADD course_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE recipes ADD CONSTRAINT FK_A369E2B5591CC992 FOREIGN KEY (course_id) REFERENCES course (id)');
        $this->addSql('CREATE INDEX IDX_A369E2B5591CC992 ON recipes (course_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recipes DROP FOREIGN KEY FK_A369E2B5591CC992');
        $this->addSql('DROP INDEX IDX_A369E2B5591CC992 ON recipes');
        $this->addSql('ALTER TABLE recipes DROP course_id');
    }
}
