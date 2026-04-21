import { defineStore } from 'pinia';
import axios from 'axios';

export const useAuthStore = defineStore('auth', {
  state: () => ({
    token: localStorage.getItem('auth_token') || null,
    user: null,
  }),
  actions: {
    setToken(token) {
      this.token = token;
      if (token) {
        localStorage.setItem('auth_token', token);
        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
      } else {
        localStorage.removeItem('auth_token');
        delete axios.defaults.headers.common['Authorization'];
      }
    },
    async login(email, password) {
      const res = await axios.post('/api/login', { email, password });
      this.setToken(res.data?.token || null);
      this.user = res.data?.user || null;
      return res.data;
    },
    async fetchMe() {
      if (!this.token) return null;
      const res = await axios.get('/api/me');
      this.user = res.data?.user || null;
      return this.user;
    },
    async logout() {
      try {
        if (this.token) await axios.post('/api/logout');
      } finally {
        this.setToken(null);
        this.user = null;
      }
    },
  },
});

