<template>
  <main class="auth">
    <section class="auth-card" role="region" aria-label="Criar conta">
      <header class="auth-header">
        <RouterLink class="auth-brand" to="/">
          <AppIcon :size="26" aria-hidden="true" />
          <span class="brand-name">InvestTrack</span>
        </RouterLink>
        <h1 class="auth-title">Criar conta</h1>
        <p class="auth-subtitle">Em poucos segundos voc&ecirc; j&aacute; come&ccedil;a a registrar suas opera&ccedil;&otilde;es.</p>
      </header>

      <form class="auth-form" @submit.prevent="onSubmit">
        <label class="field">
          <span class="label">Nome</span>
          <input
            v-model.trim="name"
            class="input"
            type="text"
            autocomplete="name"
            placeholder="Seu nome"
            required
          />
        </label>

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
            autocomplete="new-password"
            placeholder="M\u00ednimo 8 caracteres"
            required
            minlength="8"
          />
        </label>

        <label class="field">
          <span class="label">Confirmar senha</span>
          <input
            v-model="passwordConfirm"
            class="input"
            type="password"
            autocomplete="new-password"
            placeholder="Repita a senha"
            required
            minlength="8"
          />
        </label>

        <button class="btn primary btn-full" type="submit">Criar conta</button>

        <p class="auth-footnote">
          J&aacute; tem conta?
          <RouterLink class="link" to="/login">Entrar</RouterLink>
        </p>
      </form>
    </section>
  </main>
</template>

<script setup>
import { ref } from 'vue';
import { RouterLink } from 'vue-router';
import AppIcon from '../components/AppIcon.vue';

const name = ref('');
const email = ref('');
const password = ref('');
const passwordConfirm = ref('');

function onSubmit() {
  if (password.value !== passwordConfirm.value) {
    alert('As senhas n\u00e3o conferem.');
    return;
  }

  // Registration will be wired to the Laravel backend next.
  alert('Cadastro ainda n\u00e3o est\u00e1 implementado (s\u00f3 UI).');
}
</script>
