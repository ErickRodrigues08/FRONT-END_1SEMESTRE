# Sistema de Controle de Funcionários

Sistema web em PHP + MySQL para gestão de funcionários, ponto, folha de pagamento, serviços e chat.

## Requisitos

- XAMPP (Apache + MySQL + PHP 8+)
- Navegador moderno

## Instalação

1. Copie a pasta `sistema_func` para `C:\xampp\htdocs\sistema_func`
2. Inicie Apache e MySQL no XAMPP
3. Acesse `http://localhost/sistema_func/database/install.php` para criar o banco e tabelas
4. Acesse `http://localhost/sistema_func`

## Acesso

- **Administrador:** usuário `admin` / senha `admin123`
- **Funcionário:** cadastre-se em "Área do Funcionário" e use o ID gerado + e-mail

## Estrutura

- `funcionario/` — área do funcionário
- `admin/` — área do administrador
- `api/` — endpoints PHP (JSON)
- `database/schema.sql` — estrutura do banco
