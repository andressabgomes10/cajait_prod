<?php
session_start();
require_once '../config/database.php';

// Verificar se usuário está logado
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: login.php');
    exit;
}

$contact_id = intval($_GET['id'] ?? 0);

if (!$contact_id) {
    header('Location: index.php');
    exit;
}

// Conectar ao banco
try {
    $db = new Database();
    $conn = $db->connect();
} catch(Exception $e) {
    die('Erro de conexão com o banco de dados');
}

// Buscar contato
$stmt = $conn->prepare("SELECT * FROM contatos WHERE id = :id");
$stmt->bindParam(':id', $contact_id);
$stmt->execute();
$contact = $stmt->fetch();

if (!$contact) {
    header('Location: index.php');
    exit;
}

// Marcar como lido automaticamente se ainda está como novo
if ($contact['status'] === 'novo') {
    $update_stmt = $conn->prepare("UPDATE contatos SET status = 'lido' WHERE id = :id");
    $update_stmt->bindParam(':id', $contact_id);
    $update_stmt->execute();
    $contact['status'] = 'lido';
}

// Processar ações
if ($_POST['action'] ?? '') {
    $action = $_POST['action'];
    
    if ($action === 'mark_responded') {
        $stmt = $conn->prepare("UPDATE contatos SET status = 'respondido' WHERE id = :id");
        $stmt->bindParam(':id', $contact_id);
        $stmt->execute();
        $contact['status'] = 'respondido';
        $success_message = 'Contato marcado como respondido!';
    } elseif ($action === 'delete') {
        $stmt = $conn->prepare("DELETE FROM contatos WHERE id = :id");
        $stmt->bindParam(':id', $contact_id);
        $stmt->execute();
        header('Location: index.php?deleted=1');
        exit;
    }
}

// Mapear serviços
$service_names = [
    'mvp' => 'MVP Rápido',
    'software' => 'Software Personalizado',
    'gestao' => 'Gestão de Produtos Digitais',
    'treinamento' => 'Treinamentos Práticos',
    'consultoria' => 'Consultoria'
];

