import { defineStore } from 'pinia';
import axios from 'axios';

function getStoredExpiryMs() {
  const raw = localStorage.getItem('auth_expires_at');
  if (!raw) return null;
  const ms = Date.parse(raw);
  return Number.isFinite(ms) ? ms : null;
}

function isExpired(expiryMs) {
  if (!expiryMs) return false;
  return Date.now() >= expiryMs;
}

export const useAuthStore = defineStore('auth', {
  state: () => ({
    token: localStorage.getItem('auth_token') || null,
    expiresAt: localStorage.getItem('auth_expires_at') || null,
    user: null,
  }),
  actions: {
    setToken(token, expiresAt = null) {
      this.token = token;
      this.expiresAt = expiresAt;
      if (token) {
        localStorage.setItem('auth_token', token);
        if (expiresAt) localStorage.setItem('auth_expires_at', expiresAt);
        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
      } else {
        localStorage.removeItem('auth_token');
        localStorage.removeItem('auth_expires_at');
        delete axios.defaults.headers.common['Authorization'];
      }
    },
    async login(email, password) {
      const res = await axios.post('/api/login', { email, password });
      this.setToken(res.data?.token || null, res.data?.expires_at || null);
      this.user = res.data?.user || null;
      return res.data;
    },
    async fetchMe() {
      if (!this.token) return null;
      const expiryMs = getStoredExpiryMs();
      if (isExpired(expiryMs)) {
        this.setToken(null);
        this.user = null;
        return null;
      }
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
