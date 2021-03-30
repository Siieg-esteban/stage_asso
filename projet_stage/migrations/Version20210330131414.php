<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210330131414 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE requete_contributeur (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, demande LONGTEXT NOT NULL, datetime DATETIME NOT NULL, INDEX IDX_3C95CA3DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE requete_contributeur ADD CONSTRAINT FK_3C95CA3DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE blog DROP FOREIGN KEY blog_ibfk_1');
        $this->addSql('ALTER TABLE blog DROP FOREIGN KEY FK_C015514382E48DB5');
        $this->addSql('ALTER TABLE blog ADD CONSTRAINT FK_C015514355AB140 FOREIGN KEY (auteur) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('DROP INDEX fk_c015514382e48db5 ON blog');
        $this->addSql('CREATE INDEX jeu ON blog (jeu)');
        $this->addSql('ALTER TABLE blog ADD CONSTRAINT FK_C015514382E48DB5 FOREIGN KEY (jeu) REFERENCES jeu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE com DROP FOREIGN KEY com_ibfk_1');
        $this->addSql('ALTER TABLE com DROP FOREIGN KEY FK_64B6C6E62B1333E3');
        $this->addSql('ALTER TABLE com DROP FOREIGN KEY FK_64B6C6E682E48DB5');
        $this->addSql('ALTER TABLE com DROP FOREIGN KEY FK_64B6C6E6C0155143');
        $this->addSql('ALTER TABLE com ADD CONSTRAINT FK_64B6C6E69E6AFC01 FOREIGN KEY (envoyer) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('DROP INDEX fk_64b6c6e62b1333e3 ON com');
        $this->addSql('CREATE INDEX proto ON com (proto)');
        $this->addSql('DROP INDEX fk_64b6c6e682e48db5 ON com');
        $this->addSql('CREATE INDEX jeu ON com (jeu)');
        $this->addSql('DROP INDEX fk_64b6c6e6c0155143 ON com');
        $this->addSql('CREATE INDEX blog ON com (blog)');
        $this->addSql('ALTER TABLE com ADD CONSTRAINT FK_64B6C6E62B1333E3 FOREIGN KEY (proto) REFERENCES proto (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE com ADD CONSTRAINT FK_64B6C6E682E48DB5 FOREIGN KEY (jeu) REFERENCES jeu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE com ADD CONSTRAINT FK_64B6C6E6C0155143 FOREIGN KEY (blog) REFERENCES blog (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fichiercommunication DROP FOREIGN KEY FK_E1DC00B614E8F60C');
        $this->addSql('ALTER TABLE fichiercommunication DROP FOREIGN KEY FK_E1DC00B664B6C6E6');
        $this->addSql('ALTER TABLE fichiercommunication DROP FOREIGN KEY FK_E1DC00B68EA99CA4');
        $this->addSql('DROP INDEX fk_e1dc00b664b6c6e6 ON fichiercommunication');
        $this->addSql('CREATE INDEX com ON fichiercommunication (com)');
        $this->addSql('DROP INDEX fk_e1dc00b68ea99ca4 ON fichiercommunication');
        $this->addSql('CREATE INDEX tchat ON fichiercommunication (tchat)');
        $this->addSql('DROP INDEX fk_e1dc00b614e8f60c ON fichiercommunication');
        $this->addSql('CREATE INDEX messagerie ON fichiercommunication (messagerie)');
        $this->addSql('ALTER TABLE fichiercommunication ADD CONSTRAINT FK_E1DC00B614E8F60C FOREIGN KEY (messagerie) REFERENCES messagerie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fichiercommunication ADD CONSTRAINT FK_E1DC00B664B6C6E6 FOREIGN KEY (com) REFERENCES com (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fichiercommunication ADD CONSTRAINT FK_E1DC00B68EA99CA4 FOREIGN KEY (tchat) REFERENCES tchat (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE imagecommunication DROP FOREIGN KEY FK_A93BAF2E14E8F60C');
        $this->addSql('ALTER TABLE imagecommunication DROP FOREIGN KEY FK_A93BAF2E64B6C6E6');
        $this->addSql('ALTER TABLE imagecommunication DROP FOREIGN KEY FK_A93BAF2E8EA99CA4');
        $this->addSql('DROP INDEX fk_a93baf2e64b6c6e6 ON imagecommunication');
        $this->addSql('CREATE INDEX com ON imagecommunication (com)');
        $this->addSql('DROP INDEX fk_a93baf2e8ea99ca4 ON imagecommunication');
        $this->addSql('CREATE INDEX tchat ON imagecommunication (tchat)');
        $this->addSql('DROP INDEX fk_a93baf2e14e8f60c ON imagecommunication');
        $this->addSql('CREATE INDEX messagerie ON imagecommunication (messagerie)');
        $this->addSql('ALTER TABLE imagecommunication ADD CONSTRAINT FK_A93BAF2E14E8F60C FOREIGN KEY (messagerie) REFERENCES messagerie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE imagecommunication ADD CONSTRAINT FK_A93BAF2E64B6C6E6 FOREIGN KEY (com) REFERENCES com (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE imagecommunication ADD CONSTRAINT FK_A93BAF2E8EA99CA4 FOREIGN KEY (tchat) REFERENCES tchat (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE imagejeuproto DROP FOREIGN KEY FK_AA01EE752B1333E3');
        $this->addSql('ALTER TABLE imagejeuproto DROP FOREIGN KEY FK_AA01EE7582E48DB5');
        $this->addSql('DROP INDEX fk_aa01ee7582e48db5 ON imagejeuproto');
        $this->addSql('CREATE INDEX jeu ON imagejeuproto (jeu)');
        $this->addSql('DROP INDEX fk_aa01ee752b1333e3 ON imagejeuproto');
        $this->addSql('CREATE INDEX proto ON imagejeuproto (proto)');
        $this->addSql('ALTER TABLE imagejeuproto ADD CONSTRAINT FK_AA01EE752B1333E3 FOREIGN KEY (proto) REFERENCES proto (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE imagejeuproto ADD CONSTRAINT FK_AA01EE7582E48DB5 FOREIGN KEY (jeu) REFERENCES jeu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE listecategorie DROP FOREIGN KEY FK_7B60C8AE2B1333E3');
        $this->addSql('ALTER TABLE listecategorie DROP FOREIGN KEY FK_7B60C8AE82E48DB5');
        $this->addSql('DROP INDEX fk_7b60c8ae82e48db5 ON listecategorie');
        $this->addSql('CREATE INDEX jeu ON listecategorie (jeu)');
        $this->addSql('DROP INDEX fk_7b60c8ae2b1333e3 ON listecategorie');
        $this->addSql('CREATE INDEX proto ON listecategorie (proto)');
        $this->addSql('ALTER TABLE listecategorie ADD CONSTRAINT FK_7B60C8AE2B1333E3 FOREIGN KEY (proto) REFERENCES proto (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE listecategorie ADD CONSTRAINT FK_7B60C8AE82E48DB5 FOREIGN KEY (jeu) REFERENCES jeu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE listecompetence DROP FOREIGN KEY listecompetence_ibfk_1');
        $this->addSql('ALTER TABLE listecompetence DROP FOREIGN KEY listecompetence_ibfk_2');
        $this->addSql('ALTER TABLE listecompetence ADD CONSTRAINT FK_843C0F3B8D93D649 FOREIGN KEY (user) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE listecompetence ADD CONSTRAINT FK_843C0F3B94D4687F FOREIGN KEY (competence) REFERENCES competence (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE listecontributeur DROP FOREIGN KEY listecontributeur_ibfk_1');
        $this->addSql('ALTER TABLE listecontributeur DROP FOREIGN KEY FK_C02FF72B1333E3');
        $this->addSql('ALTER TABLE listecontributeur DROP FOREIGN KEY FK_C02FF782E48DB5');
        $this->addSql('ALTER TABLE listecontributeur ADD CONSTRAINT FK_C02FF78D93D649 FOREIGN KEY (user) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('DROP INDEX fk_c02ff782e48db5 ON listecontributeur');
        $this->addSql('CREATE INDEX jeu ON listecontributeur (jeu)');
        $this->addSql('DROP INDEX fk_c02ff72b1333e3 ON listecontributeur');
        $this->addSql('CREATE INDEX proto ON listecontributeur (proto)');
        $this->addSql('ALTER TABLE listecontributeur ADD CONSTRAINT FK_C02FF72B1333E3 FOREIGN KEY (proto) REFERENCES proto (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE listecontributeur ADD CONSTRAINT FK_C02FF782E48DB5 FOREIGN KEY (jeu) REFERENCES jeu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE listejeusuivis DROP FOREIGN KEY listejeusuivis_ibfk_1');
        $this->addSql('ALTER TABLE listejeusuivis DROP FOREIGN KEY FK_ECE71C8E82E48DB5');
        $this->addSql('ALTER TABLE listejeusuivis ADD CONSTRAINT FK_ECE71C8E8D93D649 FOREIGN KEY (user) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('DROP INDEX fk_ece71c8e82e48db5 ON listejeusuivis');
        $this->addSql('CREATE INDEX jeu ON listejeusuivis (jeu)');
        $this->addSql('ALTER TABLE listejeusuivis ADD CONSTRAINT FK_ECE71C8E82E48DB5 FOREIGN KEY (jeu) REFERENCES jeu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE maj DROP FOREIGN KEY FK_6ECF53C12B1333E3');
        $this->addSql('ALTER TABLE maj DROP FOREIGN KEY FK_6ECF53C182E48DB5');
        $this->addSql('DROP INDEX fk_6ecf53c182e48db5 ON maj');
        $this->addSql('CREATE INDEX jeu ON maj (jeu)');
        $this->addSql('DROP INDEX fk_6ecf53c12b1333e3 ON maj');
        $this->addSql('CREATE INDEX proto ON maj (proto)');
        $this->addSql('ALTER TABLE maj ADD CONSTRAINT FK_6ECF53C12B1333E3 FOREIGN KEY (proto) REFERENCES proto (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE maj ADD CONSTRAINT FK_6ECF53C182E48DB5 FOREIGN KEY (jeu) REFERENCES jeu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE messagerie DROP FOREIGN KEY messagerie_ibfk_1');
        $this->addSql('ALTER TABLE messagerie DROP FOREIGN KEY messagerie_ibfk_2');
        $this->addSql('ALTER TABLE messagerie ADD CONSTRAINT FK_14E8F60C9E6AFC01 FOREIGN KEY (envoyer) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE messagerie ADD CONSTRAINT FK_14E8F60C6160C427 FOREIGN KEY (receveur) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE proto DROP FOREIGN KEY proto_ibfk_1');
        $this->addSql('ALTER TABLE proto ADD CONSTRAINT FK_2B1333E355AB140 FOREIGN KEY (auteur) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tchat DROP FOREIGN KEY tchat_ibfk_1');
        $this->addSql('ALTER TABLE tchat ADD CONSTRAINT FK_8EA99CA48D93D649 FOREIGN KEY (user) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE requete_contributeur');
        $this->addSql('ALTER TABLE blog DROP FOREIGN KEY FK_C015514355AB140');
        $this->addSql('ALTER TABLE blog DROP FOREIGN KEY FK_C015514382E48DB5');
        $this->addSql('ALTER TABLE blog ADD CONSTRAINT blog_ibfk_1 FOREIGN KEY (auteur) REFERENCES user (id)');
        $this->addSql('DROP INDEX jeu ON blog');
        $this->addSql('CREATE INDEX FK_C015514382E48DB5 ON blog (jeu)');
        $this->addSql('ALTER TABLE blog ADD CONSTRAINT FK_C015514382E48DB5 FOREIGN KEY (jeu) REFERENCES jeu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE com DROP FOREIGN KEY FK_64B6C6E69E6AFC01');
        $this->addSql('ALTER TABLE com DROP FOREIGN KEY FK_64B6C6E682E48DB5');
        $this->addSql('ALTER TABLE com DROP FOREIGN KEY FK_64B6C6E6C0155143');
        $this->addSql('ALTER TABLE com DROP FOREIGN KEY FK_64B6C6E62B1333E3');
        $this->addSql('ALTER TABLE com ADD CONSTRAINT com_ibfk_1 FOREIGN KEY (envoyer) REFERENCES user (id)');
        $this->addSql('DROP INDEX proto ON com');
        $this->addSql('CREATE INDEX FK_64B6C6E62B1333E3 ON com (proto)');
        $this->addSql('DROP INDEX jeu ON com');
        $this->addSql('CREATE INDEX FK_64B6C6E682E48DB5 ON com (jeu)');
        $this->addSql('DROP INDEX blog ON com');
        $this->addSql('CREATE INDEX FK_64B6C6E6C0155143 ON com (blog)');
        $this->addSql('ALTER TABLE com ADD CONSTRAINT FK_64B6C6E682E48DB5 FOREIGN KEY (jeu) REFERENCES jeu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE com ADD CONSTRAINT FK_64B6C6E6C0155143 FOREIGN KEY (blog) REFERENCES blog (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE com ADD CONSTRAINT FK_64B6C6E62B1333E3 FOREIGN KEY (proto) REFERENCES proto (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fichiercommunication DROP FOREIGN KEY FK_E1DC00B664B6C6E6');
        $this->addSql('ALTER TABLE fichiercommunication DROP FOREIGN KEY FK_E1DC00B68EA99CA4');
        $this->addSql('ALTER TABLE fichiercommunication DROP FOREIGN KEY FK_E1DC00B614E8F60C');
        $this->addSql('DROP INDEX messagerie ON fichiercommunication');
        $this->addSql('CREATE INDEX FK_E1DC00B614E8F60C ON fichiercommunication (messagerie)');
        $this->addSql('DROP INDEX com ON fichiercommunication');
        $this->addSql('CREATE INDEX FK_E1DC00B664B6C6E6 ON fichiercommunication (com)');
        $this->addSql('DROP INDEX tchat ON fichiercommunication');
        $this->addSql('CREATE INDEX FK_E1DC00B68EA99CA4 ON fichiercommunication (tchat)');
        $this->addSql('ALTER TABLE fichiercommunication ADD CONSTRAINT FK_E1DC00B664B6C6E6 FOREIGN KEY (com) REFERENCES com (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fichiercommunication ADD CONSTRAINT FK_E1DC00B68EA99CA4 FOREIGN KEY (tchat) REFERENCES tchat (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fichiercommunication ADD CONSTRAINT FK_E1DC00B614E8F60C FOREIGN KEY (messagerie) REFERENCES messagerie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE imagecommunication DROP FOREIGN KEY FK_A93BAF2E64B6C6E6');
        $this->addSql('ALTER TABLE imagecommunication DROP FOREIGN KEY FK_A93BAF2E8EA99CA4');
        $this->addSql('ALTER TABLE imagecommunication DROP FOREIGN KEY FK_A93BAF2E14E8F60C');
        $this->addSql('DROP INDEX messagerie ON imagecommunication');
        $this->addSql('CREATE INDEX FK_A93BAF2E14E8F60C ON imagecommunication (messagerie)');
        $this->addSql('DROP INDEX com ON imagecommunication');
        $this->addSql('CREATE INDEX FK_A93BAF2E64B6C6E6 ON imagecommunication (com)');
        $this->addSql('DROP INDEX tchat ON imagecommunication');
        $this->addSql('CREATE INDEX FK_A93BAF2E8EA99CA4 ON imagecommunication (tchat)');
        $this->addSql('ALTER TABLE imagecommunication ADD CONSTRAINT FK_A93BAF2E64B6C6E6 FOREIGN KEY (com) REFERENCES com (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE imagecommunication ADD CONSTRAINT FK_A93BAF2E8EA99CA4 FOREIGN KEY (tchat) REFERENCES tchat (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE imagecommunication ADD CONSTRAINT FK_A93BAF2E14E8F60C FOREIGN KEY (messagerie) REFERENCES messagerie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE imagejeuproto DROP FOREIGN KEY FK_AA01EE7582E48DB5');
        $this->addSql('ALTER TABLE imagejeuproto DROP FOREIGN KEY FK_AA01EE752B1333E3');
        $this->addSql('DROP INDEX proto ON imagejeuproto');
        $this->addSql('CREATE INDEX FK_AA01EE752B1333E3 ON imagejeuproto (proto)');
        $this->addSql('DROP INDEX jeu ON imagejeuproto');
        $this->addSql('CREATE INDEX FK_AA01EE7582E48DB5 ON imagejeuproto (jeu)');
        $this->addSql('ALTER TABLE imagejeuproto ADD CONSTRAINT FK_AA01EE7582E48DB5 FOREIGN KEY (jeu) REFERENCES jeu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE imagejeuproto ADD CONSTRAINT FK_AA01EE752B1333E3 FOREIGN KEY (proto) REFERENCES proto (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE listecategorie DROP FOREIGN KEY FK_7B60C8AE82E48DB5');
        $this->addSql('ALTER TABLE listecategorie DROP FOREIGN KEY FK_7B60C8AE2B1333E3');
        $this->addSql('DROP INDEX proto ON listecategorie');
        $this->addSql('CREATE INDEX FK_7B60C8AE2B1333E3 ON listecategorie (proto)');
        $this->addSql('DROP INDEX jeu ON listecategorie');
        $this->addSql('CREATE INDEX FK_7B60C8AE82E48DB5 ON listecategorie (jeu)');
        $this->addSql('ALTER TABLE listecategorie ADD CONSTRAINT FK_7B60C8AE82E48DB5 FOREIGN KEY (jeu) REFERENCES jeu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE listecategorie ADD CONSTRAINT FK_7B60C8AE2B1333E3 FOREIGN KEY (proto) REFERENCES proto (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE listecompetence DROP FOREIGN KEY FK_843C0F3B8D93D649');
        $this->addSql('ALTER TABLE listecompetence DROP FOREIGN KEY FK_843C0F3B94D4687F');
        $this->addSql('ALTER TABLE listecompetence ADD CONSTRAINT listecompetence_ibfk_1 FOREIGN KEY (user) REFERENCES user (id)');
        $this->addSql('ALTER TABLE listecompetence ADD CONSTRAINT listecompetence_ibfk_2 FOREIGN KEY (competence) REFERENCES competence (id)');
        $this->addSql('ALTER TABLE listecontributeur DROP FOREIGN KEY FK_C02FF78D93D649');
        $this->addSql('ALTER TABLE listecontributeur DROP FOREIGN KEY FK_C02FF782E48DB5');
        $this->addSql('ALTER TABLE listecontributeur DROP FOREIGN KEY FK_C02FF72B1333E3');
        $this->addSql('ALTER TABLE listecontributeur ADD CONSTRAINT listecontributeur_ibfk_1 FOREIGN KEY (user) REFERENCES user (id)');
        $this->addSql('DROP INDEX proto ON listecontributeur');
        $this->addSql('CREATE INDEX FK_C02FF72B1333E3 ON listecontributeur (proto)');
        $this->addSql('DROP INDEX jeu ON listecontributeur');
        $this->addSql('CREATE INDEX FK_C02FF782E48DB5 ON listecontributeur (jeu)');
        $this->addSql('ALTER TABLE listecontributeur ADD CONSTRAINT FK_C02FF782E48DB5 FOREIGN KEY (jeu) REFERENCES jeu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE listecontributeur ADD CONSTRAINT FK_C02FF72B1333E3 FOREIGN KEY (proto) REFERENCES proto (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE listejeusuivis DROP FOREIGN KEY FK_ECE71C8E8D93D649');
        $this->addSql('ALTER TABLE listejeusuivis DROP FOREIGN KEY FK_ECE71C8E82E48DB5');
        $this->addSql('ALTER TABLE listejeusuivis ADD CONSTRAINT listejeusuivis_ibfk_1 FOREIGN KEY (user) REFERENCES user (id)');
        $this->addSql('DROP INDEX jeu ON listejeusuivis');
        $this->addSql('CREATE INDEX FK_ECE71C8E82E48DB5 ON listejeusuivis (jeu)');
        $this->addSql('ALTER TABLE listejeusuivis ADD CONSTRAINT FK_ECE71C8E82E48DB5 FOREIGN KEY (jeu) REFERENCES jeu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE maj DROP FOREIGN KEY FK_6ECF53C182E48DB5');
        $this->addSql('ALTER TABLE maj DROP FOREIGN KEY FK_6ECF53C12B1333E3');
        $this->addSql('DROP INDEX jeu ON maj');
        $this->addSql('CREATE INDEX FK_6ECF53C182E48DB5 ON maj (jeu)');
        $this->addSql('DROP INDEX proto ON maj');
        $this->addSql('CREATE INDEX FK_6ECF53C12B1333E3 ON maj (proto)');
        $this->addSql('ALTER TABLE maj ADD CONSTRAINT FK_6ECF53C182E48DB5 FOREIGN KEY (jeu) REFERENCES jeu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE maj ADD CONSTRAINT FK_6ECF53C12B1333E3 FOREIGN KEY (proto) REFERENCES proto (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE messagerie DROP FOREIGN KEY FK_14E8F60C9E6AFC01');
        $this->addSql('ALTER TABLE messagerie DROP FOREIGN KEY FK_14E8F60C6160C427');
        $this->addSql('ALTER TABLE messagerie ADD CONSTRAINT messagerie_ibfk_1 FOREIGN KEY (envoyer) REFERENCES user (id)');
        $this->addSql('ALTER TABLE messagerie ADD CONSTRAINT messagerie_ibfk_2 FOREIGN KEY (receveur) REFERENCES user (id)');
        $this->addSql('ALTER TABLE proto DROP FOREIGN KEY FK_2B1333E355AB140');
        $this->addSql('ALTER TABLE proto ADD CONSTRAINT proto_ibfk_1 FOREIGN KEY (auteur) REFERENCES user (id)');
        $this->addSql('ALTER TABLE tchat DROP FOREIGN KEY FK_8EA99CA48D93D649');
        $this->addSql('ALTER TABLE tchat ADD CONSTRAINT tchat_ibfk_1 FOREIGN KEY (user) REFERENCES user (id)');
    }
}
