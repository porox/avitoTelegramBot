<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191008220616 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE search_query (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, "query" VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL --Дата и время обновления
        , blocked BOOLEAN DEFAULT \'0\' NOT NULL)');
        $this->addSql('CREATE TABLE sq_usr (search_query_id INTEGER NOT NULL, telegram_user_id INTEGER NOT NULL, PRIMARY KEY(search_query_id, telegram_user_id))');
        $this->addSql('CREATE INDEX IDX_838B98D9FFC3C42C ON sq_usr (search_query_id)');
        $this->addSql('CREATE INDEX IDX_838B98D9FC28B263 ON sq_usr (telegram_user_id)');
        $this->addSql('CREATE TABLE telegram_user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, telegram_chat_id VARCHAR(255) NOT NULL, telegram_info CLOB NOT NULL --(DC2Type:json_array)
        , blocked BOOLEAN DEFAULT \'0\' NOT NULL)');
        $this->addSql('CREATE TABLE advertisement (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, price VARCHAR(255) NOT NULL, link VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL --Дата и время обновления
        , hash VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE adv_sq (advertisement_id INTEGER NOT NULL, search_query_id INTEGER NOT NULL, PRIMARY KEY(advertisement_id, search_query_id))');
        $this->addSql('CREATE INDEX IDX_147C5183A1FBF71B ON adv_sq (advertisement_id)');
        $this->addSql('CREATE INDEX IDX_147C5183FFC3C42C ON adv_sq (search_query_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE search_query');
        $this->addSql('DROP TABLE sq_usr');
        $this->addSql('DROP TABLE telegram_user');
        $this->addSql('DROP TABLE advertisement');
        $this->addSql('DROP TABLE adv_sq');
    }
}
