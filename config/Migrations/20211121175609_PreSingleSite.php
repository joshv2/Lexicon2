<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class PreSingleSite extends AbstractMigration
{
    /**
     * Up Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-up-method
     * @return void
     */
    public function up()
    {

        $this->table('words')
            ->changeColumn('created', 'timestamp', [
                'default' => 'current_timestamp()',
                'limit' => null,
                'null' => false,
            ])
            ->update();
        $this->table('alphabets', ['id' => false])
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('language_id', 'integer', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('UTF8value', 'char', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addIndex(
                [
                    'id',
                ],
                ['unique' => true]
            )
            ->create();

        $this->table('dictionaries')
            ->addColumn('language_id', 'integer', [
                'after' => 'dictionary',
                'default' => null,
                'length' => null,
                'null' => false,
            ])
            ->addColumn('top', 'boolean', [
                'after' => 'language_id',
                'default' => null,
                'length' => null,
                'null' => false,
            ])
            ->update();

        $this->table('languages')
            ->addColumn('i18nspec', 'string', [
                'after' => 'subdomain',
                'default' => null,
                'length' => 6,
                'null' => false,
            ])
            ->addColumn('translationfile', 'string', [
                'after' => 'i18nspec',
                'default' => null,
                'length' => 255,
                'null' => false,
            ])
            ->addColumn('HeaderImage', 'string', [
                'after' => 'translationfile',
                'default' => null,
                'length' => 255,
                'null' => false,
            ])
            ->addColumn('LogoImage', 'string', [
                'after' => 'HeaderImage',
                'default' => null,
                'length' => 255,
                'null' => false,
            ])
            ->addColumn('AboutSec1Header', 'text', [
                'after' => 'LogoImage',
                'default' => null,
                'length' => null,
                'null' => false,
            ])
            ->addColumn('AboutSec1Text', 'text', [
                'after' => 'AboutSec1Header',
                'default' => null,
                'length' => null,
                'null' => false,
            ])
            ->addColumn('AboutSec1Text_json', 'text', [
                'after' => 'AboutSec1Text',
                'default' => null,
                'length' => null,
                'null' => false,
            ])
            ->addColumn('AboutSec2Header', 'text', [
                'after' => 'AboutSec1Text_json',
                'default' => null,
                'length' => null,
                'null' => false,
            ])
            ->addColumn('AboutSec2Text', 'text', [
                'after' => 'AboutSec2Header',
                'default' => null,
                'length' => null,
                'null' => false,
            ])
            ->addColumn('AboutSec2Text_json', 'text', [
                'after' => 'AboutSec2Text',
                'default' => null,
                'length' => null,
                'null' => false,
            ])
            ->addColumn('AboutSec3Header', 'text', [
                'after' => 'AboutSec2Text_json',
                'default' => null,
                'length' => null,
                'null' => false,
            ])
            ->addColumn('AboutSec3Text', 'text', [
                'after' => 'AboutSec3Header',
                'default' => null,
                'length' => null,
                'null' => false,
            ])
            ->addColumn('AboutSec3Text_json', 'text', [
                'after' => 'AboutSec3Text',
                'default' => null,
                'length' => null,
                'null' => false,
            ])
            ->addColumn('AboutSec4Header', 'text', [
                'after' => 'AboutSec3Text_json',
                'default' => null,
                'length' => null,
                'null' => false,
            ])
            ->addColumn('AboutSec4Text', 'text', [
                'after' => 'AboutSec4Header',
                'default' => null,
                'length' => null,
                'null' => false,
            ])
            ->addColumn('AboutSec4Text_json', 'text', [
                'after' => 'AboutSec4Text',
                'default' => null,
                'length' => null,
                'null' => false,
            ])
            ->addColumn('NotesSec1Header', 'text', [
                'after' => 'AboutSec4Text_json',
                'default' => null,
                'length' => null,
                'null' => false,
            ])
            ->addColumn('NotesSec1Text', 'text', [
                'after' => 'NotesSec1Header',
                'default' => null,
                'length' => null,
                'null' => false,
            ])
            ->addColumn('NotesSec1Text_json', 'text', [
                'after' => 'NotesSec1Text',
                'default' => null,
                'length' => null,
                'null' => false,
            ])
            ->addColumn('hasOrigins', 'boolean', [
                'after' => 'NotesSec1Text_json',
                'default' => null,
                'length' => null,
                'null' => false,
            ])
            ->addColumn('hasRegions', 'boolean', [
                'after' => 'hasOrigins',
                'default' => null,
                'length' => null,
                'null' => false,
            ])
            ->addColumn('hasTypes', 'boolean', [
                'after' => 'hasRegions',
                'default' => null,
                'length' => null,
                'null' => false,
            ])
            ->addColumn('hasDictionaries', 'boolean', [
                'after' => 'hasTypes',
                'default' => null,
                'length' => null,
                'null' => false,
            ])
            ->addColumn('UTFRangeStart', 'char', [
                'after' => 'hasDictionaries',
                'default' => null,
                'length' => 40,
                'null' => false,
            ])
            ->addColumn('UTFRangeEnd', 'char', [
                'after' => 'UTFRangeStart',
                'default' => null,
                'length' => 40,
                'null' => false,
            ])
            ->addColumn('righttoleft', 'boolean', [
                'after' => 'UTFRangeEnd',
                'default' => null,
                'length' => null,
                'null' => false,
            ])
            ->update();

        $this->table('origins')
            ->addColumn('language_id', 'integer', [
                'after' => 'origin',
                'default' => null,
                'length' => null,
                'null' => false,
            ])
            ->addColumn('top', 'boolean', [
                'after' => 'language_id',
                'default' => null,
                'length' => null,
                'null' => false,
            ])
            ->update();

        $this->table('regions')
            ->addColumn('language_id', 'integer', [
                'after' => 'region',
                'default' => null,
                'length' => null,
                'null' => false,
            ])
            ->addColumn('top', 'boolean', [
                'after' => 'language_id',
                'default' => null,
                'length' => null,
                'null' => false,
            ])
            ->update();

        $this->table('types')
            ->addColumn('language_id', 'integer', [
                'after' => 'type',
                'default' => null,
                'length' => null,
                'null' => false,
            ])
            ->addColumn('top', 'boolean', [
                'after' => 'language_id',
                'default' => null,
                'length' => null,
                'null' => false,
            ])
            ->update();
    }

    /**
     * Down Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-down-method
     * @return void
     */
    public function down()
    {

        $this->table('dictionaries')
            ->removeColumn('language_id')
            ->removeColumn('top')
            ->update();

        $this->table('languages')
            ->removeColumn('i18nspec')
            ->removeColumn('translationfile')
            ->removeColumn('HeaderImage')
            ->removeColumn('LogoImage')
            ->removeColumn('AboutSec1Header')
            ->removeColumn('AboutSec1Text')
            ->removeColumn('AboutSec1Text_json')
            ->removeColumn('AboutSec2Header')
            ->removeColumn('AboutSec2Text')
            ->removeColumn('AboutSec2Text_json')
            ->removeColumn('AboutSec3Header')
            ->removeColumn('AboutSec3Text')
            ->removeColumn('AboutSec3Text_json')
            ->removeColumn('AboutSec4Header')
            ->removeColumn('AboutSec4Text')
            ->removeColumn('AboutSec4Text_json')
            ->removeColumn('NotesSec1Header')
            ->removeColumn('NotesSec1Text')
            ->removeColumn('NotesSec1Text_json')
            ->removeColumn('hasOrigins')
            ->removeColumn('hasRegions')
            ->removeColumn('hasTypes')
            ->removeColumn('hasDictionaries')
            ->removeColumn('UTFRangeStart')
            ->removeColumn('UTFRangeEnd')
            ->removeColumn('righttoleft')
            ->update();

        $this->table('origins')
            ->removeColumn('language_id')
            ->removeColumn('top')
            ->update();

        $this->table('regions')
            ->removeColumn('language_id')
            ->removeColumn('top')
            ->update();

        $this->table('types')
            ->removeColumn('language_id')
            ->removeColumn('top')
            ->update();

        $this->table('words')
            ->changeColumn('created', 'timestamp', [
                'default' => 'CURRENT_TIMESTAMP',
                'length' => null,
                'null' => false,
            ])
            ->update();

        $this->table('alphabets')->drop()->save();
    }
}
