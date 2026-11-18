<script setup>
defineProps({
  brand: { type: String, default: 'سپاسیران' },
  title: { type: String, default: 'همه‌چیز برای همراه چهارپای شما' },
  lede: { type: String, default: 'بازار سگ، مربی، کلینیک و فروشگاه — مینیمال، مطمئن، فارسی.' },
  dogsUrl: { type: String, required: true },
  servicesUrl: { type: String, required: true },
  bookingUrl: { type: String, default: '' },
  heroImage: { type: String, default: '' },
});

function onParallax(e) {
  const media = e.currentTarget.querySelector('[data-parallax]');
  if (!media || window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
  const x = (e.clientX / window.innerWidth - 0.5) * 8;
  const y = (e.clientY / window.innerHeight - 0.5) * 8;
  media.style.transform = `translate3d(${-x}px,${-y}px,0) scale(1.03)`;
}
</script>

<template>
  <section class="hero" id="hero" @pointermove="onParallax">
    <div
      class="hero__media"
      data-parallax
      :style="heroImage
        ? {
            backgroundImage: `linear-gradient(105deg, rgba(7, 10, 8, 0.92) 0%, rgba(7, 10, 8, 0.55) 45%, rgba(7, 10, 8, 0.25) 100%), linear-gradient(to top, #070a08 0%, transparent 45%), url('${heroImage}')`,
            backgroundSize: 'cover',
            backgroundPosition: 'center',
          }
        : undefined"
    />
    <div class="wrap hero__content">
      <h1 class="hero__brand">{{ brand }} <span class="accent">.</span></h1>
      <p class="hero__title">{{ title }}</p>
      <p class="hero__lede">{{ lede }}</p>
      <div class="hero__cta">
        <a class="btn btn-primary" :href="dogsUrl">مشاهده سگ‌ها</a>
        <a class="btn btn-ghost" :href="bookingUrl || servicesUrl">رزرو خدمات</a>
      </div>
    </div>
  </section>
</template>