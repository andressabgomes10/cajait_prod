<?php
session_start();
require_once '../config/database.php';

$error_message = '';

// Processar login
if ($_POST) {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if ($username && $password) {
        try {
            $db = new Database();
            $conn = $db->connect();
            
            $stmt = $conn->prepare("SELECT * FROM usuarios_admin WHERE username = :username AND ativo = 1");
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                // Login bem-sucedido
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_user_id'] = $user['id'];
                $_SESSION['admin_username'] = $user['username'];
                $_SESSION['admin_name'] = $user['nome_completo'];
                
                // Atualizar último acesso
                $update_stmt = $conn->prepare("UPDATE usuarios_admin SET ultimo_acesso = NOW() WHERE id = :id");
                $update_stmt->bindParam(':id', $user['id']);
                $update_stmt->execute();
                
                header('Location: index.php');
                exit;
            } else {
                $error_message = 'Usuário ou senha incorretos.';
            }
        } catch(Exception $e) {
            $error_message = 'Erro de conexão com o banco de dados.';
            error_log("Login error: " . $e->getMessage());
        }
    } else {
        $error_message = 'Por favor, preencha todos os campos.';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Painel Administrativo Cajá</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #F6D100, #E6C200);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .login-container {
            background: white;
            padding: 3rem;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }
        
        .logo {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .logo h1 {
            color: #3E2F08;
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        
        .logo p {
            color: #666;
            font-size: 0.9rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
            font-weight: 500;
        }
        
        .form-group input {
            width: 100%;
            padding: 1rem;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #F6D100;
        }
        
        .btn-login {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #F6D100, #E6C200);
            color: #3E2F08;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s ease;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
        }
        
        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        
        .footer {
            text-align: center;
            margin-top: 2rem;
            color: #666;
            font-size: 0.8rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <h1>🎯 Cajá Admin</h1>
            <p>Painel Administrativo</p>
        </div>
        
        <?php if ($error_message): ?>
        <div class="error-message">
            <?= htmlspecialchars($error_message) ?>
        </div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="username">Usuário:</label>
                <input 
                    type="text" 
                    id="username" 
                    name="username" 
                    required 
                    value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                    autocomplete="username"
                >
            </div>
            
            <div class="form-group">
                <label for="password">Senha:</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    required
                    autocomplete="current-password"
                >
            </div>
            
            <button type="submit" class="btn-login">
                Entrar no Painel
            </button>
        </form>
        
        <div class="footer">
            <p>&copy; 2025 Cajá - Tecnologia Artesanal</p>
        </div>
    </div>
</body>
</html>