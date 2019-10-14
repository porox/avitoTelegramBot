<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191008224648 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TEMPORARY TABLE __temp__search_query AS SELECT id, "query", created_at, blocked FROM search_query');
        $this->addSql('DROP TABLE search_query');
        $this->addSql('CREATE TABLE search_query (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, "query" VARCHAR(255) NOT NULL COLLATE BINARY, blocked BOOLEAN DEFAULT \'0\' NOT NULL, created_at DATETIME DEFAULT NULL --Дата и время обновления
        )');
        $this->addSql('INSERT INTO search_query (id, "query", created_at, blocked) SELECT id, "query", created_at, blocked FROM __temp__search_query');
        $this->addSql('DROP TABLE __temp__search_query');
        $this->addSql('DROP INDEX IDX_838B98D9FC28B263');
        $this->addSql('DROP INDEX IDX_838B98D9FFC3C42C');
        $this->addSql('CREATE TEMPORARY TABLE __temp__sq_usr AS SELECT search_query_id, telegram_user_id FROM sq_usr');
        $this->addSql('DROP TABLE sq_usr');
        $this->addSql('CREATE TABLE sq_usr (search_query_id INTEGER NOT NULL, telegram_user_id INTEGER NOT NULL, PRIMARY KEY(search_query_id, telegram_user_id), CONSTRAINT FK_838B98D9FFC3C42C FOREIGN KEY (search_query_id) REFERENCES search_query (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_838B98D9FC28B263 FOREIGN KEY (telegram_user_id) REFERENCES telegram_user (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO sq_usr (search_query_id, telegram_user_id) SELECT search_query_id, telegram_user_id FROM __temp__sq_usr');
        $this->addSql('DROP TABLE __temp__sq_usr');
        $this->addSql('CREATE INDEX IDX_838B98D9FC28B263 ON sq_usr (telegram_user_id)');
        $this->addSql('CREATE INDEX IDX_838B98D9FFC3C42C ON sq_usr (search_query_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__advertisement AS SELECT id, price, link, created_at, hash FROM advertisement');
        $this->addSql('DROP TABLE advertisement');
        $this->addSql('CREATE TABLE advertisement (id INTEGER NOT NULL, price VARCHAR(255) NOT NULL COLLATE BINARY, link VARCHAR(255) NOT NULL COLLATE BINARY, hash VARCHAR(255) NOT NULL COLLATE BINARY, created_at DATETIME DEFAULT NULL --Дата и время обновления
        , PRIMARY KEY(id))');
        $this->addSql('INSERT INTO advertisement (id, price, link, created_at, hash) SELECT id, price, link, created_at, hash FROM __temp__advertisement');
        $this->addSql('DROP TABLE __temp__advertisement');
        $this->addSql('DROP INDEX IDX_147C5183FFC3C42C');
        $this->addSql('DROP INDEX IDX_147C5183A1FBF71B');
        $this->addSql('CREATE TEMPORARY TABLE __temp__adv_sq AS SELECT advertisement_id, search_query_id FROM adv_sq');
        $this->addSql('DROP TABLE adv_sq');
        $this->addSql('CREATE TABLE adv_sq (advertisement_id INTEGER NOT NULL, search_query_id INTEGER NOT NULL, PRIMARY KEY(advertisement_id, search_query_id), CONSTRAINT FK_147C5183A1FBF71B FOREIGN KEY (advertisement_id) REFERENCES advertisement (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_147C5183FFC3C42C FOREIGN KEY (search_query_id) REFERENCES search_query (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO adv_sq (advertisement_id, search_query_id) SELECT advertisement_id, search_query_id FROM __temp__adv_sq');
        $this->addSql('DROP TABLE __temp__adv_sq');
        $this->addSql('CREATE INDEX IDX_147C5183FFC3C42C ON adv_sq (search_query_id)');
        $this->addSql('CREATE INDEX IDX_147C5183A1FBF71B ON adv_sq (advertisement_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX IDX_147C5183A1FBF71B');
        $this->addSql('DROP INDEX IDX_147C5183FFC3C42C');
        $this->addSql('CREATE TEMPORARY TABLE __temp__adv_sq AS SELECT advertisement_id, search_query_id FROM adv_sq');
        $this->addSql('DROP TABLE adv_sq');
        $this->addSql('CREATE TABLE adv_sq (advertisement_id INTEGER NOT NULL, search_query_id INTEGER NOT NULL, PRIMARY KEY(advertisement_id, search_query_id))');
        $this->addSql('INSERT INTO adv_sq (advertisement_id, search_query_id) SELECT advertisement_id, search_query_id FROM __temp__adv_sq');
        $this->addSql('DROP TABLE __temp__adv_sq');
        $this->addSql('CREATE INDEX IDX_147C5183A1FBF71B ON adv_sq (advertisement_id)');
        $this->addSql('CREATE INDEX IDX_147C5183FFC3C42C ON adv_sq (search_query_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__advertisement AS SELECT id, price, link, created_at, hash FROM advertisement');
        $this->addSql('DROP TABLE advertisement');
        $this->addSql('CREATE TABLE advertisement (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, price VARCHAR(255) NOT NULL, link VARCHAR(255) NOT NULL, hash VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT \'NULL --Дата и время обновления\' --Дата и время обновления
        )');
        $this->addSql('INSERT INTO advertisement (id, price, link, created_at, hash) SELECT id, price, link, created_at, hash FROM __temp__advertisement');
        $this->addSql('DROP TABLE __temp__advertisement');
        $this->addSql('CREATE TEMPORARY TABLE __temp__search_query AS SELECT id, "query", created_at, blocked FROM search_query');
        $this->addSql('DROP TABLE search_query');
        $this->addSql('CREATE TABLE search_query (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, "query" VARCHAR(255) NOT NULL, blocked BOOLEAN DEFAULT \'0\' NOT NULL, created_at DATETIME DEFAULT \'NULL --Дата и время обновления\' --Дата и время обновления
        )');
        $this->addSql('INSERT INTO search_query (id, "query", created_at, blocked) SELECT id, "query", created_at, blocked FROM __temp__search_query');
        $this->addSql('DROP TABLE __temp__search_query');
        $this->addSql('DROP INDEX IDX_838B98D9FFC3C42C');
        $this->addSql('DROP INDEX IDX_838B98D9FC28B263');
        $this->addSql('CREATE TEMPORARY TABLE __temp__sq_usr AS SELECT search_query_id, telegram_user_id FROM sq_usr');
        $this->addSql('DROP TABLE sq_usr');
        $this->addSql('CREATE TABLE sq_usr (search_query_id INTEGER NOT NULL, telegram_user_id INTEGER NOT NULL, PRIMARY KEY(search_query_id, telegram_user_id))');
        $this->addSql('INSERT INTO sq_usr (search_query_id, telegram_user_id) SELECT search_query_id, telegram_user_id FROM __temp__sq_usr');
        $this->addSql('DROP TABLE __temp__sq_usr');
        $this->addSql('CREATE INDEX IDX_838B98D9FFC3C42C ON sq_usr (search_query_id)');
        $this->addSql('CREATE INDEX IDX_838B98D9FC28B263 ON sq_usr (telegram_user_id)');
    }
}
