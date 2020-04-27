<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200413125743 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE reservations_cottages (reservations_id INT NOT NULL, cottages_id INT NOT NULL, INDEX IDX_A43B78E3D9A7F869 (reservations_id), INDEX IDX_A43B78E38E608045 (cottages_id), PRIMARY KEY(reservations_id, cottages_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reservations_cottages ADD CONSTRAINT FK_A43B78E3D9A7F869 FOREIGN KEY (reservations_id) REFERENCES reservations (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservations_cottages ADD CONSTRAINT FK_A43B78E38E608045 FOREIGN KEY (cottages_id) REFERENCES cottages (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservations ADD event_id INT NOT NULL, ADD guest_name VARCHAR(255) DEFAULT NULL, ADD guest_last_name VARCHAR(255) DEFAULT NULL, ADD guests_number INT DEFAULT NULL, ADD payment_on_account TINYINT(1) NOT NULL, ADD is_active TINYINT(1) NOT NULL, ADD phone_number VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE reservations ADD CONSTRAINT FK_4DA23971F7E88B FOREIGN KEY (event_id) REFERENCES events (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4DA23971F7E88B ON reservations (event_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE reservations_cottages');
        $this->addSql('ALTER TABLE reservations DROP FOREIGN KEY FK_4DA23971F7E88B');
        $this->addSql('DROP INDEX UNIQ_4DA23971F7E88B ON reservations');
        $this->addSql('ALTER TABLE reservations DROP event_id, DROP guest_name, DROP guest_last_name, DROP guests_number, DROP payment_on_account, DROP is_active, DROP phone_number');
    }
}
