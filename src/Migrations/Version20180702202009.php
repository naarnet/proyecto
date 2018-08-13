<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180702202009 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE store (id INT AUTO_INCREMENT NOT NULL, store_entity_type_id INT DEFAULT NULL, store_entity_role_id INT DEFAULT NULL, user_id INT DEFAULT NULL, conexion_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_FF5758774117A36E (store_entity_type_id), INDEX IDX_FF57587752580D51 (store_entity_role_id), INDEX IDX_FF575877A76ED395 (user_id), INDEX IDX_FF57587727105691 (conexion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE store_category (id INT AUTO_INCREMENT NOT NULL, store_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, is_active TINYINT(1) NOT NULL, store_category_id INT NOT NULL, parent_category_id INT DEFAULT NULL, INDEX IDX_383B663BB092A811 (store_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE store_migration (id INT AUTO_INCREMENT NOT NULL, store_id INT DEFAULT NULL, INDEX IDX_9E4AD9BFB092A811 (store_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE store_entity_role (id INT AUTO_INCREMENT NOT NULL, role_name VARCHAR(255) NOT NULL, role_code VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE store_entity_type (id INT AUTO_INCREMENT NOT NULL, type_name VARCHAR(255) NOT NULL, type_code VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE store ADD CONSTRAINT FK_FF5758774117A36E FOREIGN KEY (store_entity_type_id) REFERENCES store_entity_type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE store ADD CONSTRAINT FK_FF57587752580D51 FOREIGN KEY (store_entity_role_id) REFERENCES store_entity_role (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE store ADD CONSTRAINT FK_FF575877A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE store ADD CONSTRAINT FK_FF57587727105691 FOREIGN KEY (conexion_id) REFERENCES conexion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE store_category ADD CONSTRAINT FK_383B663BB092A811 FOREIGN KEY (store_id) REFERENCES store (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE store_migration ADD CONSTRAINT FK_9E4AD9BFB092A811 FOREIGN KEY (store_id) REFERENCES store (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE store_category DROP FOREIGN KEY FK_383B663BB092A811');
        $this->addSql('ALTER TABLE store_migration DROP FOREIGN KEY FK_9E4AD9BFB092A811');
        $this->addSql('ALTER TABLE store DROP FOREIGN KEY FK_FF57587752580D51');
        $this->addSql('ALTER TABLE store DROP FOREIGN KEY FK_FF5758774117A36E');
        $this->addSql('DROP TABLE store');
        $this->addSql('DROP TABLE store_category');
        $this->addSql('DROP TABLE store_migration');
        $this->addSql('DROP TABLE store_entity_role');
        $this->addSql('DROP TABLE store_entity_type');
    }
}
