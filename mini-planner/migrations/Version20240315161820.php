<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240315161820 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE project (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, hits INTEGER NOT NULL)');
        $this->addSql('CREATE TABLE project_room (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, project_id INTEGER NOT NULL, CONSTRAINT FK_4B0E9983166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_4B0E9983166D1F9C ON project_room (project_id)');
        $this->addSql('CREATE TABLE room_wall (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, project_room_id INTEGER NOT NULL, CONSTRAINT FK_70F10592C2097CF6 FOREIGN KEY (project_room_id) REFERENCES project_room (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_70F10592C2097CF6 ON room_wall (project_room_id)');
        $this->addSql('CREATE TABLE wall_point (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, room_wall_id INTEGER NOT NULL, point_x DOUBLE PRECISION NOT NULL, point_y DOUBLE PRECISION NOT NULL, CONSTRAINT FK_FB0A4E0E2F1B9F11 FOREIGN KEY (room_wall_id) REFERENCES room_wall (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_FB0A4E0E2F1B9F11 ON wall_point (room_wall_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE project_room');
        $this->addSql('DROP TABLE room_wall');
        $this->addSql('DROP TABLE wall_point');
    }
}
