<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200413124138 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE reservations DROP FOREIGN KEY FK_4DA23917FF9E93');
        $this->addSql('DROP INDEX IDX_4DA23917FF9E93 ON reservations');
        $this->addSql('ALTER TABLE reservations DROP cottage_id, DROP date_from, DROP date_to, DROP is_active, DROP type, DROP tourist_name, DROP tourist_num');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE reservations ADD cottage_id INT DEFAULT NULL, ADD date_from DATE NOT NULL, ADD date_to DATE NOT NULL, ADD is_active TINYINT(1) DEFAULT NULL, ADD type VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD tourist_name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD tourist_num INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservations ADD CONSTRAINT FK_4DA23917FF9E93 FOREIGN KEY (cottage_id) REFERENCES cottages (id)');
        $this->addSql('CREATE INDEX IDX_4DA23917FF9E93 ON reservations (cottage_id)');
    }
}
