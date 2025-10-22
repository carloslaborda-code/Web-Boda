<?php
if (!defined('FROM_INDEX')) {
    header("HTTP/1.0 403 Forbidden");
    exit("Acceso directo no permitido.");
}

// Clase UserModel para gestionar usuarios
class UserModel {
    private $pdo;

    public function __construct() {
        require_once __DIR__ . '/../config/db.php'; // Configuración de conexión
        $this->pdo = $pdo;
    }

    // Verificar credenciales de usuario de forma segura
    public function verificarCredenciales($username, $password) {
        try {
            $query = "SELECT IdUsuario, NomUsuario AS username, Clave AS password, Estilo 
                      FROM Usuarios 
                      WHERE NomUsuario = :username";

            $stmt = $this->pdo->prepare($query);
            $stmt->execute([':username' => $username]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario && password_verify($password, $usuario['password'])) {

                // Obtener el estilo asociado al usuario
                $query_estilo = "SELECT Fichero FROM Estilos WHERE IdEstilo = :idEstilo";
                $stmt_estilo = $this->pdo->prepare($query_estilo);
                $stmt_estilo->execute([':idEstilo' => $usuario['Estilo']]);
                $estilo = $stmt_estilo->fetch(PDO::FETCH_ASSOC);

                if ($estilo) {
                    $usuario['estilo'] = '/daw/miweb_xampp/css/' . $estilo['Fichero'];
                } else {
                    $usuario['estilo'] = '/daw/miweb_xampp/css/style.css'; // Estilo por defecto
                }

                return $usuario;
            }

            // Log opcional (no recomendado mostrar mensajes en producción)
            error_log("Intento de login fallido para usuario: $username");
            return false;

        } catch (PDOException $e) {
            error_log("Error en UserModel::verificarCredenciales - " . $e->getMessage());
            return false;
        }
    }
}
