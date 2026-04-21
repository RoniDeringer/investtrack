import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const token = localStorage.getItem('auth_token');
const expiresAtRaw = localStorage.getItem('auth_expires_at');
const expiresAtMs = expiresAtRaw ? Date.parse(expiresAtRaw) : null;

if (token && (!expiresAtMs || Date.now() < expiresAtMs)) {
  window.axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
} else if (token && expiresAtMs && Date.now() >= expiresAtMs) {
  localStorage.removeItem('auth_token');
  localStorage.removeItem('auth_expires_at');
}

window.axios.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error?.response?.status === 401) {
      localStorage.removeItem('auth_token');
      localStorage.removeItem('auth_expires_at');
      delete window.axios.defaults.headers.common['Authorization'];

      if (window.location.pathname !== '/login') {
        window.location.href = '/login';
      }
    }

    return Promise.reject(error);
  },
);
