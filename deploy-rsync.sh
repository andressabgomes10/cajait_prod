#!/bin/bash

# Script de Deploy Automático via rsync - Site Cajá
# Para upload eficiente na Hostinger

echo "🚀 Iniciando deploy automático via rsync..."

# Configurações SSH/FTP
HOST="cajait.com"
USER="u921347543_andressa"
REMOTE_DIR="/public_html"
LOCAL_DIR="public_html"

# Cores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Função para log colorido
log() {
    echo -e "${GREEN}[$(date +'%Y-%m-%d %H:%M:%S')] $1${NC}"
}

error() {
    echo -e "${RED}[ERRO] $1${NC}"
}

warning() {
    echo -e "${YELLOW}[AVISO] $1${NC}"
}

# Verificar se a pasta public_html existe
if [ ! -d "$LOCAL_DIR" ]; then
    error "Pasta $LOCAL_DIR não encontrada! Execute primeiro o deploy.sh"
    exit 1
fi

log "📡 Tentando diferentes métodos de upload..."

# Método 1: rsync via SSH
if command -v rsync &> /dev/null; then
    log "🔄 Tentando upload via rsync..."
    if rsync -avz --progress "$LOCAL_DIR/" "$USER@$HOST:$REMOTE_DIR/" --exclude='*.tmp' --exclude='*.log'; then
        log "✅ Upload via rsync concluído com sucesso!"
        UPLOAD_SUCCESS=true
    else
        warning "⚠️ rsync falhou, tentando método alternativo..."
    fi
fi

# Método 2: scp (se rsync falhou)
if [ "$UPLOAD_SUCCESS" != "true" ] && command -v scp &> /dev/null; then
    log "🔄 Tentando upload via scp..."
    if scp -r "$LOCAL_DIR"/* "$USER@$HOST:$REMOTE_DIR/"; then
        log "✅ Upload via scp concluído com sucesso!"
        UPLOAD_SUCCESS=true
    else
        warning "⚠️ scp falhou, tentando método alternativo..."
    fi
fi

# Método 3: curl com arquivos individuais
if [ "$UPLOAD_SUCCESS" != "true" ]; then
    log "🔄 Tentando upload via curl (arquivos individuais)..."
    
    # Listar todos os arquivos
    FILES=$(find "$LOCAL_DIR" -type f)
    UPLOAD_COUNT=0
    
    for file in $FILES; do
        # Calcular caminho relativo
        REL_PATH=${file#$LOCAL_DIR/}
        REMOTE_PATH="$REMOTE_DIR/$REL_PATH"
        
        if curl -s -T "$file" "ftp://$USER@$HOST$REMOTE_PATH" > /dev/null 2>&1; then
            log "✅ Upload: $REL_PATH"
            ((UPLOAD_COUNT++))
        else
            warning "⚠️ Falha no upload: $REL_PATH"
        fi
    done
    
    if [ $UPLOAD_COUNT -gt 0 ]; then
        log "✅ Upload parcial concluído: $UPLOAD_COUNT arquivos"
        UPLOAD_SUCCESS=true
    fi
fi

# Se nenhum método funcionou
if [ "$UPLOAD_SUCCESS" != "true" ]; then
    error "❌ Não foi possível fazer upload automático."
    echo ""
    log "📋 Para fazer upload manual:"
    echo "   1. Acesse o cPanel da Hostinger"
    echo "   2. Vá em File Manager"
    echo "   3. Navegue até public_html/"
    echo "   4. Faça upload dos arquivos da pasta $LOCAL_DIR/"
    echo ""
    log "📁 Arquivos prontos em: $LOCAL_DIR/"
fi

# Verificar arquivos locais
log "📊 Verificando arquivos prontos para upload:"
echo "   - Arquivos HTML: $(find $LOCAL_DIR -name '*.html' | wc -l)"
echo "   - Arquivos CSS: $(find $LOCAL_DIR -name '*.css' | wc -l)"
echo "   - Arquivos JS: $(find $LOCAL_DIR -name '*.js' | wc -l)"
echo "   - Arquivos PHP: $(find $LOCAL_DIR -name '*.php' | wc -l)"
echo "   - Total de arquivos: $(find $LOCAL_DIR -type f | wc -l)"

echo ""
log "🌐 URLs do site após upload:"
echo "   - Site principal: https://cajait.com"
echo "   - Painel admin: https://cajait.com/admin/"
echo "   - Teste DB: https://cajait.com/test-database.php"

echo ""
log "🎉 Deploy automático concluído!"
log "📋 Próximos passos:"
echo "   1. Executar script SQL no phpMyAdmin"
echo "   2. Testar site em https://cajait.com"
echo "   3. Verificar painel admin"
echo "   4. Testar formulário de contato"

# Criar arquivo de instruções para upload manual
cat > "INSTRUCOES-UPLOAD.md" << EOF
# 📤 Instruções para Upload Manual

## 🌐 Site: cajait.com

### 📁 Arquivos para Upload:
Localizados na pasta: \`$LOCAL_DIR/\`

### 🔧 Método 1: cPanel File Manager
1. Acesse o cPanel da Hostinger
2. Vá em **File Manager**
3. Navegue até \`public_html/\`
4. Faça upload de todos os arquivos da pasta \`$LOCAL_DIR/\`

### 🔧 Método 2: FTP
- **Host:** cajait.com
- **Usuário:** u921347543_andressa
- **Senha:** -Aa200200229*
- **Diretório:** /public_html

### 📋 Lista de Arquivos:
$(find $LOCAL_DIR -type f | sed 's|public_html/||' | sort)

### 🌐 URLs após Upload:
- **Site:** https://cajait.com
- **Admin:** https://cajait.com/admin/
- **Teste DB:** https://cajait.com/test-database.php

### 🔐 Credenciais Admin:
- **Usuário:** admin
- **Senha:** cajait2025!
EOF

log "📄 Instruções salvas em: INSTRUCOES-UPLOAD.md" 