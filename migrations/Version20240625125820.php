<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240625125820 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $table = $schema->createTable('teams');
        $table->addColumn(
            'id',
            'string',
        );
        $table->addColumn(
            'name',
            'string',
        );
        $table->addColumn(
            'description',
            'string',
            [
                'notNull' => false,
            ]
        );
        $table->addColumn(
            'sport_id',
            'string'
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
        $table->addForeignKeyConstraint('sports', ['sport_id'], ['id']);

        $pivot = $schema->createTable('team_people');
        $pivot->addColumn(
            'team_id',
            'string'
        );
        $pivot->addColumn(
            'person_id',
            'string'
        );
        $pivot->setPrimaryKey(['team_id', 'person_id']);
        $pivot->addForeignKeyConstraint('teams', ['team_id'], ['id']);
        $pivot->addForeignKeyConstraint('people', ['person_id'], ['id']);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
    }
}
