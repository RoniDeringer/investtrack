<template>
  <main class="auth">
    <section class="auth-card" role="region" aria-label="Login">
      <header class="auth-header">
        <RouterLink class="auth-brand" to="/">
          <AppIcon :size="26" aria-hidden="true" />
          <span class="brand-name">InvestTrack</span>
        </RouterLink>
        <h1 class="auth-title">Entrar</h1>
        <p class="auth-subtitle">Acesse sua carteira e continue de onde parou.</p>
      </header>

      <form class="auth-form" @submit.prevent="onSubmit">
        <label class="field">
          <span class="label">E-mail</span>
          <input
            v-model.trim="email"
            class="input"
            type="email"
            autocomplete="email"
            placeholder="voce@exemplo.com"
            required
          />
        </label>

        <label class="field">
          <span class="label">Senha</span>
          <input
            v-model="password"
            class="input"
            type="password"
            autocomplete="current-password"
            placeholder="••••••••"
            required
          />
        </label>

        <button class="btn primary btn-full" type="submit">Entrar</button>

        <p class="auth-footnote">
          N&atilde;o tem conta?
          <RouterLink class="link" to="/register">Criar conta</RouterLink>
        </p>
      </form>
    </section>
  </main>
</template>

<script setup>
import { ref } from 'vue';
import { RouterLink } from 'vue-router';
import AppIcon from '../components/AppIcon.vue';

const email = ref('');
const password = ref('');

function onSubmit() {
  // Auth will be wired to the Laravel backend next.
  alert('Login ainda n\u00e3o est\u00e1 implementado (s\u00f3 UI).');
}
</script>
