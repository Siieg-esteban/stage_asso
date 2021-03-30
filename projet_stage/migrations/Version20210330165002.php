<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210330165002 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE imagefichierrequete DROP FOREIGN KEY FK_243E9D2618FB544F');
        $this->addSql('ALTER TABLE imagefichierrequete ADD CONSTRAINT FK_243E9D2618FB544F FOREIGN KEY (requete_id) REFERENCES requete_contributeur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE requete_contributeur DROP FOREIGN KEY FK_3C95CA3DA76ED395');
        $this->addSql('ALTER TABLE requete_contributeur ADD CONSTRAINT FK_3C95CA3DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE imagefichierrequete DROP FOREIGN KEY FK_243E9D2618FB544F');
        $this->addSql('ALTER TABLE imagefichierrequete ADD CONSTRAINT FK_243E9D2618FB544F FOREIGN KEY (requete_id) REFERENCES requete_contributeur (id)');
        $this->addSql('ALTER TABLE requete_contributeur DROP FOREIGN KEY FK_3C95CA3DA76ED395');
        $this->addSql('ALTER TABLE requete_contributeur ADD CONSTRAINT FK_3C95CA3DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }
}
