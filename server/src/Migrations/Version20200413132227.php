<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200413132227 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE reservations ADD event_id INT DEFAULT NULL, ADD cottage_id INT DEFAULT NULL, ADD guest_first_name VARCHAR(255) NOT NULL, ADD guest_last_name VARCHAR(255) DEFAULT NULL, ADD guest_phone_number VARCHAR(255) DEFAULT NULL, ADD guests_number INT NOT NULL, ADD advance_payment TINYINT(1) DEFAULT NULL, ADD extra_info VARCHAR(255) DEFAULT NULL, ADD is_active TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE reservations ADD CONSTRAINT FK_4DA23971F7E88B FOREIGN KEY (event_id) REFERENCES events (id)');
        $this->addSql('ALTER TABLE reservations ADD CONSTRAINT FK_4DA23917FF9E93 FOREIGN KEY (cottage_id) REFERENCES cottages (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4DA23971F7E88B ON reservations (event_id)');
        $this->addSql('CREATE INDEX IDX_4DA23917FF9E93 ON reservations (cottage_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE reservations DROP FOREIGN KEY FK_4DA23971F7E88B');
        $this->addSql('ALTER TABLE reservations DROP FOREIGN KEY FK_4DA23917FF9E93');
        $this->addSql('DROP INDEX UNIQ_4DA23971F7E88B ON reservations');
        $this->addSql('DROP INDEX IDX_4DA23917FF9E93 ON reservations');
        $this->addSql('ALTER TABLE reservations DROP event_id, DROP cottage_id, DROP guest_first_name, DROP guest_last_name, DROP guest_phone_number, DROP guests_number, DROP advance_payment, DROP extra_info, DROP is_active');
    }
}
