<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210319145647 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blog DROP FOREIGN KEY blog_ibfk_2');
        $this->addSql('ALTER TABLE blog ADD CONSTRAINT FK_C015514382E48DB5 FOREIGN KEY (jeu) REFERENCES jeu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE com DROP FOREIGN KEY com_ibfk_2');
        $this->addSql('ALTER TABLE com DROP FOREIGN KEY com_ibfk_3');
        $this->addSql('ALTER TABLE com DROP FOREIGN KEY com_ibfk_4');
        $this->addSql('ALTER TABLE com ADD CONSTRAINT FK_64B6C6E682E48DB5 FOREIGN KEY (jeu) REFERENCES jeu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE com ADD CONSTRAINT FK_64B6C6E6C0155143 FOREIGN KEY (blog) REFERENCES blog (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE com ADD CONSTRAINT FK_64B6C6E62B1333E3 FOREIGN KEY (proto) REFERENCES proto (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fichiercommunication DROP FOREIGN KEY fichiercommunication_ibfk_1');
        $this->addSql('ALTER TABLE fichiercommunication DROP FOREIGN KEY fichiercommunication_ibfk_2');
        $this->addSql('ALTER TABLE fichiercommunication DROP FOREIGN KEY fichiercommunication_ibfk_3');
        $this->addSql('ALTER TABLE fichiercommunication ADD CONSTRAINT FK_E1DC00B664B6C6E6 FOREIGN KEY (com) REFERENCES com (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fichiercommunication ADD CONSTRAINT FK_E1DC00B68EA99CA4 FOREIGN KEY (tchat) REFERENCES tchat (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fichiercommunication ADD CONSTRAINT FK_E1DC00B614E8F60C FOREIGN KEY (messagerie) REFERENCES messagerie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE imagecommunication DROP FOREIGN KEY imagecommunication_ibfk_1');
        $this->addSql('ALTER TABLE imagecommunication DROP FOREIGN KEY imagecommunication_ibfk_2');
        $this->addSql('ALTER TABLE imagecommunication DROP FOREIGN KEY imagecommunication_ibfk_3');
        $this->addSql('ALTER TABLE imagecommunication ADD CONSTRAINT FK_A93BAF2E64B6C6E6 FOREIGN KEY (com) REFERENCES com (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE imagecommunication ADD CONSTRAINT FK_A93BAF2E8EA99CA4 FOREIGN KEY (tchat) REFERENCES tchat (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE imagecommunication ADD CONSTRAINT FK_A93BAF2E14E8F60C FOREIGN KEY (messagerie) REFERENCES messagerie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE imagejeuproto DROP FOREIGN KEY imagejeuproto_ibfk_1');
        $this->addSql('ALTER TABLE imagejeuproto DROP FOREIGN KEY imagejeuproto_ibfk_2');
        $this->addSql('ALTER TABLE imagejeuproto DROP FOREIGN KEY FK_AA01EE75C0155143');
        $this->addSql('ALTER TABLE imagejeuproto ADD CONSTRAINT FK_AA01EE7582E48DB5 FOREIGN KEY (jeu) REFERENCES jeu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE imagejeuproto ADD CONSTRAINT FK_AA01EE752B1333E3 FOREIGN KEY (proto) REFERENCES proto (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE imagejeuproto ADD CONSTRAINT FK_AA01EE75C0155143 FOREIGN KEY (blog) REFERENCES blog (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE listecategorie DROP FOREIGN KEY listecategorie_ibfk_2');
        $this->addSql('ALTER TABLE listecategorie DROP FOREIGN KEY listecategorie_ibfk_3');
        $this->addSql('ALTER TABLE listecategorie ADD CONSTRAINT FK_7B60C8AE82E48DB5 FOREIGN KEY (jeu) REFERENCES jeu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE listecategorie ADD CONSTRAINT FK_7B60C8AE2B1333E3 FOREIGN KEY (proto) REFERENCES proto (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE listecontributeur DROP FOREIGN KEY listecontributeur_ibfk_2');
        $this->addSql('ALTER TABLE listecontributeur DROP FOREIGN KEY listecontributeur_ibfk_3');
        $this->addSql('ALTER TABLE listecontributeur ADD CONSTRAINT FK_C02FF782E48DB5 FOREIGN KEY (jeu) REFERENCES jeu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE listecontributeur ADD CONSTRAINT FK_C02FF72B1333E3 FOREIGN KEY (proto) REFERENCES proto (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE listejeusuivis DROP FOREIGN KEY listejeusuivis_ibfk_2');
        $this->addSql('ALTER TABLE listejeusuivis ADD CONSTRAINT FK_ECE71C8E82E48DB5 FOREIGN KEY (jeu) REFERENCES jeu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE maj DROP FOREIGN KEY maj_ibfk_1');
        $this->addSql('ALTER TABLE maj DROP FOREIGN KEY maj_ibfk_2');
        $this->addSql('ALTER TABLE maj ADD CONSTRAINT FK_6ECF53C182E48DB5 FOREIGN KEY (jeu) REFERENCES jeu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE maj ADD CONSTRAINT FK_6ECF53C12B1333E3 FOREIGN KEY (proto) REFERENCES proto (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blog DROP FOREIGN KEY FK_C015514382E48DB5');
        $this->addSql('ALTER TABLE blog ADD CONSTRAINT blog_ibfk_2 FOREIGN KEY (jeu) REFERENCES jeu (id)');
        $this->addSql('ALTER TABLE com DROP FOREIGN KEY FK_64B6C6E682E48DB5');
        $this->addSql('ALTER TABLE com DROP FOREIGN KEY FK_64B6C6E6C0155143');
        $this->addSql('ALTER TABLE com DROP FOREIGN KEY FK_64B6C6E62B1333E3');
        $this->addSql('ALTER TABLE com ADD CONSTRAINT com_ibfk_2 FOREIGN KEY (jeu) REFERENCES jeu (id)');
        $this->addSql('ALTER TABLE com ADD CONSTRAINT com_ibfk_3 FOREIGN KEY (blog) REFERENCES blog (id)');
        $this->addSql('ALTER TABLE com ADD CONSTRAINT com_ibfk_4 FOREIGN KEY (proto) REFERENCES proto (id)');
        $this->addSql('ALTER TABLE fichiercommunication DROP FOREIGN KEY FK_E1DC00B664B6C6E6');
        $this->addSql('ALTER TABLE fichiercommunication DROP FOREIGN KEY FK_E1DC00B68EA99CA4');
        $this->addSql('ALTER TABLE fichiercommunication DROP FOREIGN KEY FK_E1DC00B614E8F60C');
        $this->addSql('ALTER TABLE fichiercommunication ADD CONSTRAINT fichiercommunication_ibfk_1 FOREIGN KEY (com) REFERENCES com (id)');
        $this->addSql('ALTER TABLE fichiercommunication ADD CONSTRAINT fichiercommunication_ibfk_2 FOREIGN KEY (tchat) REFERENCES tchat (id)');
        $this->addSql('ALTER TABLE fichiercommunication ADD CONSTRAINT fichiercommunication_ibfk_3 FOREIGN KEY (messagerie) REFERENCES messagerie (id)');
        $this->addSql('ALTER TABLE imagecommunication DROP FOREIGN KEY FK_A93BAF2E64B6C6E6');
        $this->addSql('ALTER TABLE imagecommunication DROP FOREIGN KEY FK_A93BAF2E8EA99CA4');
        $this->addSql('ALTER TABLE imagecommunication DROP FOREIGN KEY FK_A93BAF2E14E8F60C');
        $this->addSql('ALTER TABLE imagecommunication ADD CONSTRAINT imagecommunication_ibfk_1 FOREIGN KEY (com) REFERENCES com (id)');
        $this->addSql('ALTER TABLE imagecommunication ADD CONSTRAINT imagecommunication_ibfk_2 FOREIGN KEY (tchat) REFERENCES tchat (id)');
        $this->addSql('ALTER TABLE imagecommunication ADD CONSTRAINT imagecommunication_ibfk_3 FOREIGN KEY (messagerie) REFERENCES messagerie (id)');
        $this->addSql('ALTER TABLE imagejeuproto DROP FOREIGN KEY FK_AA01EE7582E48DB5');
        $this->addSql('ALTER TABLE imagejeuproto DROP FOREIGN KEY FK_AA01EE752B1333E3');
        $this->addSql('ALTER TABLE imagejeuproto DROP FOREIGN KEY FK_AA01EE75C0155143');
        $this->addSql('ALTER TABLE imagejeuproto ADD CONSTRAINT imagejeuproto_ibfk_1 FOREIGN KEY (jeu) REFERENCES jeu (id)');
        $this->addSql('ALTER TABLE imagejeuproto ADD CONSTRAINT imagejeuproto_ibfk_2 FOREIGN KEY (proto) REFERENCES proto (id)');
        $this->addSql('ALTER TABLE imagejeuproto ADD CONSTRAINT FK_AA01EE75C0155143 FOREIGN KEY (blog) REFERENCES blog (id)');
        $this->addSql('ALTER TABLE listecategorie DROP FOREIGN KEY FK_7B60C8AE82E48DB5');
        $this->addSql('ALTER TABLE listecategorie DROP FOREIGN KEY FK_7B60C8AE2B1333E3');
        $this->addSql('ALTER TABLE listecategorie ADD CONSTRAINT listecategorie_ibfk_2 FOREIGN KEY (jeu) REFERENCES jeu (id)');
        $this->addSql('ALTER TABLE listecategorie ADD CONSTRAINT listecategorie_ibfk_3 FOREIGN KEY (proto) REFERENCES proto (id)');
        $this->addSql('ALTER TABLE listecontributeur DROP FOREIGN KEY FK_C02FF782E48DB5');
        $this->addSql('ALTER TABLE listecontributeur DROP FOREIGN KEY FK_C02FF72B1333E3');
        $this->addSql('ALTER TABLE listecontributeur ADD CONSTRAINT listecontributeur_ibfk_2 FOREIGN KEY (jeu) REFERENCES jeu (id)');
        $this->addSql('ALTER TABLE listecontributeur ADD CONSTRAINT listecontributeur_ibfk_3 FOREIGN KEY (proto) REFERENCES proto (id)');
        $this->addSql('ALTER TABLE listejeusuivis DROP FOREIGN KEY FK_ECE71C8E82E48DB5');
        $this->addSql('ALTER TABLE listejeusuivis ADD CONSTRAINT listejeusuivis_ibfk_2 FOREIGN KEY (jeu) REFERENCES jeu (id)');
        $this->addSql('ALTER TABLE maj DROP FOREIGN KEY FK_6ECF53C182E48DB5');
        $this->addSql('ALTER TABLE maj DROP FOREIGN KEY FK_6ECF53C12B1333E3');
        $this->addSql('ALTER TABLE maj ADD CONSTRAINT maj_ibfk_1 FOREIGN KEY (jeu) REFERENCES jeu (id)');
        $this->addSql('ALTER TABLE maj ADD CONSTRAINT maj_ibfk_2 FOREIGN KEY (proto) REFERENCES proto (id)');
    }
}
