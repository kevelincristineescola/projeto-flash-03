<?php
/**
 * config.php
 * Responsável por abrir a conexão PDO com o banco SQLite (banco/signos.db).
 * É incluído por resultado.php e por criar_banco.php.
 */

$caminhoBanco = __DIR__ . '/banco/signos.db';

try {
    $pdo = new PDO('sqlite:' . $caminhoBanco);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('Não foi possível conectar ao banco de dados: ' . htmlspecialchars($e->getMessage()));
}
