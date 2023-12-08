<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231206152808 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('SELECT setval(\'product_id_seq\', (SELECT MAX(id) FROM product))');
        $this->addSql('ALTER TABLE product ALTER id SET DEFAULT nextval(\'product_id_seq\')');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE product ALTER id DROP DEFAULT');
    }
}
