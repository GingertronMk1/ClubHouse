<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240627174214 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $table = $schema->createTable('matches');
        $table->addColumn(
            'id',
            'string',
        );
        $table->addColumn(
            'name',
            'string',
        );
        $table->addColumn(
            'details',
            'string',
            [
                'notNull' => false,
            ]
        );
        $table->addColumn(
            'start',
            'string',
            [
                'notNull' => false,
            ]
        );
        $table->addColumn(
            'team1_id',
            'string',
            [
                'notNull' => false,
            ]
        );
        $table->addColumn(
            'team2_id',
            'string',
            [
                'notNull' => false,
            ]
        );
        $table->addColumn(
            'sport_id',
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

        $table->addForeignKeyConstraint('teams', ['team1_id'], ['id']);
        $table->addForeignKeyConstraint('teams', ['team2_id'], ['id']);
        $table->addForeignKeyConstraint('sports', ['sport_id'], ['id']);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
