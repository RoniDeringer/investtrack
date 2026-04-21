<template>
  <main class="dashboard">
    <section class="dash-wrap">
      <header class="dash-head">
        <div class="dash-brand">
          <RouterLink class="dash-home" to="/">
            <AppIcon :size="24" aria-hidden="true" />
            <span class="dash-title">Carteira</span>
          </RouterLink>
          <span class="dash-meta" v-if="asOf">Atualizado: {{ asOf }}</span>
        </div>

        <div class="dash-actions">
          <button class="btn ghost" type="button" @click="load" :disabled="loading">
            {{ loading ? 'Atualizando…' : 'Atualizar' }}
          </button>
        </div>
      </header>

      <div class="stats">
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

      <div class="dash-grid">
        <section class="panel">
          <header class="panel-head">
            <h2 class="panel-title">Alocação</h2>
            <span class="panel-sub">Por valor atual</span>
          </header>

          <div class="panel-body panel-body-split">
            <PieChart :slices="pieSlices" :size="230" />

            <div class="legend" v-if="allocation?.length">
              <div class="legend-row" v-for="(row, idx) in allocation" :key="row.ticker">
                <span class="legend-dot" :style="{ background: colors[idx % colors.length] }"></span>
                <span class="legend-name">{{ row.ticker }}</span>
                <span class="legend-pct">{{ pct(row.pct) }}</span>
              </div>
            </div>
          </div>
        </section>

        <section class="panel panel-wide">
          <header class="panel-head">
            <h2 class="panel-title">Ações compradas</h2>
            <span class="panel-sub" v-if="holdings?.length">{{ holdings.length }} ativos</span>
          </header>

          <div class="panel-body">
            <div v-if="error" class="callout error">{{ error }}</div>
            <div v-else-if="loading" class="callout">Carregando carteira…</div>
            <div v-else-if="!holdings?.length" class="callout">Nenhum ativo encontrado.</div>

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
                  <tr v-for="(h, idx) in holdings" :key="h.ticker">
                    <td class="td-left">
                      <div class="asset">
                        <TickerAvatar
                          :ticker="h.ticker || ''"
                          :logo-url="h.logo_url || null"
                          :color="colors[idx % colors.length]"
                        />
                        <div class="asset-text">
                          <div class="asset-name">{{ h.name || h.ticker }}</div>
                          <div class="asset-ticker">{{ h.ticker }}</div>
                        </div>
                      </div>
                    </td>
                    <td>{{ int(h.quantity) }}</td>
                    <td>{{ h.average_price ? brl(h.average_price) : '—' }}</td>
                    <td>{{ h.current_price ? brl(h.current_price) : '—' }}</td>
                    <td :class="chgClass(h.change_pct)">{{ signedPct(h.change_pct) }}</td>
                    <td class="td-right">{{ brl(h.position_value ?? 0) }}</td>
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
import { RouterLink } from 'vue-router';

import AppIcon from '../components/AppIcon.vue';
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
  const v = Number(totals.value?.profit ?? 0);
  if (v > 0) return 'pos';
  if (v < 0) return 'neg';
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
  const n = Number(value) || 0;
  return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(n);
}

function signedBrl(value) {
  const n = Number(value) || 0;
  const str = brl(Math.abs(n));
  return n > 0 ? `+${str}` : n < 0 ? `-${str}` : str;
}

function pct(value) {
  if (value === null || value === undefined) return '—';
  const n = Number(value);
  if (Number.isNaN(n)) return '—';
  return `${n.toFixed(2)}%`;
}

function signedPct(value) {
  if (value === null || value === undefined) return '—';
  const n = Number(value);
  if (Number.isNaN(n)) return '—';
  const str = `${Math.abs(n).toFixed(2)}%`;
  return n > 0 ? `+${str}` : n < 0 ? `-${str}` : str;
}

function chgClass(value) {
  const n = Number(value);
  if (Number.isNaN(n)) return '';
  if (n > 0) return 'pos';
  if (n < 0) return 'neg';
  return '';
}

function int(value) {
  const n = Number(value);
  return Number.isFinite(n) ? Math.trunc(n) : 0;
}

async function load() {
  loading.value = true;
  error.value = null;

  try {
    const res = await axios.get('/api/portfolio');
    data.value = res.data;
  } catch (e) {
    data.value = null;
    error.value = 'N\u00e3o foi poss\u00edvel carregar a carteira agora.';
  } finally {
    loading.value = false;
  }
}

onMounted(load);
</script>
