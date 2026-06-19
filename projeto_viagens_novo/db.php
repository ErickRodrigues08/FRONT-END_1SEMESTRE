<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

function db(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];

    $dsnWithDatabase = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';

    $createdDatabase = false;

    try {
        $pdo = new PDO($dsnWithDatabase, DB_USER, DB_PASS, $options);
    } catch (PDOException $e) {
        $isUnknownDatabase = str_contains($e->getMessage(), 'Unknown database');
        if (!$isUnknownDatabase) {
            throw $e;
        }

        $dsnWithoutDatabase = 'mysql:host=' . DB_HOST . ';charset=utf8mb4';
        $bootstrapPdo = new PDO($dsnWithoutDatabase, DB_USER, DB_PASS, $options);
        $bootstrapPdo->exec(
            'CREATE DATABASE IF NOT EXISTS `' . DB_NAME . '` ' .
            'DEFAULT CHARACTER SET utf8mb4 DEFAULT COLLATE utf8mb4_unicode_ci'
        );
        $createdDatabase = true;

        $pdo = new PDO($dsnWithDatabase, DB_USER, DB_PASS, $options);
    }

    if ($createdDatabase || !schemaIsReady($pdo)) {
        runSqlFile($pdo, __DIR__ . '/init.sql');
    }

    return $pdo;
}

function schemaIsReady(PDO $pdo): bool
{
    $sql = '
      SELECT COUNT(*) 
      FROM information_schema.tables
      WHERE table_schema = :schema
        AND table_name IN ("clientes", "pacotes", "reservas")
    ';
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['schema' => DB_NAME]);

    return (int) $stmt->fetchColumn() === 3;
}

function runSqlFile(PDO $pdo, string $path): void
{
    if (!is_file($path)) {
        return;
    }

    $content = file_get_contents($path);
    if ($content === false) {
        return;
    }

    $lines = preg_split("/\r\n|\n|\r/", $content) ?: [];
    $statement = '';

    foreach ($lines as $line) {
        $trimmedLine = trim($line);
        if ($trimmedLine === '' || str_starts_with($trimmedLine, '--')) {
            continue;
        }

        $statement .= $line . "\n";
        if (str_ends_with(trim($line), ';')) {
            $pdo->exec($statement);
            $statement = '';
        }
    }
}

function redirectWithMessage(string $location, string $message, string $type = 'success'): never
{
    $query = http_build_query([
        'message' => $message,
        'type' => $type,
    ]);

    header('Location: ' . $location . '?' . $query);
    exit;
}
