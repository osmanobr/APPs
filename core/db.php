<?php
require_once __DIR__ . '/../config/config.php';

class DB {
    private static $instance = null;
    private $conn;

    private function __construct() {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            $this->conn = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // Em um ambiente de produção, logar o erro e mostrar uma mensagem genérica.
            error_log("Erro de conexão com o banco de dados: " . $e->getMessage());
            die("Erro ao conectar com o banco de dados. Por favor, tente mais tarde.");
        }
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new DB();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->conn;
    }

    // Métodos helper para consultas comuns podem ser adicionados aqui
    // Exemplo:
    // public function query($sql, $params = []) {
    //     $stmt = $this->conn->prepare($sql);
    //     $stmt->execute($params);
    //     return $stmt;
    // }
    //
    // public function fetchOne($sql, $params = []) {
    //     $stmt = $this->query($sql, $params);
    //     return $stmt->fetch();
    // }
    //
    // public function fetchAll($sql, $params = []) {
    //    $stmt = $this->query($sql, $params);
    //    return $stmt->fetchAll();
    //}
}

// Para usar a conexão:
// $db = DB::getInstance();
// $pdo = $db->getConnection();
// $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = :id");
// $stmt->execute(['id' => $userId]);
// $user = $stmt->fetch();
?>
