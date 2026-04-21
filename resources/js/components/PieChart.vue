<template>
  <div class="pie">
    <svg :width="size" :height="size" viewBox="0 0 200 200" role="img" aria-label="Alocação da carteira">
      <g v-if="hasData">
        <path v-for="(slice, idx) in paths" :key="idx" :d="slice.d" :fill="slice.color" opacity="0.92" />
        <circle cx="100" cy="100" :r="innerRadius" fill="rgba(0,0,0,0.28)" />
      </g>
      <g v-else>
        <circle cx="100" cy="100" r="74" fill="rgba(255,255,255,0.06)" />
        <circle cx="100" cy="100" r="48" fill="rgba(0,0,0,0.28)" />
        <text x="100" y="106" text-anchor="middle" fill="rgba(255,255,255,0.65)" font-size="12">Sem dados</text>
      </g>
    </svg>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  slices: { type: Array, default: () => [] }, // { label, value, color }
  size: { type: Number, default: 220 },
});

const outerRadius = 74;
const innerRadius = 48;

const total = computed(() => props.slices.reduce((sum, s) => sum + (Number(s.value) || 0), 0));
const hasData = computed(() => total.value > 0);

function polarToCartesian(cx, cy, r, angleDeg) {
  const angle = ((angleDeg - 90) * Math.PI) / 180.0;
  return { x: cx + r * Math.cos(angle), y: cy + r * Math.sin(angle) };
}

function donutSlicePath(startAngle, endAngle) {
  const largeArc = endAngle - startAngle > 180 ? 1 : 0;
  const outerStart = polarToCartesian(100, 100, outerRadius, endAngle);
  const outerEnd = polarToCartesian(100, 100, outerRadius, startAngle);
  const innerStart = polarToCartesian(100, 100, innerRadius, startAngle);
  const innerEnd = polarToCartesian(100, 100, innerRadius, endAngle);

  return [
    `M ${outerStart.x} ${outerStart.y}`,
    `A ${outerRadius} ${outerRadius} 0 ${largeArc} 0 ${outerEnd.x} ${outerEnd.y}`,
    `L ${innerStart.x} ${innerStart.y}`,
    `A ${innerRadius} ${innerRadius} 0 ${largeArc} 1 ${innerEnd.x} ${innerEnd.y}`,
    'Z',
  ].join(' ');
}

const paths = computed(() => {
  if (!hasData.value) return [];

  let angle = 0;
  return props.slices
    .filter((s) => (Number(s.value) || 0) > 0)
    .map((s) => {
      const fraction = (Number(s.value) || 0) / total.value;
      const start = angle;
      const end = angle + fraction * 360;
      angle = end;

      return {
        label: s.label,
        color: s.color || 'rgba(255,255,255,0.35)',
        d: donutSlicePath(start, end),
      };
    });
});
</script>

<style scoped>
.pie {
  display: grid;
  place-items: center;
}
</style>

