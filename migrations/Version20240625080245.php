<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240625080245 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $table = $schema->createTable('people');
        $table->addColumn(
            'id',
            'string',
        );
        $table->addColumn(
            'name',
            'string',
        );
        $table->addColumn(
            'user_id',
            'string',
            [
                'notNull' => false,
            ]
        );
        $table->addColumn(
            'created_at',
            'string',
        );
        $table->addColumn(
            'updated_at',
            'string',
        );
        $table->addColumn(
            'deleted_at',
            'string',
            [
                'notNull' => false,
            ]
        );

        $table->setPrimaryKey(['id']);
        $table->addForeignKeyConstraint('users', ['user_id'], ['id']);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
    }
}
