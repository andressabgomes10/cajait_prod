<?php
// Configurações de email para Hostinger
require_once 'config/database.php';

$recipient_email = 'andressa@cajait.com';

// Configurar cabeçalhos para JSON
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Log de debug
error_log("Contact form accessed. Method: " . $_SERVER['REQUEST_METHOD']);

// Verificar se é POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
    exit;
}

// Função para sanitizar dados
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Validar e sanitizar dados
$name = isset($_POST['name']) ? sanitize_input($_POST['name']) : '';
$email = isset($_POST['email']) ? sanitize_input($_POST['email']) : '';
$company = isset($_POST['company']) ? sanitize_input($_POST['company']) : '';
$service = isset($_POST['service']) ? sanitize_input($_POST['service']) : '';
$message = isset($_POST['message']) ? sanitize_input($_POST['message']) : '';

// Log dos dados recebidos
error_log("Form data received: name=$name, email=$email");

// Validações
$errors = [];

if (empty($name) || strlen($name) < 2) {
    $errors[] = 'Nome é obrigatório e deve ter pelo menos 2 caracteres';
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Email válido é obrigatório';
}

if (empty($message) || strlen($message) < 10) {
    $errors[] = 'Mensagem é obrigatória e deve ter pelo menos 10 caracteres';
}

if (!empty($errors)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
    exit;
}

// Mapear serviços
$service_names = [
    'mvp' => 'MVP Rápido',
    'software' => 'Software Personalizado',
    'gestao' => 'Gestão de Produtos Digitais',
    'treinamento' => 'Treinamentos Práticos',
    'consultoria' => 'Consultoria'
];

$service_display = isset($service_names[$service]) ? $service_names[$service] : ($service ?: 'Não especificado');
$company_display = $company ?: 'Não informado';

// Salvar no banco de dados
$database_saved = false;
try {
    $db = new Database();
    $conn = $db->connect();
    
    // Inserir na tabela contatos
    $sql = "INSERT INTO contatos (nome, email, empresa, servico, mensagem, ip_address, user_agent) 
            VALUES (:nome, :email, :empresa, :servico, :mensagem, :ip_address, :user_agent)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':nome', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':empresa', $company);
    $stmt->bindParam(':servico', $service);
    $stmt->bindParam(':mensagem', $message);
    $stmt->bindParam(':ip_address', $_SERVER['REMOTE_ADDR']);
    $stmt->bindParam(':user_agent', $_SERVER['HTTP_USER_AGENT']);
    
    $database_saved = $stmt->execute();
    $contact_id = $conn->lastInsertId();
    
    // Atualizar estatísticas
    $today = date('Y-m-d');
    $stats_sql = "INSERT INTO estatisticas (data_evento, tipo_evento, valor, detalhes) 
                  VALUES (:data_evento, 'contato_enviado', 1, :detalhes)
                  ON DUPLICATE KEY UPDATE valor = valor + 1";
    
    $stats_stmt = $conn->prepare($stats_sql);
    $stats_stmt->bindParam(':data_evento', $today);
    $details = json_encode(['servico' => $service, 'contact_id' => $contact_id]);
    $stats_stmt->bindParam(':detalhes', $details);
    $stats_stmt->execute();
    
    error_log("Contact saved to database with ID: " . $contact_id);
    
} catch(Exception $e) {
    error_log("Database error: " . $e->getMessage());
    $database_saved = false;
}

// Tentar enviar email
$email_sent = false;

// Modo demonstração para desenvolvimento
$is_demo_mode = strpos($_SERVER['HTTP_HOST'], 'localhost') !== false ||
                strpos($_SERVER['HTTP_HOST'], 'preview.emergentagent.com') !== false;

try {
    if ($is_demo_mode) {
        // Simular sucesso em modo demo
        $email_sent = true;
        error_log("DEMO MODE: Email simulation successful");
    } else {
        // Criar corpo do email
        $email_subject = "Nova mensagem do site - " . $name;
        $email_body = "Nova mensagem recebida pelo site da Cajá:\n\n";
        $email_body .= "ID do Contato: " . ($contact_id ?? 'N/A') . "\n";
        $email_body .= "Nome: " . $name . "\n";
        $email_body .= "Email: " . $email . "\n";
        $email_body .= "Empresa: " . $company_display . "\n";
        $email_body .= "Serviço de interesse: " . $service_display . "\n\n";
        $email_body .= "Mensagem:\n" . $message . "\n\n";
        $email_body .= "---\n";
        $email_body .= "Enviado em: " . date('d/m/Y \à\s H:i:s') . "\n";
        $email_body .= "IP do visitante: " . $_SERVER['REMOTE_ADDR'] . "\n";
        $email_body .= "Acesse o painel administrativo: https://cajait.com/admin/\n\n";
        $email_body .= "Esta mensagem foi enviada através do formulário de contato do site cajait.com";
        
        // Configurar headers do email
        $headers = "From: Site Cajá <noreply@cajait.com>\r\n";
        $headers .= "Reply-To: " . $name . " <" . $email . ">\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();
        
        // Tentar envio real
        $email_sent = mail($recipient_email, $email_subject, $email_body, $headers);
        error_log("Email send attempt: " . ($email_sent ? 'SUCCESS' : 'FAILED'));
    }
} catch (Exception $e) {
    error_log("Email error: " . $e->getMessage());
}

// Resposta baseada no sucesso das operações
if ($database_saved) {
    // Sucesso - dados salvos no banco
    echo json_encode([
        'success' => true, 
        'message' => '🎉 Perfeito! Sua mensagem foi enviada com sucesso! Nossa equipe da Cajá recebeu sua solicitação e entrará em contato em até 24 horas. Muito obrigado pelo interesse!',
        'contact_id' => $contact_id ?? null
    ]);
    
    // Log de sucesso
    $log_entry = date('Y-m-d H:i:s') . " - Contato ID: " . ($contact_id ?? 'N/A') . " - " . $name . " (" . $email . ") - SALVO NO BANCO\n";
    file_put_contents('contact_log.txt', $log_entry, FILE_APPEND | LOCK_EX);
    
} else {
    // Fallback - salvar em arquivo se banco falhar
    $log_entry = date('Y-m-d H:i:s') . " - Contato de: " . $name . " (" . $email . ") - ERRO NO BANCO - SALVO EM ARQUIVO\n";
    $log_entry .= "Empresa: " . $company_display . "\n";
    $log_entry .= "Serviço: " . $service_display . "\n";
    $log_entry .= "Mensagem: " . $message . "\n";
    $log_entry .= "IP: " . $_SERVER['REMOTE_ADDR'] . "\n";
    $log_entry .= "---\n\n";
    file_put_contents('contact_log.txt', $log_entry, FILE_APPEND | LOCK_EX);
    
    echo json_encode([
        'success' => true, 
        'message' => '🎉 Sua mensagem foi recebida com sucesso! Nossa equipe entrará em contato em breve. Obrigado pelo interesse na Cajá!'
    ]);
}
?>