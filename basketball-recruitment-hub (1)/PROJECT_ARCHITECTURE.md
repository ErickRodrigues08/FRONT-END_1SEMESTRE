# Basketball Recruitment Hub - Arquitetura do Sistema

## Visão Geral

Sistema completo de recrutamento esportivo que conecta atletas de basquete a faculdades e treinadores dos EUA, permitindo envio profissional de highlights e rastreamento de oportunidades.

## Stack Tecnológico

- **Frontend:** React 19 + Tailwind CSS 4 + TypeScript
- **Backend:** Express 4 + tRPC 11 + Node.js
- **Banco de Dados:** MySQL (TiDB)
- **Autenticação:** JWT + Manus OAuth
- **Armazenamento de Arquivos:** S3
- **Email:** SendGrid API
- **Recomendações:** Algoritmo baseado em perfil (altura, posição, nível)

## Modelo de Dados

### Tabelas Principais

#### 1. **users** (Atletas)
- id (PK)
- openId (Manus OAuth)
- email
- name
- role (user/admin)
- createdAt, updatedAt, lastSignedIn

#### 2. **athletes** (Perfil do Atleta)
- id (PK)
- userId (FK → users)
- age
- height (em cm)
- position (PG, SG, SF, PF, C)
- school (escola/time atual)
- statistics (JSON: PPG, RPG, APG, etc)
- bio
- profileImageUrl
- createdAt, updatedAt

#### 3. **highlights** (Vídeos de Highlights)
- id (PK)
- athleteId (FK → athletes)
- title
- description
- videoUrl (YouTube ou S3)
- category (season, game, training)
- duration (segundos)
- uploadedAt
- createdAt

#### 4. **colleges** (Faculdades)
- id (PK)
- name
- division (NCAA D1, D2, D3, NAIA, JUCO)
- state
- city
- website
- latitude, longitude
- createdAt

#### 5. **coaches** (Treinadores)
- id (PK)
- collegeId (FK → colleges)
- firstName, lastName
- email
- position (Head Coach, Assistant Coach)
- phone
- createdAt

#### 6. **emailCampaigns** (Campanhas de Email)
- id (PK)
- athleteId (FK → athletes)
- templateId (FK → emailTemplates)
- status (draft, scheduled, sent, completed)
- scheduledFor
- sentAt
- createdAt, updatedAt

#### 7. **emailRecipients** (Destinatários de Email)
- id (PK)
- campaignId (FK → emailCampaigns)
- coachId (FK → coaches)
- status (pending, sent, opened, replied)
- sentAt, openedAt, repliedAt
- trackingPixelId

#### 8. **emailTemplates** (Templates de Email)
- id (PK)
- userId (FK → users)
- name
- subject
- body (HTML com placeholders)
- isDefault (boolean)
- createdAt, updatedAt

#### 9. **recommendations** (Recomendações de Faculdades)
- id (PK)
- athleteId (FK → athletes)
- collegeId (FK → colleges)
- score (0-100)
- reason (JSON: matching criteria)
- createdAt

#### 10. **emailOpens** (Rastreamento de Aberturas)
- id (PK)
- recipientId (FK → emailRecipients)
- openedAt
- userAgent
- ipAddress

## Endpoints da API (tRPC Procedures)

### Autenticação
- `auth.me` - Obter usuário atual
- `auth.logout` - Fazer logout

### Perfil do Atleta
- `athlete.getProfile` - Obter perfil completo
- `athlete.updateProfile` - Atualizar dados do atleta
- `athlete.getStatistics` - Obter estatísticas
- `athlete.updateStatistics` - Atualizar estatísticas

### Highlights
- `highlights.list` - Listar highlights do atleta
- `highlights.create` - Criar novo highlight (gerar URL de upload S3)
- `highlights.update` - Atualizar informações do highlight
- `highlights.delete` - Deletar highlight
- `highlights.getUploadUrl` - Gerar URL pré-assinada para upload S3

### Faculdades e Treinadores
- `colleges.search` - Buscar faculdades (filtro por divisão, estado, nome)
- `colleges.getById` - Obter detalhes da faculdade
- `colleges.list` - Listar todas as faculdades
- `coaches.getByCollege` - Listar treinadores de uma faculdade

### Recomendações
- `recommendations.getForAthlete` - Obter recomendações personalizadas
- `recommendations.calculateScore` - Calcular score de compatibilidade

### Campanhas de Email
- `campaigns.create` - Criar nova campanha
- `campaigns.list` - Listar campanhas do atleta
- `campaigns.getById` - Obter detalhes da campanha
- `campaigns.update` - Atualizar campanha (draft apenas)
- `campaigns.send` - Enviar campanha
- `campaigns.delete` - Deletar campanha (draft apenas)
- `campaigns.getStats` - Obter estatísticas (enviados, abertos, respondidos)

