<?php
/**
 * Script de Teste de Conexão com Banco de Dados
 * Use este arquivo para verificar se a conexão está funcionando
 */

// Incluir configuração do banco
require_once 'config/database.php';

echo "<h1>🔍 Teste de Conexão - Banco de Dados Cajá</h1>";
echo "<hr>";

try {
    // Criar instância da classe Database
    $database = new Database();
    
    // Testar conexão
    echo "<h2>📡 Testando Conexão...</h2>";
    $conn = $database->connect();
    
    if ($conn) {
        echo "<p style='color: green;'>✅ <strong>Conexão estabelecida com sucesso!</strong></p>";
        
        // Testar se as tabelas existem
        echo "<h2>📋 Verificando Tabelas...</h2>";
        
        $tabelas = ['contatos', 'usuarios_admin', 'estatisticas', 'configuracoes'];
        
        foreach ($tabelas as $tabela) {
            try {
                $stmt = $conn->prepare("SHOW TABLES LIKE ?");
                $stmt->execute([$tabela]);
                
                if ($stmt->rowCount() > 0) {
                    echo "<p style='color: green;'>✅ Tabela <strong>$tabela</strong> existe</p>";
                    
                    // Contar registros
                    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM $tabela");
                    $stmt->execute();
                    $result = $stmt->fetch();
                    echo "<p style='margin-left: 20px;'>📊 Registros: <strong>{$result['total']}</strong></p>";
                } else {
                    echo "<p style='color: red;'>❌ Tabela <strong>$tabela</strong> não encontrada</p>";
                }
            } catch (Exception $e) {
                echo "<p style='color: red;'>❌ Erro ao verificar tabela <strong>$tabela</strong>: " . $e->getMessage() . "</p>";
            }
        }
        
        // Testar usuário admin
        echo "<h2>👤 Verificando Usuário Admin...</h2>";
        try {
            $stmt = $conn->prepare("SELECT username, nome_completo, email FROM usuarios_admin WHERE username = 'admin'");
            $stmt->execute();
            $admin = $stmt->fetch();
            
            if ($admin) {
                echo "<p style='color: green;'>✅ Usuário admin encontrado</p>";
                echo "<p style='margin-left: 20px;'>👤 Nome: <strong>{$admin['nome_completo']}</strong></p>";
                echo "<p style='margin-left: 20px;'>📧 Email: <strong>{$admin['email']}</strong></p>";
            } else {
                echo "<p style='color: orange;'>⚠️ Usuário admin não encontrado</p>";
            }
        } catch (Exception $e) {
            echo "<p style='color: red;'>❌ Erro ao verificar usuário admin: " . $e->getMessage() . "</p>";
        }
        
        // Testar configurações
        echo "<h2>⚙️ Verificando Configurações...</h2>";
        try {
            $stmt = $conn->prepare("SELECT chave, valor FROM configuracoes");
            $stmt->execute();
            $configs = $stmt->fetchAll();
            
            if (count($configs) > 0) {
                echo "<p style='color: green;'>✅ Configurações encontradas</p>";
                foreach ($configs as $config) {
                    echo "<p style='margin-left: 20px;'>🔧 <strong>{$config['chave']}</strong>: {$config['valor']}</p>";
                }
            } else {
                echo "<p style='color: orange;'>⚠️ Nenhuma configuração encontrada</p>";
            }
        } catch (Exception $e) {
            echo "<p style='color: red;'>❌ Erro ao verificar configurações: " . $e->getMessage() . "</p>";
        }
        
        // Informações do banco
        echo "<h2>📊 Informações do Banco...</h2>";
        try {
            $stmt = $conn->prepare("SELECT DATABASE() as banco_atual");
            $stmt->execute();
            $banco = $stmt->fetch();
            echo "<p>🗄️ Banco atual: <strong>{$banco['banco_atual']}</strong></p>";
            
            $stmt = $conn->prepare("SELECT VERSION() as versao_mysql");
            $stmt->execute();
            $versao = $stmt->fetch();
            echo "<p>🔧 Versão MySQL: <strong>{$versao['versao_mysql']}</strong></p>";
        } catch (Exception $e) {
            echo "<p style='color: red;'>❌ Erro ao obter informações: " . $e->getMessage() . "</p>";
        }
        
    } else {
        echo "<p style='color: red;'>❌ <strong>Falha na conexão!</strong></p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ <strong>Erro de conexão:</strong> " . $e->getMessage() . "</p>";
    
    echo "<h2>🔧 Verificações Recomendadas:</h2>";
    echo "<ul>";
    echo "<li>Verificar se o banco de dados foi criado</li>";
    echo "<li>Confirmar credenciais em config/database.php</li>";
    echo "<li>Verificar se o usuário tem permissões</li>";
    echo "<li>Confirmar se o MySQL está rodando</li>";
    echo "</ul>";
}

echo "<hr>";
echo "<h2>📋 Credenciais Configuradas:</h2>";
echo "<ul>";
echo "<li><strong>Host:</strong> localhost</li>";
echo "<li><strong>Banco:</strong> u921347543_sitecaja</li>";
echo "<li><strong>Usuário:</strong> u921347543_andressa</li>";
echo "<li><strong>Senha:</strong> -Aa200200229*</li>";
echo "</ul>";

echo "<hr>";
echo "<p><strong>🎯 Próximo passo:</strong> Se todos os testes passaram, o banco está pronto para uso!</p>";
echo "<p><strong>🔐 Painel Admin:</strong> <a href='admin/'>Acessar Painel Administrativo</a></p>";
?> 