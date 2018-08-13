<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180514194837 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE conexion_mode (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE store ADD conexion_mode_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE store ADD CONSTRAINT FK_FF5758776503A698 FOREIGN KEY (conexion_mode_id) REFERENCES conexion_mode (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_FF5758776503A698 ON store (conexion_mode_id)');
        $this->addSql('ALTER TABLE store_credential ADD conexion_mode_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE store_credential ADD CONSTRAINT FK_2B9646A66503A698 FOREIGN KEY (conexion_mode_id) REFERENCES conexion_mode (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_2B9646A66503A698 ON store_credential (conexion_mode_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE store DROP FOREIGN KEY FK_FF5758776503A698');
        $this->addSql('ALTER TABLE store_credential DROP FOREIGN KEY FK_2B9646A66503A698');
        $this->addSql('DROP TABLE conexion_mode');
        $this->addSql('DROP INDEX IDX_FF5758776503A698 ON store');
        $this->addSql('ALTER TABLE store DROP conexion_mode_id');
        $this->addSql('DROP INDEX IDX_2B9646A66503A698 ON store_credential');
        $this->addSql('ALTER TABLE store_credential DROP conexion_mode_id');
    }
}
