<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210309110646 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE com CHANGE envoyer envoyer INT DEFAULT NULL');
        $this->addSql('ALTER TABLE imagejeuproto ADD blog INT DEFAULT NULL, CHANGE type type TEXT NOT NULL COMMENT \'jeu ou proto\'');
        $this->addSql('ALTER TABLE imagejeuproto ADD CONSTRAINT FK_AA01EE75C0155143 FOREIGN KEY (blog) REFERENCES blog (id)');
        $this->addSql('CREATE INDEX blog ON imagejeuproto (blog)');
        $this->addSql('ALTER TABLE jeu CHANGE auteur auteur INT DEFAULT NULL, CHANGE upvote upvote INT NOT NULL');
        $this->addSql('ALTER TABLE listecategorie CHANGE categorie categorie INT DEFAULT NULL');
        $this->addSql('ALTER TABLE listecompetence CHANGE competence competence INT DEFAULT NULL, CHANGE user user INT DEFAULT NULL');
        $this->addSql('ALTER TABLE listecontributeur CHANGE user user INT DEFAULT NULL');
        $this->addSql('ALTER TABLE listejeusuivis CHANGE user user INT DEFAULT NULL, CHANGE jeu jeu INT DEFAULT NULL');
        $this->addSql('ALTER TABLE messagerie CHANGE envoyer envoyer INT DEFAULT NULL, CHANGE receveur receveur INT DEFAULT NULL');
        $this->addSql('ALTER TABLE proto CHANGE auteur auteur INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tchat CHANGE user user INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE com CHANGE envoyer envoyer INT NOT NULL');
        $this->addSql('ALTER TABLE imagejeuproto DROP FOREIGN KEY FK_AA01EE75C0155143');
        $this->addSql('DROP INDEX blog ON imagejeuproto');
        $this->addSql('ALTER TABLE imagejeuproto DROP blog, CHANGE type type TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci` COMMENT \'jeu ou proto ou blog\'');
        $this->addSql('ALTER TABLE jeu CHANGE auteur auteur INT NOT NULL, CHANGE upvote upvote INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE listecategorie CHANGE categorie categorie INT NOT NULL');
        $this->addSql('ALTER TABLE listecompetence CHANGE user user INT NOT NULL, CHANGE competence competence INT NOT NULL');
        $this->addSql('ALTER TABLE listecontributeur CHANGE user user INT NOT NULL');
        $this->addSql('ALTER TABLE listejeusuivis CHANGE user user INT NOT NULL, CHANGE jeu jeu INT NOT NULL');
        $this->addSql('ALTER TABLE messagerie CHANGE envoyer envoyer INT NOT NULL, CHANGE receveur receveur INT NOT NULL');
        $this->addSql('ALTER TABLE proto CHANGE auteur auteur INT NOT NULL');
        $this->addSql('ALTER TABLE tchat CHANGE user user INT NOT NULL');
    }
}
