<template>
  <main class="welcome">
    <div class="bg">
      <div class="grid" aria-hidden="true"></div>
      <div class="glow glow-a" aria-hidden="true"></div>
      <div class="glow glow-b" aria-hidden="true"></div>
    </div>

    <section class="hero">
      <header class="brand">
        <div class="mark" aria-hidden="true">
          <AppIcon :size="42" />
        </div>
        <div class="brand-text">
          <div class="name">InvestTrack</div>
          <div class="tag">Seu portf&oacute;lio, com clareza.</div>
        </div>
      </header>

      <h1 class="title">
        Controle de opera&ccedil;&otilde;es, dividendos e performance
        <span class="title-accent">em um s&oacute; lugar</span>.
      </h1>

      <p class="subtitle">
        Comece registrando sua primeira carteira e acompanhe pre&ccedil;o m&eacute;dio,
        lucro/preju&iacute;zo e proventos com uma vis&atilde;o limpa e objetiva.
      </p>

      <div class="cta-row">
        <RouterLink class="btn primary" to="/login">Entrar</RouterLink>
        <RouterLink class="btn ghost" to="/register">Criar conta</RouterLink>
      </div>

      <div class="cards">
        <article class="card">
          <div class="card-kicker">Opera&ccedil;&otilde;es</div>
          <div class="card-title">Compras e vendas sem dor</div>
          <div class="card-body">Valida&ccedil;&otilde;es, hist&oacute;rico e c&aacute;lculos autom&aacute;ticos.</div>
        </article>

        <article class="card">
          <div class="card-kicker">Dividendos</div>
          <div class="card-title">Proventos no tempo certo</div>
          <div class="card-body">Registro por ativo e valor recebido por carteira.</div>
        </article>

        <article class="card">
          <div class="card-kicker">Performance</div>
          <div class="card-title">M&eacute;tricas que importam</div>
          <div class="card-body">Valor investido, valor atual e rentabilidade.</div>
        </article>
      </div>

      <footer class="foot">
        <div class="foot-left">
          <span class="pill">Laravel 11</span>
          <span class="pill">Vue 3 + Pinia</span>
          <span class="pill">PostgreSQL + Redis</span>
        </div>
        <div class="foot-right">
          <span class="muted">Ambiente Docker pronto para desenvolvimento.</span>
        </div>
      </footer>
    </section>
  </main>
</template>

<script setup>
import { RouterLink } from 'vue-router';
import AppIcon from '../components/AppIcon.vue';
</script>
