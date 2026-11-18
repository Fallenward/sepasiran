import { createApp, h } from 'vue';
import SpPageHeader from './components/SpPageHeader.vue';
import SpHomeHero from './components/SpHomeHero.vue';

function parseProps(el) {
  const raw = el.getAttribute('data-props');
  if (!raw) return {};
  try {
    return JSON.parse(raw);
  } catch {
    return {};
  }
}

function mountIslands() {
  document.querySelectorAll('[data-vue="page-header"]').forEach((el) => {
    const props = parseProps(el);
    createApp({ render: () => h(SpPageHeader, props) }).mount(el);
  });

  document.querySelectorAll('[data-vue="home-hero"]').forEach((el) => {
    const props = parseProps(el);
    createApp({ render: () => h(SpHomeHero, props) }).mount(el);
  });
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', mountIslands);
} else {
  mountIslands();
}

export { SpPageHeader, SpHomeHero };