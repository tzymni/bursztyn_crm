<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190624191059 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

//        $this->addSql('ALTER TABLE user ADD PRIMARY KEY (email)');
        $this->addSql('ALTER TABLE reservations DROP FOREIGN KEY FK_4DA2395BD48B97');
        $this->addSql('DROP INDEX IDX_4DA2395BD48B97 ON reservations');
        $this->addSql('ALTER TABLE reservations DROP cottage_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE reservations ADD cottage_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservations ADD CONSTRAINT FK_4DA2395BD48B97 FOREIGN KEY (cottage_id) REFERENCES cottages (id)');
        $this->addSql('CREATE INDEX IDX_4DA2395BD48B97 ON reservations (cottage_id)');
        $this->addSql('ALTER TABLE user DROP PRIMARY KEY');
    }
}
