import { createRouter, createWebHistory } from 'vue-router';

import Welcome from '../pages/Welcome.vue';

const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/', name: 'welcome', component: Welcome },
  ],
});

export default router;