### Templates de Email
- `templates.list` - Listar templates
- `templates.getById` - Obter template específico
- `templates.create` - Criar novo template
- `templates.update` - Atualizar template
- `templates.delete` - Deletar template
- `templates.getDefaults` - Obter templates padrão

### Dashboard
- `dashboard.getStats` - Obter estatísticas gerais (total enviados, taxa abertura, etc)
- `dashboard.getRecentCampaigns` - Obter campanhas recentes
- `dashboard.getEmailOpenTimeline` - Timeline de aberturas de email

## Fluxo de Uso Principal

### 1. Cadastro e Perfil do Atleta
1. Usuário faz login via Manus OAuth
2. Sistema cria registro em `users`
3. Usuário completa perfil em `athletes` (altura, posição, estatísticas, etc)
4. Usuário faz upload de foto de perfil para S3

### 2. Upload de Highlights
1. Atleta acessa página de highlights
2. Escolhe entre upload direto ou link YouTube
3. Para upload: sistema gera URL pré-assinada S3
4. Atleta faz upload do vídeo
5. Sistema registra em `highlights`

### 3. Seleção de Faculdades e Envio de Emails
1. Atleta acessa página de faculdades
2. Sistema sugere faculdades baseado em perfil (algoritmo de recomendação)
3. Atleta filtra por divisão, estado, etc
4. Atleta seleciona múltiplas faculdades/treinadores
5. Escolhe ou cria template de email
6. Sistema personaliza email com dados do atleta
7. Envia emails em massa via SendGrid
8. Registra em `emailCampaigns` e `emailRecipients`

### 4. Rastreamento e Dashboard
1. Sistema rastreia aberturas de email via pixel tracking
2. Dashboard mostra estatísticas em tempo real
3. Atleta pode ver histórico de campanhas
4. Visualiza taxa de abertura, respostas, etc

## Algoritmo de Recomendação

**Critérios de Compatibilidade:**
1. **Altura:** Comparar com média da posição na divisão
2. **Posição:** Match exato com necessidades da faculdade
3. **Nível de Jogo:** Comparar estatísticas com nível da divisão
4. **Localização:** Preferência geográfica (opcional)

**Score (0-100):**
- Altura: 30 pontos
- Posição: 40 pontos
- Estatísticas: 20 pontos
- Localização: 10 pontos

Faculdades com score > 70 são recomendadas.

## Segurança

- JWT para autenticação de sessão
- Validação de inputs em todas as APIs
- Rate limiting em endpoints de email
- Proteção contra CSRF
- Dados sensíveis criptografados
- HTTPS obrigatório
- Pixel tracking anônimo (sem dados pessoais)

## Estrutura de Pastas

```
basketball-recruitment-hub/
├── client/
│   ├── src/
│   │   ├── pages/
│   │   │   ├── Home.tsx
│   │   │   ├── Dashboard.tsx
│   │   │   ├── AthleteProfile.tsx
│   │   │   ├── Highlights.tsx
│   │   │   ├── Colleges.tsx
│   │   │   ├── Campaigns.tsx
│   │   │   └── Templates.tsx
│   │   ├── components/
│   │   │   ├── DashboardLayout.tsx
│   │   │   ├── CollegeCard.tsx
│   │   │   ├── HighlightCard.tsx
│   │   │   ├── CampaignForm.tsx
│   │   │   └── ...
│   │   ├── lib/
│   │   │   └── trpc.ts
│   │   ├── App.tsx
│   │   └── index.css
│   └── public/
├── server/
│   ├── routers/
│   │   ├── auth.ts
│   │   ├── athlete.ts
│   │   ├── highlights.ts
│   │   ├── colleges.ts
│   │   ├── campaigns.ts
│   │   ├── templates.ts
│   │   ├── recommendations.ts
│   │   └── dashboard.ts
│   ├── db.ts
│   ├── routers.ts
│   └── _core/
├── drizzle/
│   ├── schema.ts
│   └── migrations/
├── storage/
│   └── index.ts
└── shared/
    └── const.ts
```

## Próximas Etapas

1. ✅ Inicializar projeto
2. ⏳ Configurar banco de dados e schema
3. ⏳ Implementar autenticação e perfil
4. ⏳ Sistema de upload de vídeos
5. ⏳ Banco de dados de faculdades
6. ⏳ Sistema de emails
7. ⏳ Dashboard e recomendações
8. ⏳ Frontend UI
9. ⏳ Testes e ajustes finais
