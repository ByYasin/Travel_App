<template>
  <transition 
    name="theme-transition"
    mode="out-in"
    @before-enter="beforeEnter"
    @enter="enter"
    @leave="leave"
  >
    <slot></slot>
  </transition>
</template>

<script setup>
const beforeEnter = (el) => {
  el.style.opacity = 0;
  el.style.transform = 'scale(0.95)';
};

const enter = (el, done) => {
  const duration = 300;
  
  setTimeout(() => {
    el.style.transition = `opacity ${duration}ms ease, transform ${duration}ms ease`;
    el.style.opacity = 1;
    el.style.transform = 'scale(1)';
  }, 10);

  setTimeout(done, duration);
};

const leave = (el, done) => {
  const duration = 300;
  
  el.style.transition = `opacity ${duration}ms ease, transform ${duration}ms ease`;
  el.style.opacity = 0;
  el.style.transform = 'scale(0.95)';
  
  setTimeout(done, duration);
};
</script>

<style scoped>
.theme-transition-enter-active,
.theme-transition-leave-active {
  transition: opacity 0.3s ease, transform 0.3s ease;
}

.theme-transition-enter-from,
.theme-transition-leave-to {
  opacity: 0;
  transform: scale(0.95);
}
</style> 