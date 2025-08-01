# Site Cajá - Tecnologia Artesanal

Site institucional da Cajá, empresa de tecnologia especializada em desenvolvimento de MVPs e soluções digitais personalizadas.

## 🚀 Como rodar localmente

### Pré-requisitos
- PHP 7.4 ou superior
- Navegador web moderno

### Passos para execução

1. **Abra o terminal** na pasta do projeto:
```bash
cd /caminho/para/WIDGET_CORRIGIDO_FUNCIONAL
```

2. **Inicie o servidor PHP embutido**:
```bash
php -S localhost:8000
```

3. **Acesse o site** no navegador:
```
http://localhost:8000
```

### Funcionalidades disponíveis localmente

✅ **Site completo funcionando**
- Páginas: Início, Serviços, Sobre, Contato
- Design responsivo
- Animações e interações
- Widget de ajuda

✅ **Formulário de contato**
- Validação de campos
- Salvamento em arquivo local (`local_contacts.json`)
- Log de contatos (`contact_log.txt`)
- Modo demo (sem envio de email real)

✅ **Navegação**
- Menu mobile responsivo
- Scroll suave entre seções
- Links funcionais

### Arquivos importantes

- `index.html` - Página principal
- `style.css` - Estilos do site
- `script.js` - JavaScript e interações
- `contact_local.php` - Processamento do formulário (modo local)
- `local_contacts.json` - Contatos salvos localmente
- `contact_log.txt` - Log de atividades

### Estrutura do projeto

```
WIDGET_CORRIGIDO_FUNCIONAL/
├── index.html              # Página principal
├── style.css               # Estilos CSS
├── script.js               # JavaScript
├── contact_local.php       # Formulário local
├── contact.php             # Formulário produção
├── config/                 # Configurações
│   ├── database.php        # Banco produção
│   └── database_local.php  # Banco local
├── admin/                  # Painel administrativo
├── local_contacts.json     # Contatos salvos
└── contact_log.txt         # Log de atividades
```

### Notas importantes

- **Modo local**: O formulário salva os dados em arquivo JSON local
- **Sem banco de dados**: Não é necessário MySQL para desenvolvimento
- **Logs**: Todas as atividades são registradas em `contact_log.txt`
- **Responsivo**: Testado em desktop, tablet e mobile

### Para produção

Para usar em produção, certifique-se de:
1. Configurar o banco de dados MySQL
2. Ajustar as configurações em `config/database.php`
3. Configurar o envio de emails
4. Usar `contact.php` em vez de `contact_local.php`

---

**Desenvolvido por Cajá - Tecnologia Artesanal**
*Transformando ideias em produtos digitais únicos* 