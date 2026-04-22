<template>
  <main class="dashboard">
    <section class="dash-wrap">
      <DashboardTabs meta="Controle de dividendos e outros proventos recebidos">
        <button class="btn primary" type="button">Cadastrar provento</button>
      </DashboardTabs>

      <section class="panel panel-wide">
        <header class="panel-head panel-head-block">
          <div>
            <h1 class="section-title">Proventos</h1>
            <p class="section-copy">Acompanhe dividendos, JCP e rendimentos recebidos por ativo.</p>
          </div>
          <span class="panel-sub">{{ dividends.length }} registros</span>
        </header>

        <div class="panel-body">
          <div class="stats stats-compact">
            <article class="stat">
              <div class="stat-kicker">Recebido no mês</div>
              <div class="stat-value">{{ brl(1824.77) }}</div>
              <div class="stat-sub">Distribuído entre 4 ativos</div>
            </article>

            <article class="stat">
              <div class="stat-kicker">Maior pagador</div>
              <div class="stat-value">PETR4</div>
              <div class="stat-sub">{{ brl(913.2) }} em 30 dias</div>
            </article>
          </div>

          <div class="table-wrap" role="region" aria-label="Lista de proventos">
            <table class="table">
              <thead>
                <tr>
                  <th class="th-left">Ativo</th>
                  <th>Tipo</th>
                  <th>Data com</th>
                  <th>Pagamento</th>
                  <th>Valor por cota</th>
                  <th>Quantidade</th>
                  <th class="th-right">Valor recebido</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(dividend, idx) in dividends" :key="`${dividend.ticker}-${dividend.exDate}-${idx}`">
                  <td class="td-left">
                    <div class="asset">
                      <TickerAvatar :ticker="dividend.ticker" :color="colors[idx % colors.length]" />
                      <div class="asset-text">
                        <div class="asset-name">{{ dividend.name }}</div>
                        <div class="asset-ticker">{{ dividend.ticker }}</div>
                      </div>
                    </div>
                  </td>
                  <td>{{ dividend.kind }}</td>
                  <td>{{ dividend.exDate }}</td>
                  <td>{{ dividend.paymentDate }}</td>
                  <td>{{ brl(dividend.valuePerShare) }}</td>
                  <td>{{ quantity(dividend.quantity) }}</td>
                  <td class="td-right">{{ brl(dividend.amountReceived) }}</td>
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

const dividends = [
  { ticker: 'PETR4', name: 'Petrobras', kind: 'Dividendo', exDate: '12/04/2026', paymentDate: '20/04/2026', valuePerShare: 1.14, quantity: 50, amountReceived: 57 },
  { ticker: 'BBSE3', name: 'BB Seguridade', kind: 'JCP', exDate: '08/04/2026', paymentDate: '19/04/2026', valuePerShare: 0.68, quantity: 250, amountReceived: 170 },
  { ticker: 'VALE3', name: 'Vale', kind: 'Dividendo', exDate: '02/04/2026', paymentDate: '16/04/2026', valuePerShare: 2.35, quantity: 90, amountReceived: 211.5 },
  { ticker: 'BBAS3', name: 'Banco do Brasil', kind: 'JCP', exDate: '28/03/2026', paymentDate: '12/04/2026', valuePerShare: 0.91, quantity: 390, amountReceived: 354.9 },
];

function brl(value) {
  return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(Number(value) || 0);
}

function quantity(value) {
  return new Intl.NumberFormat('pt-BR').format(Number(value) || 0);
}
</script>

