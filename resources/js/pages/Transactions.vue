<template>
  <main class="dashboard">
    <section class="dash-wrap">
      <DashboardTabs meta="Histórico de compras e vendas da carteira">
        <button class="btn primary" type="button" @click="openModal">Novo lançamento</button>
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
              <p class="quick-copy">Lance quantidade, preço médio, data da operação e ativo.</p>
            </article>

            <article class="quick-card">
              <span class="quick-label">Venda</span>
              <strong class="quick-value">Atualizar posição</strong>
              <p class="quick-copy">Mantenha o histórico em ordem para calcular o resultado realizado.</p>
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
                  <th>Preço médio</th>
                  <th>Classe</th>
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
                  <td>{{ entry.assetTypeLabel }}</td>
                  <td class="td-right">{{ brl(entry.total) }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </section>

      <div v-if="isModalOpen" class="modal-backdrop" @click.self="closeModal">
        <section class="modal-card" role="dialog" aria-modal="true" aria-labelledby="new-entry-title">
          <header class="modal-head">
            <div>
              <h2 id="new-entry-title" class="modal-title">Novo lançamento</h2>
              <p class="modal-copy">Preencha os dados da operação e acompanhe o valor total antes de salvar.</p>
            </div>
            <button class="modal-close" type="button" @click="closeModal" aria-label="Fechar modal">×</button>
          </header>

          <div class="modal-tabs" role="tablist" aria-label="Tipo de lançamento">
            <button
              type="button"
              class="modal-tab"
              :class="{ active: form.mode === 'buy' }"
              @click="form.mode = 'buy'"
            >
              Compra
            </button>
            <button
              type="button"
              class="modal-tab"
              :class="{ active: form.mode === 'sell' }"
              @click="form.mode = 'sell'"
            >
              Venda
            </button>
          </div>

          <form class="modal-form" @submit.prevent="submitEntry">
            <label class="field">
              <span class="label">Tipo de ativo</span>
              <select v-model="form.assetType" class="input">
                <option v-for="type in assetTypes" :key="type.value" :value="type.value">
                  {{ type.label }}
                </option>
              </select>
            </label>

            <label class="field">
              <span class="label">Ativo</span>
              <select v-model="form.assetTicker" class="input">
                <option v-for="asset in filteredAssets" :key="asset.ticker" :value="asset.ticker">
                  {{ asset.ticker }} - {{ asset.name }}
                </option>
              </select>
            </label>

            <label class="field">
              <span class="label">Data da {{ modeLabel.toLowerCase() }}</span>
              <input v-model="form.date" class="input" type="date" required />
            </label>

            <label class="field">
              <span class="label">Quantidade</span>
              <input v-model.number="form.quantity" class="input" type="number" min="0" step="0.0001" required />
            </label>

            <label class="field">
              <span class="label">Preço médio</span>
              <input v-model.number="form.price" class="input" type="number" min="0" step="0.0001" required />
            </label>

            <div class="modal-total">
              <span class="modal-total-label">Valor total</span>
              <strong class="modal-total-value">{{ brl(totalValue) }}</strong>
            </div>

            <div class="modal-actions">
              <button class="btn primary" type="submit">Adicionar lançamento</button>
              <button class="btn ghost" type="button" @click="closeModal">Cancelar</button>
            </div>
          </form>
        </section>
      </div>
    </section>
  </main>
</template>

<script setup>
import { computed, reactive, ref, watch } from 'vue';

import DashboardTabs from '../components/DashboardTabs.vue';
import TickerAvatar from '../components/TickerAvatar.vue';

const colors = ['#4de1c1', '#6aa7ff', '#ffd36a', '#b77bff', '#ff6a7a', '#7cff6a'];

const assetTypes = [
  { value: 'stock', label: 'Ação' },
  { value: 'fii', label: 'FII' },
  { value: 'etf', label: 'ETF' },
  { value: 'crypto', label: 'Cripto' },
];

const assets = [
  { ticker: 'BBAS3', name: 'Banco do Brasil', type: 'stock' },
  { ticker: 'BBSE3', name: 'BB Seguridade', type: 'stock' },
  { ticker: 'PETR4', name: 'Petrobras', type: 'stock' },
  { ticker: 'VALE3', name: 'Vale', type: 'stock' },
  { ticker: 'HGLG11', name: 'CSHG Logística', type: 'fii' },
  { ticker: 'BOVA11', name: 'iShares Ibovespa', type: 'etf' },
  { ticker: 'BTC', name: 'Bitcoin', type: 'crypto' },
];

const entries = ref([
  { ticker: 'BBAS3', name: 'Banco do Brasil', type: 'Compra', date: '18/04/2026', quantity: 120, price: 24.12, assetTypeLabel: 'Ação', total: 2894.4 },
  { ticker: 'VALE3', name: 'Vale', type: 'Compra', date: '15/04/2026', quantity: 30, price: 58.47, assetTypeLabel: 'Ação', total: 1754.1 },
  { ticker: 'PETR4', name: 'Petrobras', type: 'Venda', date: '10/04/2026', quantity: 12, price: 36.83, assetTypeLabel: 'Ação', total: 441.96 },
  { ticker: 'BBSE3', name: 'BB Seguridade', type: 'Compra', date: '05/04/2026', quantity: 80, price: 41.3, assetTypeLabel: 'Ação', total: 3304 },
]);

const isModalOpen = ref(false);

const form = reactive({
  mode: 'buy',
  assetType: 'stock',
  assetTicker: 'BBAS3',
  date: new Date().toISOString().slice(0, 10),
  quantity: 100,
  price: 24.15,
});

const filteredAssets = computed(() => assets.filter((asset) => asset.type === form.assetType));

watch(filteredAssets, (list) => {
  if (!list.some((asset) => asset.ticker === form.assetTicker)) {
    form.assetTicker = list[0]?.ticker ?? '';
  }
});

const totalValue = computed(() => {
  const quantityValue = Number(form.quantity) || 0;
  const priceValue = Number(form.price) || 0;
  return quantityValue * priceValue;
});

const modeLabel = computed(() => (form.mode === 'buy' ? 'Compra' : 'Venda'));

function resetForm() {
  form.mode = 'buy';
  form.assetType = 'stock';
  form.assetTicker = 'BBAS3';
  form.date = new Date().toISOString().slice(0, 10);
  form.quantity = 100;
  form.price = 24.15;
}

function openModal() {
  resetForm();
  isModalOpen.value = true;
}

function closeModal() {
  isModalOpen.value = false;
}

function submitEntry() {
  const selectedAsset = assets.find((asset) => asset.ticker === form.assetTicker);
  const selectedType = assetTypes.find((type) => type.value === form.assetType);

  if (!selectedAsset || !selectedType) {
    return;
  }

  entries.value.unshift({
    ticker: selectedAsset.ticker,
    name: selectedAsset.name,
    type: modeLabel.value,
    date: formatDate(form.date),
    quantity: Number(form.quantity) || 0,
    price: Number(form.price) || 0,
    assetTypeLabel: selectedType.label,
    total: totalValue.value,
  });

  closeModal();
}

function formatDate(value) {
  if (!value) return '-';
  return new Intl.DateTimeFormat('pt-BR').format(new Date(`${value}T00:00:00`));
}

function brl(value) {
  return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(Number(value) || 0);
}

function quantity(value) {
  return new Intl.NumberFormat('pt-BR').format(Number(value) || 0);
}
</script>

