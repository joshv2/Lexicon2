<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     3.0.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */

use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;

/**
 * NOTE: This project previously loaded a full MySQL schema dump into the test
 * database at bootstrap time.
 *
 * That approach is fragile locally (tables may already exist) and it makes it
 * easy to accidentally run PHPUnit against a real/dev database.
 *
 * Instead, we:
 * - Configure a dedicated test database (default DB name + "_phpunit")
 * - Recreate that database and load the schema from a SQL dump
 * - Alias the application's `default` connection to the `test` connection
 *
 * CakePHP 5 fixtures reflect schema from the database. They do not create
 * tables from fixture definitions. This bootstrap ensures the schema exists.
 */

/**
 * Test runner bootstrap.
 *
 * Add additional configuration/setup your application needs when running
 * unit tests in this file.
 */
require dirname(__DIR__) . '/vendor/autoload.php';

require dirname(__DIR__) . '/config/bootstrap.php';

/**
 * Import schema statements (DDL) from a MySQL dump into the current database.
 * Data statements (INSERT, LOCK TABLES, etc.) are skipped.
 */
$importSchemaFromSqlDump = static function (PDO $pdo, string $dumpPath): void {
    if (!is_file($dumpPath)) {
        throw new RuntimeException('Schema dump not found at: ' . $dumpPath);
    }

    $handle = fopen($dumpPath, 'rb');
    if ($handle === false) {
        throw new RuntimeException('Unable to open schema dump at: ' . $dumpPath);
    }

    $buffer = '';
    $inSingle = false;
    $inDouble = false;
    $inBacktick = false;
    $escape = false;

    $execStatement = static function (PDO $pdo, string $statement): void {
        $sql = trim($statement);
        if ($sql === '') {
            return;
        }

        // Skip statements that would switch DB, or load data.
        $upper = strtoupper(ltrim($sql));
        if (
            str_starts_with($upper, 'CREATE DATABASE') ||
            str_starts_with($upper, 'USE ') ||
            str_starts_with($upper, 'INSERT ') ||
            str_starts_with($upper, 'LOCK TABLES') ||
            str_starts_with($upper, 'UNLOCK TABLES') ||
            str_starts_with($upper, 'START TRANSACTION') ||
            str_starts_with($upper, 'COMMIT')
        ) {
            return;
        }

        // MySQL dumps sometimes include multi-line ALTER TABLE ... DISABLE/ENABLE KEYS.
        if (str_contains($upper, 'DISABLE KEYS') || str_contains($upper, 'ENABLE KEYS')) {
            return;
        }

        $pdo->exec($sql);
    };

    try {
        while (($line = fgets($handle)) !== false) {
            // Skip full-line comments.
            $trimmed = ltrim($line);
            if ($trimmed === '' || str_starts_with($trimmed, '-- ')) {
                continue;
            }

            $length = strlen($line);
            for ($i = 0; $i < $length; $i++) {
                $ch = $line[$i];

                if ($escape) {
                    $buffer .= $ch;
                    $escape = false;
                    continue;
                }

                if ($ch === '\\') {
                    // Only treat backslash as escape inside quoted strings.
                    if ($inSingle || $inDouble) {
                        $escape = true;
                    }
                    $buffer .= $ch;
                    continue;
                }

                if ($ch === "'" && !$inDouble && !$inBacktick) {
                    $inSingle = !$inSingle;
                    $buffer .= $ch;
                    continue;
                }
                if ($ch === '"' && !$inSingle && !$inBacktick) {
                    $inDouble = !$inDouble;
                    $buffer .= $ch;
                    continue;
                }
                if ($ch === '`' && !$inSingle && !$inDouble) {
                    $inBacktick = !$inBacktick;
                    $buffer .= $ch;
                    continue;
                }

                if ($ch === ';' && !$inSingle && !$inDouble && !$inBacktick) {
                    $execStatement($pdo, $buffer);
                    $buffer = '';
                    continue;
                }

                $buffer .= $ch;
            }
        }

        // Any trailing statement without semicolon.
        $execStatement($pdo, $buffer);
    } finally {
        fclose($handle);
    }
};

// Ensure an isolated test database is used for all connections during PHPUnit.
// You can override this entirely via DATABASE_TEST_URL.
$testUrl = env('DATABASE_TEST_URL', null);
if ($testUrl) {
    ConnectionManager::drop('test');
    ConnectionManager::setConfig('test', ['url' => $testUrl]);
} else {
    $defaultConfig = ConnectionManager::getConfig('default');
    $defaultDbName = $defaultConfig['database'] ?? null;
    if (!$defaultDbName) {
        throw new RuntimeException('Unable to determine default database name; set DATABASE_TEST_URL to run tests.');
    }

    $testDbName = $defaultDbName . '_phpunit';

    // Recreate the test database and load schema.
    // Do this via a lightweight PDO connection so we don't instantiate Cake's
    // `default` connection (which may point to a real/dev DB).
    try {
        $host = $defaultConfig['host'] ?? 'localhost';
        $port = (string)($defaultConfig['port'] ?? '3306');
        $username = (string)($defaultConfig['username'] ?? '');
        $password = (string)($defaultConfig['password'] ?? '');

        $dsn = 'mysql:host=' . $host . ';port=' . $port . ';charset=utf8mb4';
        $pdo = new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);

        // Start from a clean DB each run.
        $pdo->exec('DROP DATABASE IF EXISTS `' . $testDbName . '`');
        $pdo->exec('CREATE DATABASE `' . $testDbName . '` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci');

        $pdoDb = new PDO($dsn . ';dbname=' . $testDbName, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);

        $dumpPath = __DIR__ . DIRECTORY_SEPARATOR . 'lexicon_latest6fortet.sql';
        $importSchemaFromSqlDump($pdoDb, $dumpPath);
    } catch (Throwable $e) {
        throw new RuntimeException(
            'Failed to create/import the PHPUnit database schema. ' .
            'Either grant CREATE/DROP privileges for the configured MySQL user, ' .
            'or set DATABASE_TEST_URL to point at a pre-provisioned test database.',
            0,
            $e,
        );
    }

    $testConfig = $defaultConfig;
    $testConfig['database'] = $testDbName;
    ConnectionManager::drop('test');
    ConnectionManager::setConfig('test', $testConfig);
}

// Make sure app code that uses the `default` connection is pointed at `test`.
ConnectionManager::alias('test', 'default');

ConnectionManager::alias('test_debug_kit', 'debug_kit');

