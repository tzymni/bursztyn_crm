<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190613173920 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE reservations (id INT AUTO_INCREMENT NOT NULL, cottage_id INT DEFAULT NULL, date_from DATE NOT NULL, date_to DATE NOT NULL, is_active TINYINT(1) DEFAULT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_4DA2395BD48B97 (cottage_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reservations ADD CONSTRAINT FK_4DA2395BD48B97 FOREIGN KEY (cottage_id) REFERENCES cottages (id)');
//        $this->addSql('ALTER TABLE user ADD PRIMARY KEY (email)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE reservations');
        $this->addSql('ALTER TABLE user DROP PRIMARY KEY');
    }
}
