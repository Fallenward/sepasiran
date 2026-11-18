<script setup>
defineProps({
  title: { type: String, required: true },
  breadcrumbs: { type: Array, default: () => [] },
});
</script>

<template>
  <section class="sp-page-header mb-4">
    <div class="wrap sp-page-header__inner">
      <h1 class="sp-page-header__title">{{ title }}</h1>
      <nav v-if="breadcrumbs.length" class="sp-breadcrumb sp-page-header__crumbs" aria-label="breadcrumb">
        <template v-for="(crumb, i) in breadcrumbs" :key="i">
          <span v-if="i > 0" class="sp-breadcrumb__sep" aria-hidden="true">/</span>
          <a v-if="crumb.route" :href="crumb.route">{{ crumb.label }}</a>
          <span v-else>{{ crumb.label }}</span>
        </template>
      </nav>
    </div>
  </section>
</template>