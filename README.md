# 📈 InvestTrack — gestão de investimentos

Sistema de gestão de portfólio de investimentos com **operações**, **preço médio**, **dividendos** e **métricas de performance**.

<p align="center">
  <img src="public/favicon.svg" width="110" alt="InvestTrack icon" />
</p>

<p align="center">
  <img alt="Laravel" src="https://img.shields.io/badge/Laravel-11-FF2D20?logo=laravel&logoColor=white" />
  <img alt="PHP" src="https://img.shields.io/badge/PHP-8.3-777BB4?logo=php&logoColor=white" />
  <img alt="Vue" src="https://img.shields.io/badge/Vue-3-4FC08D?logo=vue.js&logoColor=white" />
  <img alt="Vite" src="https://img.shields.io/badge/Vite-5-646CFF?logo=vite&logoColor=white" />
  <img alt="PostgreSQL" src="https://img.shields.io/badge/PostgreSQL-16-4169E1?logo=postgresql&logoColor=white" />
  <img alt="Redis" src="https://img.shields.io/badge/Redis-7-DC382D?logo=redis&logoColor=white" />
  <img alt="Docker" src="https://img.shields.io/badge/Docker-Compose-2496ED?logo=docker&logoColor=white" />
</p>

## 🧰 Tecnologias / Ferramentas

- 🧠 **Backend**: Laravel 11 (PHP 8.3)
- 🎛️ **Banco de dados**: PostgreSQL 16 (Docker)
- ⚡ **Cache / Sessão / Filas**: Redis 7 (driver `phpredis`)
- 🖥️ **Frontend**: Vue 3 + Pinia + Vue Router + Vite
- 🐳 **Infra (dev)**: Docker Compose (containers: `app`, `nginx`, `postgres`, `redis`, `node`, `queue`)
- 🧪 **Testes**: PHPUnit (via `php artisan test`)
- 🤖 **CI**: GitHub Actions (build do frontend + testes)

## 🧠 Conceitos do domínio

- 📦 **Portfolio**: carteira do usuário (múltiplas carteiras por usuário).
- 🏷️ **Asset**: ativo financeiro (ações, FIIs, cripto, etc).
- 🧾 **Operation**: compra/venda (base para preço médio e lucro/prejuízo).
- 💸 **Dividend**: provento por ativo.
- 🧮 **DividendReceipt**: provento recebido pelo usuário/carteira.

## 📜 Regras de negócio (resumo)

### 🧾 Operações

- 🚫 **Venda limitada**: o usuário não pode vender mais do que possui.
- 🧮 **Preço médio**:
  - recalcula **somente em compras**
  - fórmula: `((qtd_atual × pm_atual) + (qtd_compra × preco_compra)) / nova_qtd`
- 🧊 **Venda não altera preço médio**: apenas reduz a quantidade.
- ✅ **Lucro realizado**: `(preco_venda - preco_medio) × quantidade_vendida`.
- 📈 **Lucro não realizado**: `(preco_atual - preco_medio) × quantidade`.

### 💸 Dividendos

- 🗓️ O valor recebido considera a **quantidade na data com**:
  - `valor_por_cota × quantidade_possuida_na_data`

## 🚀 Execução (dev)

### Subir tudo (Docker)

```bash
docker compose up --build -d
```

- App (Nginx): `http://127.0.0.1:8080`
- Vite (HMR): `http://127.0.0.1:5173`

### Artisan (sempre dentro do container)

```bash
docker compose exec app php artisan migrate
docker compose exec app php artisan test
```

## 🧪 Testes

```bash
docker compose exec app php artisan test
```

## 🤖 CI/CD (GitHub Actions)

Workflow que roda automaticamente em `push` e `pull_request`:

- 🧱 `npm run build`
- ✅ `php artisan test`

Arquivo: `.github/workflows/tests.yml`

## 🎨 Ícone / Branding

- Ícone principal (reutilizável): `public/brand/icon.svg`
- Favicon: `public/favicon.svg`
- Componente Vue: `resources/js/components/AppIcon.vue`