$service_display = $service_names[$contact['servico']] ?? ($contact['servico'] ?: 'Não especificado');
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contato #<?= $contact['id'] ?> - Painel Administrativo Cajá</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
            color: #333;
            line-height: 1.6;
        }
        
        .header {
            background: linear-gradient(135deg, #F6D100, #E6C200);
            color: #3E2F08;
            padding: 1rem 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .header h1 {
            font-size: 1.8rem;
            font-weight: 600;
        }
        
        .header .subtitle {
            opacity: 0.8;
            margin-top: 0.5rem;
        }
        
        .back-link {
            float: right;
            background: rgba(255,255,255,0.2);
            padding: 0.5rem 1rem;
            border-radius: 4px;
            text-decoration: none;
            color: #3E2F08;
            border: 1px solid rgba(62, 47, 8, 0.3);
        }
        
        .back-link:hover {
            background: rgba(255,255,255,0.3);
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .contact-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .contact-header {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            padding: 2rem;
            border-bottom: 1px solid #dee2e6;
        }
        
        .contact-title {
            font-size: 1.5rem;
            color: #3E2F08;
            margin-bottom: 1rem;
        }
        
        .contact-meta {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .meta-icon {
            width: 20px;
            height: 20px;
            color: #F6D100;
        }
        
        .status {
            padding: 0.5rem 1rem;
            border-radius: 25px;
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .status.novo {
            background: #fff3cd;
            color: #856404;
        }
        
        .status.lido {
            background: #d1ecf1;
            color: #0c5460;
        }
        
        .status.respondido {
            background: #d4edda;
            color: #155724;
        }
        
        .contact-body {
            padding: 2rem;
        }
        
        .info-section {
            margin-bottom: 2rem;
        }
        
        .info-section h3 {
            color: #3E2F08;
            margin-bottom: 1rem;
            font-size: 1.2rem;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }
        
        .info-item {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 8px;
            border-left: 4px solid #F6D100;
        }
        
        .info-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .info-value {
            color: #333;
            font-size: 1rem;
        }
        
        .message-section {
            background: #f8f9fa;
            padding: 2rem;
            border-radius: 8px;
            margin: 2rem 0;
        }
        
        .message-content {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            border: 1px solid #e9ecef;
            white-space: pre-wrap;
            line-height: 1.8;
        }
        
        .technical-info {
            background: #e9ecef;
            padding: 1rem;
            border-radius: 8px;
            font-size: 0.85rem;
            color: #6c757d;
        }
        
        .technical-info h4 {
            margin-bottom: 0.5rem;
            color: #495057;
        }
        
        .actions {
            background: #f8f9fa;
            padding: 2rem;
            border-top: 1px solid #dee2e6;
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            text-decoration: none;
            display: inline-block;
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }
        
        .btn:hover {
            transform: translateY(-1px);
        }
        
        .btn-primary {
            background: #F6D100;
            color: #3E2F08;
        }
        
        .btn-primary:hover {
            background: #E6C200;
        }
        
        .btn-success {
            background: #28a745;
            color: white;
        }
        
        .btn-success:hover {
            background: #218838;
        }
        
        .btn-danger {
            background: #dc3545;
            color: white;
        }
        
        .btn-danger:hover {
            background: #c82333;
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #545b62;
        }
        
        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 1rem;
            border: 1px solid #c3e6cb;
        }
        
        @media (max-width: 768px) {
            .container { padding: 1rem; }
            .contact-meta { grid-template-columns: 1fr; }
            .info-grid { grid-template-columns: 1fr; }
            .actions { flex-direction: column; }
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php" class="back-link">← Voltar ao Painel</a>
        <h1>📧 Detalhes do Contato</h1>
        <div class="subtitle">Contato ID: #<?= $contact['id'] ?></div>
    </div>
    
    <div class="container">
        <?php if (isset($success_message)): ?>
        <div class="success-message">
            <?= htmlspecialchars($success_message) ?>
        </div>
        <?php endif; ?>
        
        <div class="contact-card">
            <div class="contact-header">
                <h2 class="contact-title"><?= htmlspecialchars($contact['nome']) ?></h2>
                
                <div class="contact-meta">
                    <div class="meta-item">
                        <svg class="meta-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.73a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <a href="mailto:<?= htmlspecialchars($contact['email']) ?>" style="color: #333; text-decoration: none;">
                            <?= htmlspecialchars($contact['email']) ?>
                        </a>
                    </div>
                    
                    <div class="meta-item">
                        <svg class="meta-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m0 0V7a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V9a2 2 0 012-2h8z"/>
                        </svg>
                        <?= htmlspecialchars($contact['empresa'] ?: 'Não informado') ?>
                    </div>
                    
                    <div class="meta-item">
                        <svg class="meta-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <?= date('d/m/Y às H:i', strtotime($contact['data_criacao'])) ?>
                    </div>
                    
                    <div class="meta-item">
                        <span class="status <?= $contact['status'] ?>">
                            <?= ucfirst($contact['status']) ?>
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="contact-body">
                <div class="info-section">
                    <h3>📋 Informações do Contato</h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Nome Completo</div>
                            <div class="info-value"><?= htmlspecialchars($contact['nome']) ?></div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label">Email</div>
                            <div class="info-value">
                                <a href="mailto:<?= htmlspecialchars($contact['email']) ?>">
                                    <?= htmlspecialchars($contact['email']) ?>
                                </a>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label">Empresa</div>
                            <div class="info-value"><?= htmlspecialchars($contact['empresa'] ?: 'Não informado') ?></div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label">Serviço de Interesse</div>
                            <div class="info-value"><?= htmlspecialchars($service_display) ?></div>
                        </div>
                    </div>
                </div>
                
                <div class="info-section">
                    <h3>💬 Mensagem</h3>
                    <div class="message-section">
                        <div class="message-content">
<?= htmlspecialchars($contact['mensagem']) ?>
                        </div>
                    </div>
                </div>
                
                <div class="info-section">
                    <h3>🔧 Informações Técnicas</h3>
                    <div class="technical-info">
                        <h4>Detalhes Técnicos:</h4>
                        <p><strong>IP Address:</strong> <?= htmlspecialchars($contact['ip_address']) ?></p>
                        <p><strong>User Agent:</strong> <?= htmlspecialchars($contact['user_agent']) ?></p>
                        <p><strong>Data de Criação:</strong> <?= date('d/m/Y H:i:s', strtotime($contact['data_criacao'])) ?></p>
                        <p><strong>Última Atualização:</strong> <?= date('d/m/Y H:i:s', strtotime($contact['data_atualizacao'])) ?></p>
                    </div>
                </div>
            </div>
            
            <div class="actions">
                <a href="mailto:<?= htmlspecialchars($contact['email']) ?>?subject=Re: Sua mensagem para Cajá&body=Olá <?= htmlspecialchars($contact['nome']) ?>,%0A%0AObrigado por entrar em contato com a Cajá!%0A%0A" class="btn btn-primary">
                    📧 Responder por Email
                </a>
                
                <?php if ($contact['status'] !== 'respondido'): ?>
                <form method="POST" style="display: inline;">
                    <input type="hidden" name="action" value="mark_responded">
                    <button type="submit" class="btn btn-success">
                        ✅ Marcar como Respondido
                    </button>
                </form>
                <?php endif; ?>
                
                <form method="POST" style="display: inline;" onsubmit="return confirm('Tem certeza que deseja excluir este contato? Esta ação não pode ser desfeita.')">
                    <input type="hidden" name="action" value="delete">
                    <button type="submit" class="btn btn-danger">
                        🗑️ Excluir Contato
                    </button>
                </form>
                
                <a href="index.php" class="btn btn-secondary">
                    ← Voltar ao Painel
                </a>
            </div>
        </div>
    </div>
</body>
</html>