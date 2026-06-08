<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260607182432 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE usuario_juego (usuario_id INT NOT NULL, juego_id INT NOT NULL, INDEX IDX_ED322A23DB38439E (usuario_id), INDEX IDX_ED322A2313375255 (juego_id), PRIMARY KEY (usuario_id, juego_id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('ALTER TABLE usuario_juego ADD CONSTRAINT FK_ED322A23DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE usuario_juego ADD CONSTRAINT FK_ED322A2313375255 FOREIGN KEY (juego_id) REFERENCES juego (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE usuario_juego DROP FOREIGN KEY FK_ED322A23DB38439E');
        $this->addSql('ALTER TABLE usuario_juego DROP FOREIGN KEY FK_ED322A2313375255');
        $this->addSql('DROP TABLE usuario_juego');
    }
}
