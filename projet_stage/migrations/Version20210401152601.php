<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210401152601 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE jeu ADD lien_web LONGTEXT DEFAULT NULL, ADD lien_dl LONGTEXT DEFAULT NULL, ADD nomdossier LONGTEXT DEFAULT NULL, ADD type LONGTEXT DEFAULT NULL, ADD longueur INT DEFAULT NULL, ADD largeur INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE jeu DROP lien_web, DROP lien_dl, DROP nomdossier, DROP type, DROP longueur, DROP largeur');
    }
}
