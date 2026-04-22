<template>
  <div class="pie">
    <svg :width="size" :height="size" viewBox="0 0 200 200" role="img" aria-label="Alocação da carteira">
      <g v-if="hasData">
        <path
          v-for="(slice, idx) in paths"
          :key="idx"
          class="pie-slice"
          :class="{ active: hoveredIndex === idx }"
          :d="slice.d"
          :fill="slice.color"
          opacity="0.92"
          @mouseenter="hoveredIndex = idx"
          @mouseleave="hoveredIndex = null"
        />
        <circle cx="100" cy="100" :r="innerRadius" fill="rgba(0, 0, 0, 0.28)" />
        <text x="100" y="92" text-anchor="middle" fill="rgba(255,255,255,0.68)" font-size="10">
          {{ centerTitle }}
        </text>
        <text x="100" y="108" text-anchor="middle" fill="rgba(255,255,255,0.96)" font-size="14" font-weight="700">
          {{ centerValue }}
        </text>
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
import { computed, ref } from 'vue';

const props = defineProps({
  slices: { type: Array, default: () => [] },
  size: { type: Number, default: 220 },
});

const hoveredIndex = ref(null);

const outerRadius = 74;
const innerRadius = 48;

const total = computed(() => props.slices.reduce((sum, slice) => sum + (Number(slice.value) || 0), 0));
const hasData = computed(() => total.value > 0);

function polarToCartesian(cx, cy, radius, angleDeg) {
  const angle = ((angleDeg - 90) * Math.PI) / 180;
  return { x: cx + radius * Math.cos(angle), y: cy + radius * Math.sin(angle) };
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
    .filter((slice) => (Number(slice.value) || 0) > 0)
    .map((slice) => {
      const fraction = (Number(slice.value) || 0) / total.value;
      const start = angle;
      const end = angle + fraction * 360;
      angle = end;

      return {
        label: slice.label,
        percent: fraction * 100,
        color: slice.color || 'rgba(255,255,255,0.35)',
        d: donutSlicePath(start, end),
      };
    });
});

const activeSlice = computed(() => {
  if (!paths.value.length) return null;
  if (hoveredIndex.value === null) return paths.value[0];
  return paths.value[hoveredIndex.value] ?? paths.value[0];
});

const centerTitle = computed(() => activeSlice.value?.label || 'Carteira');
const centerValue = computed(() => {
  if (!activeSlice.value) return '';
  return `${activeSlice.value.percent.toFixed(2)}%`;
});
</script>

<style scoped>
.pie {
  display: grid;
  place-items: center;
}

.pie-slice {
  transition: transform 140ms ease, opacity 140ms ease;
  transform-origin: 100px 100px;
}

.pie-slice.active {
  opacity: 1;
  transform: scale(1.03);
}
</style>

