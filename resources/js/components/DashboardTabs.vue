<template>
  <header class="dash-head dash-head-stack">
    <div class="dash-brand-row">
      <RouterLink class="dash-home" to="/">
        <AppIcon :size="24" aria-hidden="true" />
        <span class="dash-title">InvestTrack</span>
      </RouterLink>

      <span class="dash-meta" v-if="meta">{{ meta }}</span>
    </div>

    <div class="dash-toolbar">
      <nav class="dash-tabs" aria-label="Seções da carteira">
        <RouterLink
          v-for="tab in tabs"
          :key="tab.to"
          class="dash-tab"
          :class="{ active: route.path === tab.to }"
          :to="tab.to"
        >
          {{ tab.label }}
        </RouterLink>
      </nav>

      <div class="dash-actions">
        <slot />
      </div>
    </div>
  </header>
</template>

<script setup>
import { RouterLink, useRoute } from 'vue-router';

import AppIcon from './AppIcon.vue';

defineProps({
  meta: { type: String, default: '' },
});

const route = useRoute();

const tabs = [
  { label: 'Carteira', to: '/carteira' },
  { label: 'Lançamentos', to: '/lancamentos' },
  { label: 'Proventos', to: '/proventos' },
];
</script>

