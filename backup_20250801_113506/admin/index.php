<?php
session_start();
require_once '../config/database.php';

// Verificar se usuário está logado
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: login.php');
    exit;
}

// Conectar ao banco
try {
    $db = new Database();
    $conn = $db->connect();
} catch(Exception $e) {
    die('Erro de conexão com o banco de dados');
}

// Processar ações (marcar como lido, responder, etc.)
if ($_POST['action'] ?? '') {
    $action = $_POST['action'];
    $contact_id = intval($_POST['contact_id'] ?? 0);
    
    if ($action === 'mark_read' && $contact_id > 0) {
        $stmt = $conn->prepare("UPDATE contatos SET status = 'lido' WHERE id = :id");
        $stmt->bindParam(':id', $contact_id);
        $stmt->execute();
    } elseif ($action === 'mark_responded' && $contact_id > 0) {
        $stmt = $conn->prepare("UPDATE contatos SET status = 'respondido' WHERE id = :id");
        $stmt->bindParam(':id', $contact_id);
        $stmt->execute();
    } elseif ($action === 'delete' && $contact_id > 0) {
        $stmt = $conn->prepare("DELETE FROM contatos WHERE id = :id");
        $stmt->bindParam(':id', $contact_id);
        $stmt->execute();
    }
}

// Filtros
$filter_status = $_GET['status'] ?? '';
$filter_service = $_GET['service'] ?? '';
$search = $_GET['search'] ?? '';
$page = max(1, intval($_GET['page'] ?? 1));
$per_page = 20;
$offset = ($page - 1) * $per_page;

// Construir query com filtros
$where_conditions = [];
$params = [];

if ($filter_status) {
    $where_conditions[] = "status = :status";
    $params[':status'] = $filter_status;
}

if ($filter_service) {
    $where_conditions[] = "servico = :servico";
    $params[':servico'] = $filter_service;
}

if ($search) {
    $where_conditions[] = "(nome LIKE :search OR email LIKE :search OR empresa LIKE :search OR mensagem LIKE :search)";
    $params[':search'] = '%' . $search . '%';
}

$where_sql = !empty($where_conditions) ? 'WHERE ' . implode(' AND ', $where_conditions) : '';

// Contar total de registros
$count_sql = "SELECT COUNT(*) as total FROM contatos $where_sql";
$count_stmt = $conn->prepare($count_sql);
$count_stmt->execute($params);
$total_records = $count_stmt->fetch()['total'];
$total_pages = ceil($total_records / $per_page);

// Buscar contatos
$sql = "SELECT * FROM contatos $where_sql ORDER BY data_criacao DESC LIMIT :limit OFFSET :offset";
$stmt = $conn->prepare($sql);

// Bind parameters
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}
$stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

$stmt->execute();
$contacts = $stmt->fetchAll();

// Estatísticas rápidas
$stats_sql = "SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN status = 'novo' THEN 1 ELSE 0 END) as novos,
    SUM(CASE WHEN status = 'lido' THEN 1 ELSE 0 END) as lidos,
    SUM(CASE WHEN status = 'respondido' THEN 1 ELSE 0 END) as respondidos,
    SUM(CASE WHEN DATE(data_criacao) = CURDATE() THEN 1 ELSE 0 END) as hoje
    FROM contatos";
