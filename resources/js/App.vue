<template>
  <div class="app-shell">
    <div class="app-main">
      <RouterView />
    </div>
    <AppFooter />
  </div>
</template>

<script setup>
import { RouterView } from 'vue-router';
import AppFooter from './components/AppFooter.vue';
</script>

<style scoped>
.app-shell {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

.app-main {
  flex: 1;
}
</style>
