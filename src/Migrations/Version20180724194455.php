<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180724194455 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE store_migration CHANGE store_id store_id INT NOT NULL');
        $this->addSql('ALTER TABLE store CHANGE store_entity_role_id store_entity_role_id INT NOT NULL, CHANGE user_id user_id INT NOT NULL, CHANGE conexion_id conexion_id INT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE store CHANGE store_entity_role_id store_entity_role_id INT DEFAULT NULL, CHANGE user_id user_id INT DEFAULT NULL, CHANGE conexion_id conexion_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE store_migration CHANGE store_id store_id INT DEFAULT NULL');
    }
}
