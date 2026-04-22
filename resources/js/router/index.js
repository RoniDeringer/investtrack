import { createRouter, createWebHistory } from 'vue-router';

import Welcome from '../pages/Welcome.vue';
import Login from '../pages/Login.vue';
import Register from '../pages/Register.vue';
import Portfolio from '../pages/Portfolio.vue';
import Transactions from '../pages/Transactions.vue';
import Dividends from '../pages/Dividends.vue';
import { useAuthStore } from '../stores/auth';

const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/', name: 'welcome', component: Welcome },
    { path: '/login', name: 'login', component: Login },
    { path: '/register', name: 'register', component: Register },
    { path: '/carteira', name: 'portfolio', component: Portfolio, meta: { requiresAuth: true } },
    { path: '/lancamentos', name: 'transactions', component: Transactions, meta: { requiresAuth: true } },
    { path: '/proventos', name: 'dividends', component: Dividends, meta: { requiresAuth: true } },
  ],
});

router.beforeEach(async (to) => {
  if (!to.meta?.requiresAuth) return true;

  const auth = useAuthStore();
  if (!auth.token) return { name: 'login' };

  if (!auth.user) {
    try {
      await auth.fetchMe();
    } catch {
      auth.setToken(null);
      return { name: 'login' };
    }
  }

  return true;
});

export default router;
