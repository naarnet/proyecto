<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180514201909 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE store DROP FOREIGN KEY FK_FF5758776503A698');
        $this->addSql('ALTER TABLE store_credential DROP FOREIGN KEY FK_2B9646A66503A698');
        $this->addSql('CREATE TABLE conexion (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE conexion_mode');
        $this->addSql('DROP INDEX IDX_FF5758776503A698 ON store');
        $this->addSql('ALTER TABLE store CHANGE conexion_mode_id conexion_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE store ADD CONSTRAINT FK_FF57587727105691 FOREIGN KEY (conexion_id) REFERENCES conexion (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_FF57587727105691 ON store (conexion_id)');
        $this->addSql('DROP INDEX IDX_2B9646A66503A698 ON store_credential');
        $this->addSql('ALTER TABLE store_credential CHANGE conexion_mode_id conexion_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE store_credential ADD CONSTRAINT FK_2B9646A627105691 FOREIGN KEY (conexion_id) REFERENCES conexion (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_2B9646A627105691 ON store_credential (conexion_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE store DROP FOREIGN KEY FK_FF57587727105691');
        $this->addSql('ALTER TABLE store_credential DROP FOREIGN KEY FK_2B9646A627105691');
        $this->addSql('CREATE TABLE conexion_mode (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE conexion');
        $this->addSql('DROP INDEX IDX_FF57587727105691 ON store');
        $this->addSql('ALTER TABLE store CHANGE conexion_id conexion_mode_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE store ADD CONSTRAINT FK_FF5758776503A698 FOREIGN KEY (conexion_mode_id) REFERENCES conexion_mode (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_FF5758776503A698 ON store (conexion_mode_id)');
        $this->addSql('DROP INDEX IDX_2B9646A627105691 ON store_credential');
        $this->addSql('ALTER TABLE store_credential CHANGE conexion_id conexion_mode_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE store_credential ADD CONSTRAINT FK_2B9646A66503A698 FOREIGN KEY (conexion_mode_id) REFERENCES conexion_mode (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_2B9646A66503A698 ON store_credential (conexion_mode_id)');
    }
}
