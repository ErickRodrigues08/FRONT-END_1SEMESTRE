# Destino Certo Turismo - Sistema Web

Sistema web em PHP + MySQL para gestão de clientes, pacotes e reservas.

## Requisitos

- XAMPP (Apache + MySQL)
- PHP 8.0+

## Como executar

1. Inicie o Apache e o MySQL no XAMPP.
2. Crie o banco e as tabelas executando o arquivo `init.sql` no phpMyAdmin.
3. Confirme as credenciais em `config.php` (padrão do XAMPP: usuário `root` e senha vazia).
4. Acesse no navegador:

```text
http://localhost/projeto_viagens/index.php
```

## Funcionalidades entregues

- Cadastro de clientes com validação de campos obrigatórios e e-mail.
- Listagem de pacotes com descrição, duração, preço e vagas.
- Reserva de pacotes com validações:
  - cliente e pacote obrigatórios;
  - data futura;
  - quantidade de pessoas;
  - disponibilidade de vagas.
- Histórico de reservas na própria página de reservas.
- Persistência em banco MySQL.
