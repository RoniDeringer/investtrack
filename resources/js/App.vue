<template>
  <div class="app-shell">
    <RouterView />
  </div>
</template>

<script setup>
import { RouterView } from 'vue-router';
</script>

<style scoped>
.app-shell {
  min-height: 100vh;
}
</style>

