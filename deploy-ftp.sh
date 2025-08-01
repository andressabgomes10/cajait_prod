#!/bin/bash

# Script de Deploy Automático via FTP - Site Cajá
# Para upload automático na Hostinger

echo "🚀 Iniciando deploy automático via FTP..."

# Configurações FTP
FTP_HOST="cajait.com"
FTP_USER="u921347543_andressa"
FTP_PASS="-Aa200200229*"
FTP_DIR="/public_html"
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

# Verificar se o curl está instalado
if ! command -v curl &> /dev/null; then
    error "curl não está instalado. Instale o curl para continuar."
    exit 1
fi

log "📡 Conectando ao servidor FTP..."

# Criar arquivo de configuração FTP temporário
FTP_CONFIG=$(mktemp)
cat > "$FTP_CONFIG" << EOF
open $FTP_HOST
user $FTP_USER $FTP_PASS
binary
cd $FTP_DIR
prompt
mput $LOCAL_DIR/*
bye
EOF

# Executar upload via FTP
log "📤 Fazendo upload dos arquivos..."
if curl -s -T "$LOCAL_DIR/*" "ftp://$FTP_USER:$FTP_PASS@$FTP_HOST$FTP_DIR/"; then
    log "✅ Upload concluído com sucesso!"
else
    warning "⚠️ Upload via curl falhou, tentando método alternativo..."
    
    # Método alternativo usando lftp (se disponível)
    if command -v lftp &> /dev/null; then
        log "🔄 Tentando upload via lftp..."
        lftp -c "open -u $FTP_USER,$FTP_PASS $FTP_HOST; mirror --reverse $LOCAL_DIR $FTP_DIR"
        log "✅ Upload via lftp concluído!"
    else
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
fi

# Limpar arquivo temporário
rm -f "$FTP_CONFIG"

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