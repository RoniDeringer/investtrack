<template>
  <main class="dashboard">
    <section class="dash-wrap">
      <DashboardTabs :meta="asOf ? `Atualizado: ${asOf}` : ''">
        <button class="btn ghost" type="button" @click="load" :disabled="loading">
          {{ loading ? 'Atualizando...' : 'Atualizar' }}
        </button>
      </DashboardTabs>

      <div class="portfolio-hero">
        <div class="stats stats-column">
          <article class="stat">
            <div class="stat-kicker">Patrimônio total</div>
            <div class="stat-value">{{ brl(totals?.patrimony ?? 0) }}</div>
            <div class="stat-sub">{{ brl(totals?.invested ?? 0) }} investidos</div>
          </article>

          <article class="stat">
            <div class="stat-kicker">Lucro total</div>
            <div class="stat-value" :class="profitClass">{{ signedBrl(totals?.profit ?? 0) }}</div>
            <div class="stat-sub" :class="profitClass">{{ signedPct(totals?.profit_pct) }}</div>
          </article>

          <article class="stat">
            <div class="stat-kicker">Rentabilidade</div>
            <div class="stat-value">{{ pct(totals?.profit_pct) }}</div>
            <div class="stat-sub">Sobre o valor investido</div>
          </article>
        </div>

        <section class="panel chart-panel">
          <header class="panel-head">
            <h2 class="panel-title">Alocação</h2>
            <span class="panel-sub">Passe o mouse sobre a fatia</span>
          </header>

          <div class="panel-body panel-body-center">
            <PieChart :slices="pieSlices" :size="280" />
          </div>
        </section>
      </div>

      <div class="dash-grid">
        <section class="panel panel-wide">
          <header class="panel-head">
            <h2 class="panel-title">Ações compradas</h2>
            <span v-if="holdings.length" class="panel-sub">{{ holdings.length }} ativos</span>
          </header>

          <div class="panel-body">
            <div v-if="error" class="callout error">{{ error }}</div>
            <div v-else-if="loading" class="callout">Carregando carteira...</div>
            <div v-else-if="!holdings.length" class="callout">Nenhum ativo encontrado.</div>

            <div v-else class="table-wrap" role="region" aria-label="Tabela de ações">
              <table class="table">
                <thead>
                  <tr>
                    <th class="th-left">Ativo</th>
                    <th>Qtd</th>
                    <th>Preço médio</th>
                    <th>Preço atual</th>
                    <th>Variação</th>
                    <th class="th-right">Saldo total</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(holding, idx) in holdings" :key="holding.ticker">
                    <td class="td-left">
                      <div class="asset">
                        <TickerAvatar
                          :ticker="holding.ticker || ''"
                          :logo-url="holding.logo_url || null"
                          :color="colors[idx % colors.length]"
                        />
                        <div class="asset-text">
                          <div class="asset-name">{{ holding.name || holding.ticker }}</div>
                          <div class="asset-ticker">{{ holding.ticker }}</div>
                        </div>
                      </div>
                    </td>
                    <td>{{ int(holding.quantity) }}</td>
                    <td>{{ holding.average_price ? brl(holding.average_price) : '-' }}</td>
                    <td>{{ holding.current_price ? brl(holding.current_price) : '-' }}</td>
                    <td :class="chgClass(holding.change_pct)">{{ signedPct(holding.change_pct) }}</td>
                    <td class="td-right">{{ brl(holding.position_value ?? 0) }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </section>
      </div>
    </section>
  </main>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue';
import axios from 'axios';

import DashboardTabs from '../components/DashboardTabs.vue';
import PieChart from '../components/PieChart.vue';
import TickerAvatar from '../components/TickerAvatar.vue';

const loading = ref(false);
const error = ref(null);
const data = ref(null);

const colors = ['#4de1c1', '#6aa7ff', '#ffd36a', '#b77bff', '#ff6a7a', '#7cff6a'];

const totals = computed(() => data.value?.totals ?? null);
const holdings = computed(() => data.value?.holdings ?? []);
const allocation = computed(() => data.value?.allocation ?? []);
const asOf = computed(() => data.value?.as_of ?? null);

const profitClass = computed(() => {
  const value = Number(totals.value?.profit ?? 0);
  if (value > 0) return 'pos';
  if (value < 0) return 'neg';
  return '';
});

const pieSlices = computed(() =>
  allocation.value.map((row, idx) => ({
    label: row.ticker,
    value: row.value,
    color: colors[idx % colors.length],
  })),
);

function brl(value) {
  const number = Number(value) || 0;
  return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(number);
}

function signedBrl(value) {
  const number = Number(value) || 0;
  const formatted = brl(Math.abs(number));
  return number > 0 ? `+${formatted}` : number < 0 ? `-${formatted}` : formatted;
}

function pct(value) {
  if (value === null || value === undefined) return '-';
  const number = Number(value);
  if (Number.isNaN(number)) return '-';
  return `${number.toFixed(2)}%`;
}

function signedPct(value) {
  if (value === null || value === undefined) return '-';
  const number = Number(value);
  if (Number.isNaN(number)) return '-';
  const formatted = `${Math.abs(number).toFixed(2)}%`;
  return number > 0 ? `+${formatted}` : number < 0 ? `-${formatted}` : formatted;
}

function chgClass(value) {
  const number = Number(value);
  if (Number.isNaN(number)) return '';
  if (number > 0) return 'pos';
  if (number < 0) return 'neg';
  return '';
}

function int(value) {
  const number = Number(value);
  return Number.isFinite(number) ? Math.trunc(number) : 0;
}

async function load() {
  loading.value = true;
  error.value = null;

  try {
    const response = await axios.get('/api/portfolio');
    data.value = response.data;
  } catch {
    data.value = null;
    error.value = 'Não foi possível carregar a carteira agora.';
  } finally {
    loading.value = false;
  }
}

onMounted(load);
</script>

