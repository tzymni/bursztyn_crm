<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210518202426 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user_presences (id INT AUTO_INCREMENT NOT NULL, event_id INT NOT NULL, user_id INT NOT NULL, extra_info VARCHAR(255) DEFAULT NULL, date_add DATETIME NOT NULL, UNIQUE INDEX UNIQ_BD3B624F71F7E88B (event_id), INDEX IDX_BD3B624FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_presences ADD CONSTRAINT FK_BD3B624F71F7E88B FOREIGN KEY (event_id) REFERENCES events (id)');
        $this->addSql('ALTER TABLE user_presences ADD CONSTRAINT FK_BD3B624FA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE cottages CHANGE color color VARCHAR(8) DEFAULT NULL, CHANGE is_active is_active TINYINT(1) DEFAULT NULL, CHANGE max_guests_number max_guests_number INT DEFAULT NULL, CHANGE external_id external_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cottages_cleaning_events CHANGE cottage_id cottage_id INT DEFAULT NULL, CHANGE event_id event_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE events CHANGE created_by_id created_by_id INT DEFAULT NULL, CHANGE title title VARCHAR(255) DEFAULT NULL, CHANGE is_active is_active TINYINT(1) DEFAULT NULL, CHANGE type type ENUM(\'RESERVATION\', \'CLEANING\', \'PRESENCE\'), CHANGE date_from date_from VARCHAR(255) DEFAULT NULL, CHANGE date_to date_to VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE reservations CHANGE event_id event_id INT DEFAULT NULL, CHANGE cottage_id cottage_id INT DEFAULT NULL, CHANGE guest_last_name guest_last_name VARCHAR(255) DEFAULT NULL, CHANGE guest_phone_number guest_phone_number VARCHAR(255) DEFAULT NULL, CHANGE advance_payment advance_payment TINYINT(1) DEFAULT NULL, CHANGE extra_info extra_info VARCHAR(255) DEFAULT NULL, CHANGE is_active is_active TINYINT(1) DEFAULT NULL, CHANGE advance advance DOUBLE PRECISION DEFAULT NULL, CHANGE price price DOUBLE PRECISION DEFAULT NULL, CHANGE external_id external_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE user_presences');
        $this->addSql('ALTER TABLE cottages CHANGE color color VARCHAR(8) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE is_active is_active TINYINT(1) DEFAULT \'NULL\', CHANGE max_guests_number max_guests_number INT DEFAULT NULL, CHANGE external_id external_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cottages_cleaning_events CHANGE cottage_id cottage_id INT DEFAULT NULL, CHANGE event_id event_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE events CHANGE created_by_id created_by_id INT DEFAULT NULL, CHANGE title title VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE is_active is_active TINYINT(1) DEFAULT \'NULL\', CHANGE type type VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE date_from date_from VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE date_to date_to VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE reservations CHANGE event_id event_id INT DEFAULT NULL, CHANGE cottage_id cottage_id INT DEFAULT NULL, CHANGE guest_last_name guest_last_name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE guest_phone_number guest_phone_number VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE advance_payment advance_payment TINYINT(1) DEFAULT \'NULL\', CHANGE extra_info extra_info VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE is_active is_active TINYINT(1) DEFAULT \'NULL\', CHANGE advance advance DOUBLE PRECISION DEFAULT \'NULL\', CHANGE price price DOUBLE PRECISION DEFAULT \'NULL\', CHANGE external_id external_id INT DEFAULT NULL');
    }
}
