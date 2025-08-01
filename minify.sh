#!/bin/bash

# Script para minificar CSS e JS para melhor performance

echo "🔧 Iniciando minificação de arquivos..."

# Função para minificar CSS
minify_css() {
    local input_file="$1"
    local output_file="$2"
    
    echo "📝 Minificando CSS: $input_file -> $output_file"
    
    # Remover comentários, espaços extras e quebras de linha
    cat "$input_file" | \
    sed 's|/\*.*\*/||g' | \
    sed 's/[[:space:]]\+/ /g' | \
    sed 's/[[:space:]]*{[[:space:]]*/{/g' | \
    sed 's/[[:space:]]*}[[:space:]]*/}/g' | \
    sed 's/[[:space:]]*:[[:space:]]*/:/g' | \
    sed 's/[[:space:]]*;[[:space:]]*/;/g' | \
    sed 's/[[:space:]]*,[[:space:]]*/,/g' | \
    tr -d '\n' | \
    sed 's/;}/}/g' > "$output_file"
    
    echo "✅ CSS minificado: $(wc -c < "$output_file") bytes"
}

# Função para minificar JS
minify_js() {
    local input_file="$1"
    local output_file="$2"
    
    echo "📝 Minificando JS: $input_file -> $output_file"
    
    # Remover comentários, espaços extras e quebras de linha
    cat "$input_file" | \
    sed 's|//.*||g' | \
    sed 's|/\*.*\*/||g' | \
    sed 's/[[:space:]]\+/ /g' | \
    sed 's/[[:space:]]*{[[:space:]]*/{/g' | \
    sed 's/[[:space:]]*}[[:space:]]*/}/g' | \
    sed 's/[[:space:]]*;[[:space:]]*/;/g' | \
    sed 's/[[:space:]]*,[[:space:]]*/,/g' | \
    tr -d '\n' | \
    sed 's/;}/}/g' > "$output_file"
    
    echo "✅ JS minificado: $(wc -c < "$output_file") bytes"
}

# Criar diretório para arquivos minificados
mkdir -p minified

# Minificar CSS
if [ -f "style.css" ]; then
    minify_css "style.css" "minified/style.min.css"
else
    echo "❌ Arquivo style.css não encontrado"
fi

# Minificar JS
if [ -f "script.js" ]; then
    minify_js "script.js" "minified/script.min.js"
else
    echo "❌ Arquivo script.js não encontrado"
fi

echo ""
echo "📊 Estatísticas de minificação:"
echo "   - CSS original: $(wc -c < style.css) bytes"
echo "   - CSS minificado: $(wc -c < minified/style.min.css) bytes"
echo "   - Redução CSS: $(( 100 - ($(wc -c < minified/style.min.css) * 100) / $(wc -c < style.css) ))%"
echo ""
echo "   - JS original: $(wc -c < script.js) bytes"
echo "   - JS minificado: $(wc -c < minified/script.min.js) bytes"
echo "   - Redução JS: $(( 100 - ($(wc -c < minified/script.min.js) * 100) / $(wc -c < script.js) ))%"

echo ""
echo "🎉 Minificação concluída! Arquivos em: minified/" 