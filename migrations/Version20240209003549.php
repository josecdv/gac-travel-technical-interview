<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240209003549 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE products DROP FOREIGN KEY products_ibfk_1');
        $this->addSql('DROP INDEX categoria_id ON products');
        $this->addSql('ALTER TABLE products DROP categoria_id');
        $this->addSql('ALTER TABLE stock_historic DROP FOREIGN KEY stock_historic_ibfk_1');
        $this->addSql('ALTER TABLE stock_historic DROP FOREIGN KEY stock_historic_ibfk_2');
        $this->addSql('DROP INDEX user_id ON stock_historic');
        $this->addSql('DROP INDEX product_id ON stock_historic');
        $this->addSql('ALTER TABLE stock_historic DROP user_id, DROP product_id');
        $this->addSql('ALTER TABLE users ADD roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE products ADD categoria_id INT NOT NULL');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT products_ibfk_1 FOREIGN KEY (categoria_id) REFERENCES categories (id) ON UPDATE CASCADE');
        $this->addSql('CREATE INDEX categoria_id ON products (categoria_id)');
        $this->addSql('ALTER TABLE stock_historic ADD user_id INT NOT NULL, ADD product_id INT NOT NULL');
        $this->addSql('ALTER TABLE stock_historic ADD CONSTRAINT stock_historic_ibfk_1 FOREIGN KEY (user_id) REFERENCES users (id) ON UPDATE CASCADE');
        $this->addSql('ALTER TABLE stock_historic ADD CONSTRAINT stock_historic_ibfk_2 FOREIGN KEY (product_id) REFERENCES products (id) ON UPDATE CASCADE');
        $this->addSql('CREATE INDEX user_id ON stock_historic (user_id)');
        $this->addSql('CREATE INDEX product_id ON stock_historic (product_id)');
        $this->addSql('ALTER TABLE users DROP roles');
    }
}
