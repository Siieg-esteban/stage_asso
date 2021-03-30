<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210330161429 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE imagefichierrequete (id INT AUTO_INCREMENT NOT NULL, requete_id INT NOT NULL, image LONGBLOB DEFAULT NULL, lien LONGTEXT DEFAULT NULL, type LONGTEXT NOT NULL, INDEX IDX_243E9D2618FB544F (requete_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE imagefichierrequete ADD CONSTRAINT FK_243E9D2618FB544F FOREIGN KEY (requete_id) REFERENCES requete_contributeur (id)');
        $this->addSql('ALTER TABLE blog DROP FOREIGN KEY FK_C015514355AB140');
        $this->addSql('DROP INDEX fk_c015514355ab140 ON blog');
        $this->addSql('CREATE INDEX auteur ON blog (auteur)');
        $this->addSql('ALTER TABLE blog ADD CONSTRAINT FK_C015514355AB140 FOREIGN KEY (auteur) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE com DROP FOREIGN KEY FK_64B6C6E69E6AFC01');
        $this->addSql('DROP INDEX fk_64b6c6e69e6afc01 ON com');
        $this->addSql('CREATE INDEX envoyer ON com (envoyer)');
        $this->addSql('ALTER TABLE com ADD CONSTRAINT FK_64B6C6E69E6AFC01 FOREIGN KEY (envoyer) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE listecompetence DROP FOREIGN KEY FK_843C0F3B8D93D649');
        $this->addSql('ALTER TABLE listecompetence DROP FOREIGN KEY FK_843C0F3B94D4687F');
        $this->addSql('DROP INDEX fk_843c0f3b8d93d649 ON listecompetence');
        $this->addSql('CREATE INDEX user ON listecompetence (user)');
        $this->addSql('DROP INDEX fk_843c0f3b94d4687f ON listecompetence');
        $this->addSql('CREATE INDEX competence ON listecompetence (competence)');
        $this->addSql('ALTER TABLE listecompetence ADD CONSTRAINT FK_843C0F3B8D93D649 FOREIGN KEY (user) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE listecompetence ADD CONSTRAINT FK_843C0F3B94D4687F FOREIGN KEY (competence) REFERENCES competence (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE listecontributeur DROP FOREIGN KEY FK_C02FF78D93D649');
        $this->addSql('DROP INDEX fk_c02ff78d93d649 ON listecontributeur');
        $this->addSql('CREATE INDEX user ON listecontributeur (user)');
        $this->addSql('ALTER TABLE listecontributeur ADD CONSTRAINT FK_C02FF78D93D649 FOREIGN KEY (user) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE listejeusuivis DROP FOREIGN KEY FK_ECE71C8E8D93D649');
        $this->addSql('DROP INDEX fk_ece71c8e8d93d649 ON listejeusuivis');
        $this->addSql('CREATE INDEX user ON listejeusuivis (user)');
        $this->addSql('ALTER TABLE listejeusuivis ADD CONSTRAINT FK_ECE71C8E8D93D649 FOREIGN KEY (user) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE messagerie DROP FOREIGN KEY FK_14E8F60C6160C427');
        $this->addSql('ALTER TABLE messagerie DROP FOREIGN KEY FK_14E8F60C9E6AFC01');
        $this->addSql('DROP INDEX fk_14e8f60c9e6afc01 ON messagerie');
        $this->addSql('CREATE INDEX envoyer ON messagerie (envoyer)');
        $this->addSql('DROP INDEX fk_14e8f60c6160c427 ON messagerie');
        $this->addSql('CREATE INDEX receveur ON messagerie (receveur)');
        $this->addSql('ALTER TABLE messagerie ADD CONSTRAINT FK_14E8F60C6160C427 FOREIGN KEY (receveur) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE messagerie ADD CONSTRAINT FK_14E8F60C9E6AFC01 FOREIGN KEY (envoyer) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE proto DROP FOREIGN KEY FK_2B1333E355AB140');
        $this->addSql('DROP INDEX fk_2b1333e355ab140 ON proto');
        $this->addSql('CREATE INDEX auteur ON proto (auteur)');
        $this->addSql('ALTER TABLE proto ADD CONSTRAINT FK_2B1333E355AB140 FOREIGN KEY (auteur) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tchat DROP FOREIGN KEY FK_8EA99CA48D93D649');
        $this->addSql('DROP INDEX fk_8ea99ca48d93d649 ON tchat');
        $this->addSql('CREATE INDEX user ON tchat (user)');
        $this->addSql('ALTER TABLE tchat ADD CONSTRAINT FK_8EA99CA48D93D649 FOREIGN KEY (user) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE imagefichierrequete');
        $this->addSql('ALTER TABLE blog DROP FOREIGN KEY FK_C015514355AB140');
        $this->addSql('DROP INDEX auteur ON blog');
        $this->addSql('CREATE INDEX FK_C015514355AB140 ON blog (auteur)');
        $this->addSql('ALTER TABLE blog ADD CONSTRAINT FK_C015514355AB140 FOREIGN KEY (auteur) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE com DROP FOREIGN KEY FK_64B6C6E69E6AFC01');
        $this->addSql('DROP INDEX envoyer ON com');
        $this->addSql('CREATE INDEX FK_64B6C6E69E6AFC01 ON com (envoyer)');
        $this->addSql('ALTER TABLE com ADD CONSTRAINT FK_64B6C6E69E6AFC01 FOREIGN KEY (envoyer) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE listecompetence DROP FOREIGN KEY FK_843C0F3B8D93D649');
        $this->addSql('ALTER TABLE listecompetence DROP FOREIGN KEY FK_843C0F3B94D4687F');
        $this->addSql('DROP INDEX competence ON listecompetence');
        $this->addSql('CREATE INDEX FK_843C0F3B94D4687F ON listecompetence (competence)');
        $this->addSql('DROP INDEX user ON listecompetence');
        $this->addSql('CREATE INDEX FK_843C0F3B8D93D649 ON listecompetence (user)');
        $this->addSql('ALTER TABLE listecompetence ADD CONSTRAINT FK_843C0F3B8D93D649 FOREIGN KEY (user) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE listecompetence ADD CONSTRAINT FK_843C0F3B94D4687F FOREIGN KEY (competence) REFERENCES competence (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE listecontributeur DROP FOREIGN KEY FK_C02FF78D93D649');
        $this->addSql('DROP INDEX user ON listecontributeur');
        $this->addSql('CREATE INDEX FK_C02FF78D93D649 ON listecontributeur (user)');
        $this->addSql('ALTER TABLE listecontributeur ADD CONSTRAINT FK_C02FF78D93D649 FOREIGN KEY (user) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE listejeusuivis DROP FOREIGN KEY FK_ECE71C8E8D93D649');
        $this->addSql('DROP INDEX user ON listejeusuivis');
        $this->addSql('CREATE INDEX FK_ECE71C8E8D93D649 ON listejeusuivis (user)');
        $this->addSql('ALTER TABLE listejeusuivis ADD CONSTRAINT FK_ECE71C8E8D93D649 FOREIGN KEY (user) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE messagerie DROP FOREIGN KEY FK_14E8F60C9E6AFC01');
        $this->addSql('ALTER TABLE messagerie DROP FOREIGN KEY FK_14E8F60C6160C427');
        $this->addSql('DROP INDEX receveur ON messagerie');
        $this->addSql('CREATE INDEX FK_14E8F60C6160C427 ON messagerie (receveur)');
        $this->addSql('DROP INDEX envoyer ON messagerie');
        $this->addSql('CREATE INDEX FK_14E8F60C9E6AFC01 ON messagerie (envoyer)');
        $this->addSql('ALTER TABLE messagerie ADD CONSTRAINT FK_14E8F60C9E6AFC01 FOREIGN KEY (envoyer) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE messagerie ADD CONSTRAINT FK_14E8F60C6160C427 FOREIGN KEY (receveur) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE proto DROP FOREIGN KEY FK_2B1333E355AB140');
        $this->addSql('DROP INDEX auteur ON proto');
        $this->addSql('CREATE INDEX FK_2B1333E355AB140 ON proto (auteur)');
        $this->addSql('ALTER TABLE proto ADD CONSTRAINT FK_2B1333E355AB140 FOREIGN KEY (auteur) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tchat DROP FOREIGN KEY FK_8EA99CA48D93D649');
        $this->addSql('DROP INDEX user ON tchat');
        $this->addSql('CREATE INDEX FK_8EA99CA48D93D649 ON tchat (user)');
        $this->addSql('ALTER TABLE tchat ADD CONSTRAINT FK_8EA99CA48D93D649 FOREIGN KEY (user) REFERENCES user (id) ON DELETE CASCADE');
    }
}