$stats_stmt = $conn->prepare($stats_sql);
$stats_stmt->execute();
$stats = $stats_stmt->fetch();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo - Cajá</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
            color: #333;
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
        
        .logout {
            float: right;
            background: rgba(255,255,255,0.2);
            padding: 0.5rem 1rem;
            border-radius: 4px;
            text-decoration: none;
            color: #3E2F08;
            border: 1px solid rgba(62, 47, 8, 0.3);
        }
        
        .logout:hover {
            background: rgba(255,255,255,0.3);
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: #F6D100;
        }
        
        .stat-label {
            color: #666;
            margin-top: 0.5rem;
        }
        
        .filters {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .filters form {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            align-items: end;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
        }
        
        .form-group label {
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        
        .form-group input,
        .form-group select {
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            min-width: 150px;
        }
        
        .btn {
            padding: 0.5rem 1rem;
            background: #F6D100;
            color: #3E2F08;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn:hover {
            background: #E6C200;
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .btn-danger {
            background: #dc3545;
            color: white;
        }
        
        .contacts-table {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        
        th {
            background: #f8f9fa;
            font-weight: 600;
        }
        
        .status {
            padding: 0.25rem 0.5rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
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
        
        .actions {
            display: flex;
            gap: 0.5rem;
        }
        
        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 2rem;
        }
        
        .pagination a,
        .pagination span {
            padding: 0.5rem 1rem;
            background: white;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-decoration: none;
            color: #333;
        }
        
        .pagination .current {
            background: #F6D100;
            color: #3E2F08;
            font-weight: bold;
        }
        
        .contact-details {
            max-width: 300px;
            word-wrap: break-word;
        }
        
        .message-preview {
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        @media (max-width: 768px) {
            .container { padding: 1rem; }
            .filters form { flex-direction: column; }
            table { font-size: 0.9rem; }
            .actions { flex-direction: column; }
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="logout.php" class="logout">Sair</a>
        <h1>🎯 Painel Administrativo - Cajá</h1>
        <div class="subtitle">Gerenciamento de Contatos e Mensagens</div>
    </div>
    
    <div class="container">
        <!-- Estatísticas -->
        <div class="stats">
            <div class="stat-card">
                <div class="stat-number"><?= $stats['total'] ?></div>
                <div class="stat-label">Total de Contatos</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $stats['novos'] ?></div>
                <div class="stat-label">Novos</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $stats['lidos'] ?></div>
                <div class="stat-label">Lidos</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $stats['respondidos'] ?></div>
                <div class="stat-label">Respondidos</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $stats['hoje'] ?></div>
                <div class="stat-label">Hoje</div>
            </div>
        </div>
        
        <!-- Filtros -->
        <div class="filters">
            <form method="GET">
                <div class="form-group">
                    <label>Status:</label>
                    <select name="status">
                        <option value="">Todos</option>
                        <option value="novo" <?= $filter_status === 'novo' ? 'selected' : '' ?>>Novo</option>
                        <option value="lido" <?= $filter_status === 'lido' ? 'selected' : '' ?>>Lido</option>
                        <option value="respondido" <?= $filter_status === 'respondido' ? 'selected' : '' ?>>Respondido</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Serviço:</label>
                    <select name="service">
                        <option value="">Todos</option>
                        <option value="mvp" <?= $filter_service === 'mvp' ? 'selected' : '' ?>>MVP Rápido</option>
                        <option value="software" <?= $filter_service === 'software' ? 'selected' : '' ?>>Software Personalizado</option>
                        <option value="gestao" <?= $filter_service === 'gestao' ? 'selected' : '' ?>>Gestão de Produtos</option>
                        <option value="treinamento" <?= $filter_service === 'treinamento' ? 'selected' : '' ?>>Treinamentos</option>
                        <option value="consultoria" <?= $filter_service === 'consultoria' ? 'selected' : '' ?>>Consultoria</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Buscar:</label>
                    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Nome, email, empresa...">
                </div>
                
                <button type="submit" class="btn">Filtrar</button>
                <a href="index.php" class="btn btn-secondary">Limpar</a>
            </form>
        </div>
        
        <!-- Tabela de Contatos -->
        <div class="contacts-table">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Data</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Empresa</th>
                        <th>Serviço</th>
                        <th>Mensagem</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($contacts as $contact): ?>
                    <tr>
                        <td><?= $contact['id'] ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($contact['data_criacao'])) ?></td>
                        <td class="contact-details"><?= htmlspecialchars($contact['nome']) ?></td>
                        <td class="contact-details">
                            <a href="mailto:<?= htmlspecialchars($contact['email']) ?>">
                                <?= htmlspecialchars($contact['email']) ?>
                            </a>
                        </td>
                        <td><?= htmlspecialchars($contact['empresa'] ?: '-') ?></td>
                        <td><?= htmlspecialchars($contact['servico'] ?: '-') ?></td>
                        <td class="message-preview" title="<?= htmlspecialchars($contact['mensagem']) ?>">
                            <?= htmlspecialchars(substr($contact['mensagem'], 0, 100)) ?>...
                        </td>
                        <td>
                            <span class="status <?= $contact['status'] ?>">
                                <?= ucfirst($contact['status']) ?>
                            </span>
                        </td>
                        <td>
                            <div class="actions">
                                <a href="view_contact.php?id=<?= $contact['id'] ?>" class="btn">Ver</a>
                                
                                <?php if ($contact['status'] === 'novo'): ?>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="action" value="mark_read">
                                    <input type="hidden" name="contact_id" value="<?= $contact['id'] ?>">
                                    <button type="submit" class="btn btn-secondary">Marcar Lido</button>
                                </form>
                                <?php endif; ?>
                                
                                <?php if ($contact['status'] === 'lido'): ?>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="action" value="mark_responded">
                                    <input type="hidden" name="contact_id" value="<?= $contact['id'] ?>">
                                    <button type="submit" class="btn">Respondido</button>
                                </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Paginação -->
        <?php if ($total_pages > 1): ?>
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?= $page - 1 ?>&status=<?= $filter_status ?>&service=<?= $filter_service ?>&search=<?= urlencode($search) ?>">« Anterior</a>
            <?php endif; ?>
            
            <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                <?php if ($i === $page): ?>
                    <span class="current"><?= $i ?></span>
                <?php else: ?>
                    <a href="?page=<?= $i ?>&status=<?= $filter_status ?>&service=<?= $filter_service ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
                <?php endif; ?>
            <?php endfor; ?>
            
            <?php if ($page < $total_pages): ?>
                <a href="?page=<?= $page + 1 ?>&status=<?= $filter_status ?>&service=<?= $filter_service ?>&search=<?= urlencode($search) ?>">Próxima »</a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
        <?php if (empty($contacts)): ?>
        <div style="text-align: center; padding: 3rem; color: #666;">
            <h3>Nenhum contato encontrado</h3>
            <p>Tente ajustar os filtros ou aguarde novas mensagens chegarem.</p>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>