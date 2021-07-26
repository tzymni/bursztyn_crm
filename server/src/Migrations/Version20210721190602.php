<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210721190602 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE cottages CHANGE color color VARCHAR(8) DEFAULT NULL, CHANGE is_active is_active TINYINT(1) DEFAULT NULL, CHANGE max_guests_number max_guests_number INT DEFAULT NULL, CHANGE external_id external_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cottages_cleaning_events CHANGE cottage_id cottage_id INT DEFAULT NULL, CHANGE event_id event_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE events CHANGE created_by_id created_by_id INT DEFAULT NULL, CHANGE title title VARCHAR(255) DEFAULT NULL, CHANGE is_active is_active TINYINT(1) DEFAULT NULL, CHANGE type type ENUM(\'RESERVATION\', \'CLEANING\', \'PRESENCE\'), CHANGE date_from date_from VARCHAR(255) DEFAULT NULL, CHANGE date_to date_to VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE reservations CHANGE event_id event_id INT DEFAULT NULL, CHANGE cottage_id cottage_id INT DEFAULT NULL, CHANGE guest_last_name guest_last_name VARCHAR(255) DEFAULT NULL, CHANGE guest_phone_number guest_phone_number VARCHAR(255) DEFAULT NULL, CHANGE advance_payment advance_payment TINYINT(1) DEFAULT NULL, CHANGE extra_info extra_info VARCHAR(255) DEFAULT NULL, CHANGE is_active is_active TINYINT(1) DEFAULT NULL, CHANGE advance advance DOUBLE PRECISION DEFAULT NULL, CHANGE price price DOUBLE PRECISION DEFAULT NULL, CHANGE external_id external_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_presences CHANGE extra_info extra_info VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD days_before_notification INT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE cottages CHANGE color color VARCHAR(8) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE is_active is_active TINYINT(1) DEFAULT \'NULL\', CHANGE max_guests_number max_guests_number INT DEFAULT NULL, CHANGE external_id external_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cottages_cleaning_events CHANGE cottage_id cottage_id INT DEFAULT NULL, CHANGE event_id event_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE events CHANGE created_by_id created_by_id INT DEFAULT NULL, CHANGE title title VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE is_active is_active TINYINT(1) DEFAULT \'NULL\', CHANGE type type VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE date_from date_from VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE date_to date_to VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE reservations CHANGE event_id event_id INT DEFAULT NULL, CHANGE cottage_id cottage_id INT DEFAULT NULL, CHANGE guest_last_name guest_last_name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE guest_phone_number guest_phone_number VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE advance_payment advance_payment TINYINT(1) DEFAULT \'NULL\', CHANGE extra_info extra_info VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE is_active is_active TINYINT(1) DEFAULT \'NULL\', CHANGE advance advance DOUBLE PRECISION DEFAULT \'NULL\', CHANGE price price DOUBLE PRECISION DEFAULT \'NULL\', CHANGE external_id external_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_presences CHANGE extra_info extra_info VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE users DROP days_before_notification');
    }
}
