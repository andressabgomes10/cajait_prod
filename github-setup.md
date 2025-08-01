# 🔐 Configuração de Autenticação GitHub

## Problema de Permissão Detectado

O erro `Permission denied` indica que você precisa configurar a autenticação correta para o GitHub.

## 🔧 Soluções

### Opção 1: Token de Acesso Pessoal (Recomendado)

1. **Criar Token no GitHub:**
   - Acesse: https://github.com/settings/tokens
   - Clique em **"Generate new token (classic)"**
   - Selecione escopos: `repo`, `workflow`
   - Copie o token gerado

2. **Usar o Token:**
```bash
# Remover remote atual
git remote remove origin

# Adicionar com token
git remote add origin https://andressabgomes10:SEU_TOKEN@github.com/andressabgomes10/cajait_prod.git

# Fazer push
git push -u origin main
```

### Opção 2: SSH (Alternativa)

1. **Gerar chave SSH:**
```bash
ssh-keygen -t rsa -b 4096 -C "andressa@cajait.com"
```

2. **Adicionar chave ao GitHub:**
   - Copie a chave pública: `cat ~/.ssh/id_rsa.pub`
   - Vá em: https://github.com/settings/keys
   - Adicione a chave

3. **Usar SSH:**
```bash
# Remover remote atual
git remote remove origin

# Adicionar com SSH
git remote add origin git@github.com:andressabgomes10/cajait_prod.git

# Fazer push
git push -u origin main
```

### Opção 3: GitHub CLI (Mais Fácil)

1. **Instalar GitHub CLI:**
```bash
# macOS
brew install gh

# Ou baixar de: https://cli.github.com/
```

2. **Fazer login:**
```bash
gh auth login
```

3. **Fazer push:**
```bash
git push -u origin main
```

## 🚀 Comandos Prontos

### Para Token de Acesso:
```bash
# Substitua SEU_TOKEN pelo token real
git remote set-url origin https://andressabgomes10:SEU_TOKEN@github.com/andressabgomes10/cajait_prod.git
git push -u origin main
```

### Para SSH:
```bash
git remote set-url origin git@github.com:andressabgomes10/cajait_prod.git
git push -u origin main
```

## ✅ Verificação

Após o push bem-sucedido, você verá:
- ✅ Todos os arquivos no repositório
- ✅ Branch `main` criado
- ✅ Deploy automático configurado (se usar GitHub Actions)

## 🆘 Ainda com Problemas?

1. **Verificar permissões do repositório**
2. **Confirmar se é o dono do repositório**
3. **Verificar se o repositório não está vazio**
4. **Usar GitHub CLI para autenticação automática**

---

**🎯 Recomendação:** Use a **Opção 1 (Token)** para maior segurança e facilidade! 