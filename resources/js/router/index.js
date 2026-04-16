import { createRouter, createWebHistory } from 'vue-router';

import Welcome from '../pages/Welcome.vue';
import Login from '../pages/Login.vue';
import Register from '../pages/Register.vue';

const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/', name: 'welcome', component: Welcome },
    { path: '/login', name: 'login', component: Login },
    { path: '/register', name: 'register', component: Register },
  ],
});

export default router;
