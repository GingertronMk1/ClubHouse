<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240629170420 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $table = $schema->createTable('match_people');
        $table->addColumn(
            'match_id',
            'string'
        );
        $table->addColumn(
            'person_id',
            'string'
        );
        $table->addColumn(
            'role',
            'string',
            ['notNull' => false]
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
        $table->setPrimaryKey(['match_id', 'person_id']);

        $table->addForeignKeyConstraint('matches', ['match_id'], ['id']);
        $table->addForeignKeyConstraint('people', ['person_id'], ['id']);
 }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
