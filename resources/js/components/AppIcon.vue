<template>
  <svg
    class="app-icon"
    xmlns="http://www.w3.org/2000/svg"
    viewBox="0 0 256 256"
    role="img"
    aria-label="InvestTrack"
    :style="{ width: sizePx, height: sizePx }"
  >
    <defs>
      <linearGradient :id="strokeId" x1="48" y1="48" x2="208" y2="208" gradientUnits="userSpaceOnUse">
        <stop offset="0" stop-color="#4de1c1" />
        <stop offset="1" stop-color="#6aa7ff" />
      </linearGradient>
      <linearGradient :id="fillId" x1="56" y1="44" x2="200" y2="212" gradientUnits="userSpaceOnUse">
        <stop offset="0" stop-color="#0f1629" />
        <stop offset="1" stop-color="#0b0f1a" />
      </linearGradient>
    </defs>

    <path
      d="M88 32h80l56 56v80l-56 56H88l-56-56V88l56-56Z"
      :fill="`url(#${fillId})`"
      :stroke="`url(#${strokeId})`"
      stroke-width="6"
      stroke-linejoin="round"
    />

    <path
      d="M76 166 C 108 154, 124 142, 140 124 C 154 108, 170 92, 190 80"
      fill="none"
      stroke="rgba(255,255,255,0.94)"
      stroke-width="14"
      stroke-linecap="round"
      stroke-linejoin="round"
    />
    <path
      d="M168 82 L 190 80 L 182 102"
      fill="none"
      stroke="rgba(255,255,255,0.94)"
      stroke-width="14"
      stroke-linecap="round"
      stroke-linejoin="round"
    />
  </svg>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  size: { type: Number, default: 42 },
});

const sizePx = computed(() => `${props.size}px`);

// Avoid ID collisions when multiple icons render on the same page.
const uid = Math.random().toString(16).slice(2);
const strokeId = `it-stroke-${uid}`;
const fillId = `it-fill-${uid}`;
</script>

<style scoped>
.app-icon {
  display: block;
}
</style>
