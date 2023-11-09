<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231109104516 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sports_activity ADD user_id INT DEFAULT NULL, ADD type_activity_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sports_activity ADD CONSTRAINT FK_FC575709A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE sports_activity ADD CONSTRAINT FK_FC575709CAD9B707 FOREIGN KEY (type_activity_id) REFERENCES type_activity (id)');
        $this->addSql('CREATE INDEX IDX_FC575709A76ED395 ON sports_activity (user_id)');
        $this->addSql('CREATE INDEX IDX_FC575709CAD9B707 ON sports_activity (type_activity_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sports_activity DROP FOREIGN KEY FK_FC575709A76ED395');
        $this->addSql('ALTER TABLE sports_activity DROP FOREIGN KEY FK_FC575709CAD9B707');
        $this->addSql('DROP INDEX IDX_FC575709A76ED395 ON sports_activity');
        $this->addSql('DROP INDEX IDX_FC575709CAD9B707 ON sports_activity');
        $this->addSql('ALTER TABLE sports_activity DROP user_id, DROP type_activity_id');
    }
}
