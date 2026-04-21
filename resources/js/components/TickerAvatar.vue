<template>
  <span class="ticker-avatar" :style="{ background: background }" role="img" :aria-label="ticker">
    <img v-if="logoUrl && !failed" class="ticker-logo" :src="logoUrl" :alt="ticker" @error="failed = true" />
    <span v-else class="ticker-text">{{ text }}</span>
  </span>
</template>

<script setup>
import { computed, ref } from 'vue';

const props = defineProps({
  ticker: { type: String, required: true },
  color: { type: String, default: 'rgba(255,255,255,0.16)' },
  logoUrl: { type: String, default: null },
});

const failed = ref(false);
const text = computed(() => (props.ticker || '').slice(0, 4).toUpperCase());
const background = computed(() => {
  if (props.logoUrl && !failed.value) {
    return 'linear-gradient(180deg, rgba(255,255,255,0.95), rgba(255,255,255,0.82))';
  }

  return `linear-gradient(135deg, ${props.color}, rgba(0,0,0,0.30))`;
});
</script>

<style scoped>
.ticker-avatar {
  width: 34px;
  height: 34px;
  min-width: 34px;
  min-height: 34px;
  border-radius: 12px;
  border: 1px solid rgba(255, 255, 255, 0.14);
  display: inline-grid;
  place-items: center;
  flex: 0 0 auto;
  box-shadow: 0 10px 24px rgba(0, 0, 0, 0.22);
}

.ticker-logo {
  width: 24px;
  height: 24px;
  object-fit: contain;
}

.ticker-text {
  font-size: 11px;
  font-weight: 800;
  letter-spacing: 0.2px;
  color: rgba(255, 255, 255, 0.92);
  line-height: 1;
}
</style>
