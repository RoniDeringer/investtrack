<template>
  <footer class="app-footer">
    <div class="footer-inner">
      <div class="footer-left">
        <span class="footer-muted">Desenvolvido por:</span>
        <span class="footer-strong">Roni Deringer</span>
        <span class="footer-dot" aria-hidden="true">•</span>
        <span class="footer-muted">{{ year }}</span>
      </div>

      <div class="footer-right">
        <a
          v-if="links.github"
          class="social"
          :href="links.github"
          target="_blank"
          rel="noreferrer"
          aria-label="GitHub"
          title="GitHub"
        >
          <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true">
            <path
              fill="currentColor"
              d="M12 .5C5.73.5.75 5.68.75 12.1c0 5.17 3.3 9.56 7.88 11.1.58.11.8-.26.8-.58v-2.14c-3.2.72-3.88-1.6-3.88-1.6-.52-1.36-1.27-1.72-1.27-1.72-1.04-.73.08-.72.08-.72 1.15.08 1.75 1.23 1.75 1.23 1.02 1.78 2.67 1.26 3.32.96.1-.76.4-1.26.72-1.55-2.55-.3-5.24-1.33-5.24-5.9 0-1.3.45-2.36 1.18-3.19-.12-.3-.51-1.5.11-3.12 0 0 .97-.32 3.18 1.22a10.7 10.7 0 0 1 5.8 0c2.2-1.54 3.17-1.22 3.17-1.22.62 1.62.23 2.82.11 3.12.74.83 1.18 1.9 1.18 3.19 0 4.59-2.7 5.6-5.27 5.89.41.36.78 1.08.78 2.18v3.23c0 .33.21.7.81.58a11.55 11.55 0 0 0 7.86-11.1C23.25 5.68 18.27.5 12 .5Z"
            />
          </svg>
        </a>

        <a
          v-if="links.linkedin"
          class="social"
          :href="links.linkedin"
          target="_blank"
          rel="noreferrer"
          aria-label="LinkedIn"
          title="LinkedIn"
        >
          <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true">
            <path
              fill="currentColor"
              d="M20.45 20.45h-3.56v-5.57c0-1.33-.02-3.05-1.86-3.05-1.86 0-2.14 1.45-2.14 2.95v5.67H9.33V9h3.41v1.56h.05c.47-.9 1.62-1.86 3.34-1.86 3.57 0 4.23 2.35 4.23 5.41v6.34ZM5.34 7.43a2.07 2.07 0 1 1 0-4.14 2.07 2.07 0 0 1 0 4.14ZM7.12 20.45H3.56V9h3.56v11.45Z"
            />
          </svg>
        </a>
      </div>
    </div>
  </footer>
</template>

<script setup>
import { computed } from 'vue';
import { getSocialLinks } from '../config/social';

const year = computed(() => new Date().getFullYear());
const links = getSocialLinks();
</script>

