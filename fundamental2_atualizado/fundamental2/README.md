# Jogos Interclasse SESI — Sistema de inscrições

Sistema web para cadastro de alunos do 6º ao 9º ano (HTML, CSS, JavaScript, Bootstrap 5, PHP e MySQL), com painel administrativo, estatísticas e gráficos (Chart.js).

## Requisitos

- XAMPP (ou Apache + PHP 8.1+ com extensão `pdo_mysql`)
- MySQL / MariaDB

## Instalação

1. Coloque a pasta do projeto em `htdocs` (ex.: `C:\xampp\htdocs\fundamental2`).

2. No **phpMyAdmin**, crie o banco `sesi_interclasse` (utf8mb4_unicode_ci) e importe o arquivo [database/schema.sql](database/schema.sql).

3. Configure o MySQL em [api/config.php](api/config.php) (ou copie de [api/config.example.php](api/config.example.php)). Ajuste `user`, `pass` e `name` se necessário.

4. Crie o usuário administrador inicial abrindo no navegador (uma vez):

   `http://localhost/fundamental2/api/setup.php`

   Usuário padrão: **admin** · Senha: **admin123**

   Remova ou proteja `api/setup.php` em produção.

5. Acesse o site:

   - Cadastro público: `http://localhost/fundamental2/index.php`
   - Login admin: `http://localhost/fundamental2/admin/login.php`

## Personalização

- **Logo SESI:** substitua [assets/img/logo-sesi.svg](assets/img/logo-sesi.svg) pelo arquivo oficial.
- **Rodapé (fotos e nomes):** edite [includes/footer.php](includes/footer.php) e adicione imagens em `assets/img/`.

## Estrutura principal

| Caminho | Descrição |
|--------|-----------|
| `index.php` | Página pública com formulário e datas dos jogos |
| `admin/login.php` | Login do administrador |
| `admin/dashboard.php` | Painel com KPIs, gráficos e lista de alunos |
| `api/*.php` | Endpoints JSON (cadastro, login, alunos, estatísticas) |

## Segurança

- Troque a senha do admin após o primeiro acesso (atualize o hash em `admins` via phpMyAdmin ou script próprio).
- Em produção, use HTTPS e restrinja acesso ao painel (rede escolar, VPN ou autenticação adicional).

## Licença do projeto

Uso educacional / institucional. O nome e a identidade **SESI** pertencem aos respectivos titulares; substitua logos conforme a política da sua unidade.
