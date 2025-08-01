#!/bin/bash

# Script de Deploy Automático - Site Cajá
# Para uso com Hostinger e Git

echo "🚀 Iniciando deploy do site Cajá..."

# Configurações
REPO_NAME="caja-website"
BRANCH="main"
DEPLOY_DIR="public_html"

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

# Verificar se estamos no diretório correto
if [ ! -f "index.html" ]; then
    error "Execute este script na pasta raiz do projeto!"
    exit 1
fi

# Backup dos arquivos de produção
log "📦 Criando backup dos arquivos atuais..."
if [ -d "$DEPLOY_DIR" ]; then
    BACKUP_DIR="backup_$(date +%Y%m%d_%H%M%S)"
    mkdir -p "$BACKUP_DIR"
    cp -r "$DEPLOY_DIR"/* "$BACKUP_DIR/" 2>/dev/null || warning "Não foi possível fazer backup"
    log "Backup criado em: $BACKUP_DIR"
fi

# Limpar diretório de deploy
log "🧹 Limpando diretório de deploy..."
rm -rf "$DEPLOY_DIR"
mkdir -p "$DEPLOY_DIR"

# Copiar arquivos para produção
log "📋 Copiando arquivos para produção..."

# Arquivos principais
cp index.html "$DEPLOY_DIR/"

# Copiar arquivos minificados se existirem, senão copiar originais
if [ -f "minified/style.min.css" ]; then
    cp minified/style.min.css "$DEPLOY_DIR/style.css"
    log "✅ CSS minificado copiado"
else
    cp style.css "$DEPLOY_DIR/"
    log "⚠️ CSS original copiado (minificado não encontrado)"
fi

if [ -f "minified/script.min.js" ]; then
    cp minified/script.min.js "$DEPLOY_DIR/script.js"
    log "✅ JS minificado copiado"
else
    cp script.js "$DEPLOY_DIR/"
    log "⚠️ JS original copiado (minificado não encontrado)"
fi

# Arquivos PHP
cp contact.php "$DEPLOY_DIR/"
cp -r config "$DEPLOY_DIR/"

# Diretório admin (se existir)
if [ -d "admin" ]; then
    cp -r admin "$DEPLOY_DIR/"
fi

# Arquivo de setup do banco
cp database_setup.sql "$DEPLOY_DIR/"

# Remover arquivos de desenvolvimento
log "🗑️ Removendo arquivos de desenvolvimento..."
rm -f "$DEPLOY_DIR/config/database_local.php"
rm -f "$DEPLOY_DIR/contact_local.php"
rm -f "$DEPLOY_DIR/local_contacts.json"
rm -f "$DEPLOY_DIR/contact_log.txt"

# Verificar permissões
log "🔐 Ajustando permissões..."
chmod 644 "$DEPLOY_DIR"/*.html
chmod 644 "$DEPLOY_DIR"/*.css
chmod 644 "$DEPLOY_DIR"/*.js
chmod 644 "$DEPLOY_DIR"/*.php
chmod 644 "$DEPLOY_DIR"/*.sql
chmod 755 "$DEPLOY_DIR/config"
chmod 644 "$DEPLOY_DIR/config"/*.php

# Se existe diretório admin, ajustar permissões
if [ -d "$DEPLOY_DIR/admin" ]; then
    chmod 755 "$DEPLOY_DIR/admin"
    chmod 644 "$DEPLOY_DIR/admin"/*.php
fi

# Verificar se os arquivos foram copiados
log "✅ Verificando arquivos..."
if [ -f "$DEPLOY_DIR/index.html" ] && [ -f "$DEPLOY_DIR/style.css" ] && [ -f "$DEPLOY_DIR/script.js" ]; then
    log "✅ Deploy concluído com sucesso!"
    log "📁 Arquivos em: $DEPLOY_DIR/"
    log "🌐 Site pronto para publicação!"
else
    error "❌ Erro no deploy - arquivos principais não encontrados!"
    exit 1
fi

# Estatísticas do deploy
echo ""
log "📊 Estatísticas do Deploy:"
echo "   - Arquivos HTML: $(find $DEPLOY_DIR -name '*.html' | wc -l)"
echo "   - Arquivos CSS: $(find $DEPLOY_DIR -name '*.css' | wc -l)"
echo "   - Arquivos JS: $(find $DEPLOY_DIR -name '*.js' | wc -l)"
echo "   - Arquivos PHP: $(find $DEPLOY_DIR -name '*.php' | wc -l)"
echo "   - Total de arquivos: $(find $DEPLOY_DIR -type f | wc -l)"

echo ""
log "🎉 Deploy finalizado! Seu site está pronto para ser publicado na Hostinger." 