<template>
  <main class="dashboard">
    <section class="dash-wrap">
      <DashboardTabs meta="Histórico de compras e vendas da carteira">
        <button class="btn primary" type="button">Novo lançamento</button>
      </DashboardTabs>

      <section class="panel panel-wide">
        <header class="panel-head panel-head-block">
          <div>
            <h1 class="section-title">Lançamentos</h1>
            <p class="section-copy">Registre compras e vendas e acompanhe o histórico completo da carteira.</p>
          </div>
          <span class="panel-sub">{{ entries.length }} movimentos</span>
        </header>

        <div class="panel-body">
          <div class="shortcut-grid">
            <article class="quick-card">
              <span class="quick-label">Compra</span>
              <strong class="quick-value">Adicionar nova entrada</strong>
              <p class="quick-copy">Lance quantidade, preço médio, corretora e observações.</p>
            </article>

            <article class="quick-card">
              <span class="quick-label">Venda</span>
              <strong class="quick-value">Atualizar posição</strong>
              <p class="quick-copy">Mantenha o histórico em ordem para calcular lucro realizado.</p>
            </article>
          </div>

          <div class="table-wrap" role="region" aria-label="Histórico de lançamentos">
            <table class="table">
              <thead>
                <tr>
                  <th class="th-left">Ativo</th>
                  <th>Tipo</th>
                  <th>Data</th>
                  <th>Quantidade</th>
                  <th>Preço</th>
                  <th>Corretora</th>
                  <th class="th-right">Total</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(entry, idx) in entries" :key="`${entry.ticker}-${entry.date}-${idx}`">
                  <td class="td-left">
                    <div class="asset">
                      <TickerAvatar :ticker="entry.ticker" :color="colors[idx % colors.length]" />
                      <div class="asset-text">
                        <div class="asset-name">{{ entry.name }}</div>
                        <div class="asset-ticker">{{ entry.ticker }}</div>
                      </div>
                    </div>
                  </td>
                  <td>
                    <span class="status-pill" :class="entry.type === 'Compra' ? 'status-buy' : 'status-sell'">
                      {{ entry.type }}
                    </span>
                  </td>
                  <td>{{ entry.date }}</td>
                  <td>{{ quantity(entry.quantity) }}</td>
                  <td>{{ brl(entry.price) }}</td>
                  <td>{{ entry.broker }}</td>
                  <td class="td-right">{{ brl(entry.total) }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </section>
    </section>
  </main>
</template>

<script setup>
import DashboardTabs from '../components/DashboardTabs.vue';
import TickerAvatar from '../components/TickerAvatar.vue';

const colors = ['#4de1c1', '#6aa7ff', '#ffd36a', '#b77bff', '#ff6a7a', '#7cff6a'];

const entries = [
  { ticker: 'BBAS3', name: 'Banco do Brasil', type: 'Compra', date: '18/04/2026', quantity: 120, price: 24.12, broker: 'NuInvest', total: 2894.4 },
  { ticker: 'VALE3', name: 'Vale', type: 'Compra', date: '15/04/2026', quantity: 30, price: 58.47, broker: 'XP', total: 1754.1 },
  { ticker: 'PETR4', name: 'Petrobras', type: 'Venda', date: '10/04/2026', quantity: 12, price: 36.83, broker: 'Inter', total: 441.96 },
  { ticker: 'BBSE3', name: 'BB Seguridade', type: 'Compra', date: '05/04/2026', quantity: 80, price: 41.3, broker: 'NuInvest', total: 3304 },
];

function brl(value) {
  return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(Number(value) || 0);
}

function quantity(value) {
  return new Intl.NumberFormat('pt-BR').format(Number(value) || 0);
}
</script>

