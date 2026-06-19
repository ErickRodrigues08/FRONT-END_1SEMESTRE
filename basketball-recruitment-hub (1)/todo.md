# Basketball Recruitment Hub - TODO

## Banco de Dados e Schema
- [x] Criar tabelas: athletes, highlights, colleges, coaches, emailCampaigns, emailRecipients, emailTemplates, recommendations, emailOpens
- [ ] Seed de dados: faculdades e treinadores dos EUA
- [x] Criar índices para performance

## Backend - Autenticação e Perfil
- [x] Implementar rota de perfil do atleta (criar, atualizar)
- [ ] Implementar upload de foto de perfil
- [x] Validação de dados do atleta

## Backend - Highlights
- [x] Implementar CRUD de highlights
- [x] Gerar URLs pré-assinadas S3 para upload
- [x] Suporte para YouTube links
- [ ] Validação de vídeos

## Backend - Faculdades e Treinadores
- [x] Implementar busca de faculdades (filtro por divisão, estado, nome)
- [x] Implementar listagem de treinadores por faculdade
- [x] API para obter detalhes de faculdade

## Backend - Recomendações
- [x] Implementar algoritmo de recomendação (altura, posição, estatísticas)
- [x] Calcular score de compatibilidade (0-100)
- [x] Armazenar recomendações em banco de dados

## Backend - Sistema de Emails
- [ ] Integração com SendGrid
- [x] Implementar CRUD de templates de email
- [x] Implementar CRUD de campanhas
- [ ] Envio de emails em massa com personalização
- [ ] Pixel tracking para aberturas de email
- [ ] Rastreamento de status (enviado, aberto, respondido)

## Backend - Dashboard
- [x] Estatísticas gerais (total enviados, taxa abertura, respostas)
- [x] Timeline de aberturas
- [x] Histórico de campanhas

## Frontend - Layout e Navegação
- [x] Implementar navegação principal
- [x] Design elegante e profissional
- [x] Responsividade (desktop e mobile)

## Frontend - Autenticação
- [x] Página de login (via Manus OAuth)
- [x] Redirecionamento pós-login
- [x] Logout

## Frontend - Perfil do Atleta
- [x] Página de edição de perfil
- [ ] Upload de foto
- [x] Edição de estatísticas
- [x] Validação de formulário

## Frontend - Highlights
- [x] Página de gerenciamento de highlights
- [x] Upload de vídeos
- [x] Suporte para YouTube links
- [x] Pré-visualização de vídeos
- [x] Organização por categorias

## Frontend - Banco de Faculdades
- [x] Página com listagem de faculdades
- [x] Filtros por divisão, estado, nome
- [x] Recomendações automáticas
- [ ] Seleção múltipla de faculdades
- [ ] Visualização de detalhes da faculdade
- [ ] Listagem de treinadores

## Frontend - Campanhas de Email
- [x] Página de listagem de campanhas
- [ ] Página de criação de campanha
- [ ] Seleção de faculdades/treinadores
- [ ] Escolha de template
- [ ] Personalização de mensagem
- [ ] Preview de email
- [ ] Envio e confirmação

## Frontend - Templates de Email
- [ ] Página de gerenciamento de templates
- [ ] Editor de templates
- [ ] Templates padrão pré-configurados
- [ ] Placeholders para personalização

## Frontend - Dashboard
- [x] Página de dashboard
- [x] Visualização de estatísticas
- [ ] Gráficos de taxa de abertura
- [x] Histórico de campanhas
- [ ] Timeline de eventos

## Testes e Qualidade
- [ ] Testes unitários (vitest)
- [ ] Testes de integração
- [ ] Validação de segurança
- [ ] Testes de responsividade

## Deploy e Finalização
- [ ] Configurar variáveis de ambiente
- [ ] Otimização de performance
- [ ] Documentação de uso
- [ ] Checkpoint final


## Frontend - Página de Detalhes da Faculdade (Nova)
- [x] Criar página de perfil detalhado da faculdade
- [x] Exibir informações completas (nome, divisão, localização, website)
- [x] Listar todos os treinadores (Head Coach, Assistant Coaches)
- [x] Mostrar contatos dos treinadores
- [x] Implementar botão de envio direto de highlights
- [x] Criar modal/formulário de envio rápido
- [ ] Adicionar recomendação de compatibilidade
