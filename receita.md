# InvestTrack

Sistema de gestão de portfólio de investimentos desenvolvido em **PHP** utilizando **Clean Architecture** e **Domain-Driven Design (DDD)**, com controle de operações, cálculo automático de preço médio, registro de dividendos e análise de performance da carteira.

---

# 🎯 Objetivo do Projeto

Criar uma aplicação que permita ao usuário:

- Registrar **compras e vendas de ativos**
- **Acompanhar carteira de investimentos**
- Calcular **preço médio automaticamente**
- Acompanhar **lucro e prejuízo**
- Registrar **dividendos recebidos**
- Visualizar **rentabilidade e gráficos de performance**

A aplicação funciona como um **mini agregador de investimentos**, inspirado em plataformas como Kinvo e Status Invest.

---

# 🧱 Stack Tecnológica

## Backend

- PHP 8.3
- Laravel 11
- PostgreSQL
- Redis
- Laravel Queue

## Frontend

- React + TypeScript
  ou
- Vue 3 + Pinia

## Infraestrutura

- Docker
- Nginx
- GitHub Actions (CI/CD)

## Testes

- PHPUnit
  ou
- Pest

## Qualidade de Código

- PHPStan
- Laravel Pint
- Prettier

---

# 🧠 Arquitetura

O projeto deve seguir **Clean Architecture + DDD**.

Separação de responsabilidades:

| Camada         | Responsabilidade              |
| -------------- | ----------------------------- |
| Domain         | Regras de negócio             |
| Application    | Casos de uso                  |
| Infrastructure | Banco de dados, APIs externas |
| Interfaces     | Controllers e rotas           |

Estrutura sugerida:

```
src/

Domain/
    Asset/
    Portfolio/
    Operation/
    Dividend/

Application/
    UseCases/

Infrastructure/
    Persistence/
    ExternalAPIs/

Interfaces/
    Http/
    Controllers/
```

---

# 🧩 Domínios do Sistema

## User

Representa o usuário da aplicação.

Campos principais:

```
id
name
email
password
created_at
```

---

## Portfolio

Carteira de investimentos do usuário.

Um usuário pode possuir **múltiplas carteiras**.

Exemplos:

```
Carteira Dividendos
Carteira Aposentadoria
Carteira Trade
```

Campos:

```
id
user_id
name
created_at
```

---

## Asset

Representa um ativo financeiro.

Exemplos:

```
PETR4
VALE3
HGLG11
BTC
ETH
```

Campos:

```
id
ticker
name
type
created_at
```

Tipos possíveis:

```
stock
fii
crypto
etf
bond
```

---

## Operation

Registro de operações de compra ou venda.

Campos:

```
id
portfolio_id
asset_id
type
quantity
price
date
created_at
```

Tipos de operação:

```
BUY
SELL
```

---

## Dividend

Representa proventos pagos por um ativo.

Campos:

```
id
asset_id
value_per_share
date
created_at
```

---

## DividendReceipt

Registro de dividendos recebidos pelo usuário.

Campos:

```
id
portfolio_id
dividend_id
amount_received
created_at
```

---

# 🧾 Regras de Negócio

## Operações

### Regra 1 — Venda limitada

Usuário **não pode vender mais ativos do que possui**.

Exemplo:

```
Usuário possui: 100 ações
Tentativa de venda: 150 ações

Resultado: operação inválida
```

---

### Regra 2 — Cálculo de preço médio

Preço médio é recalculado **somente em compras**.

Fórmula:

```
novo_preco_medio =
((quantidade_atual × preco_medio_atual) + (quantidade_compra × preco_compra))
/ nova_quantidade
```

---

### Regra 3 — Venda não altera preço médio

Ao vender:

- apenas reduz quantidade
- preço médio permanece o mesmo

---

### Regra 4 — Lucro realizado

Venda gera lucro ou prejuízo:

```
lucro =
(preco_venda - preco_medio) × quantidade_vendida
```

---

### Regra 5 — Lucro não realizado

Calculado com base no preço atual do ativo:

```
lucro =
(preco_atual - preco_medio) × quantidade
```

---

## Dividendos

Dividendos possuem:

```
ativo
data
valor_por_cota
```

Valor recebido:

```
valor_por_cota × quantidade_possuida_na_data
```

A quantidade considerada deve ser a da **data com**.

---

# 📊 Métricas Calculadas

## Valor total da posição

```
quantidade × preço_atual
```

---

## Valor investido

```
quantidade × preço_medio
```

---

## Lucro / Prejuízo

```
valor_atual - valor_investido
```

---

## Dividend Yield

```
dividendos_12_meses / valor_investido
```

---

# 📈 Funcionalidades

## Dashboard

Deve apresentar:

- Valor total investido
- Valor atual da carteira
- Lucro / prejuízo total
- Dividendos recebidos
- Rentabilidade

---

## Carteira

Lista de ativos com:

```
ativo
quantidade
preço médio
valor atual
lucro/prejuízo
```

---

## Operações

Histórico:

```
data
ativo
tipo
quantidade
preço
```

---

## Dividendos

Histórico:

```
ativo
data
valor_por_cota
valor_recebido
```

---

## Relatórios

- Rentabilidade mensal
- Dividendos por ativo
- Lucro realizado
- Performance da carteira

---

# 🔌 Integração com APIs externas

O sistema deve integrar com APIs de mercado para buscar preços atualizados.

Possíveis fontes:

- Yahoo Finance
- AlphaVantage

Atualização automática de preços:

```
scheduler: a cada 5 minutos
```

---

# ⚙️ Processamento Assíncrono

Utilizar **Laravel Queue + Redis** para:

- atualização de preços
- cálculo de métricas
- processamento de dividendos

---

# 🗄️ Modelagem de Banco de Dados

Tabelas principais:

```
users
portfolios
assets
operations
dividends
dividend_receipts
```

---

# 🧪 Testes

Cobertura mínima esperada:

- cálculo de preço médio
- validação de venda maior que posição
- cálculo de dividendos
- cálculo de lucro/prejuízo
- criação de operações

---

# 🐳 Docker

O projeto deve ser executável via Docker.

Serviços:

```
app
nginx
postgres
redis
```

Executar:

```
docker compose up
```

---

# 🔄 CI/CD

Pipeline usando **GitHub Actions**.

Etapas:

```
lint
static analysis
tests
build
```

---

# 📚 Documentação da API

Gerar documentação com:

- OpenAPI
- Swagger

Endpoints principais:

```
POST /operations
GET /portfolio
GET /assets
GET /dividends
```

---

# 🌱 Seed de Dados

Criar seeds para:

- ativos populares
- operações de exemplo
- dividendos

Exemplos de ativos:

```
PETR4
VALE3
ITUB4
HGLG11
BTC
ETH
```

---

# 📊 Futuras melhorias

Possíveis evoluções do projeto:

- importação de notas de corretagem
- suporte a múltiplas moedas
- alertas de preço
- metas financeiras
- rebalanceamento automático de carteira
- aplicativo mobile

---

# 📜 Licença

MIT
