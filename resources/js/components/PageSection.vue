<template>
  <section :class="[
    'py-16 md:py-24', 
    bgClass,
    bordered ? 'border-t border-gray-200 dark:border-gray-800' : ''
  ]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Başlık bölümü (varsa) -->
      <div 
        v-if="title || subtitle" 
        class="text-center mb-16"
      >
        <span 
          v-if="tag" 
          class="inline-block px-4 py-2 rounded-full bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-300 text-sm mb-4"
        >
          {{ tag }}
        </span>
        <h2 
          v-if="title" 
          class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4"
          :class="{'text-center': centered, 'text-left': !centered}"
        >
          <span v-if="highlightedTitle" class="relative">
            {{ title.replace(highlightedTitle, '') }}
            <span class="text-primary-500">{{ highlightedTitle }}</span>
            <span 
              v-if="showHighlightUnderline" 
              class="absolute bottom-0 left-0 w-full h-3 bg-primary-100/70 dark:bg-primary-900/30 -z-10"
            ></span>
          </span>
          <template v-else>{{ title }}</template>
        </h2>
        <p 
          v-if="subtitle" 
          class="text-lg md:text-xl text-gray-600 dark:text-gray-400 max-w-3xl"
          :class="centered ? 'mx-auto' : ''"
        >
          {{ subtitle }}
        </p>
      </div>

      <!-- İçerik -->
      <div>
        <slot></slot>
      </div>
    </div>
  </section>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  title: {
    type: String,
    default: ''
  },
  subtitle: {
    type: String,
    default: ''
  },
  tag: {
    type: String,
    default: ''
  },
  variant: {
    type: String,
    default: 'default',
    validator: value => ['default', 'light', 'dark', 'primary', 'accent'].includes(value)
  },
  centered: {
    type: Boolean,
    default: true
  },
  bordered: {
    type: Boolean,
    default: false
  },
  highlightedTitle: {
    type: String,
    default: ''
  },
  showHighlightUnderline: {
    type: Boolean,
    default: true
  }
});

// Arkaplan rengini belirleme
const bgClass = computed(() => {
  switch (props.variant) {
    case 'light':
      return 'bg-gray-50 dark:bg-gray-900/50';
    case 'dark':
      return 'bg-gray-900 dark:bg-gray-950 text-white';
    case 'primary':
      return 'bg-primary-50 dark:bg-primary-900/20';
    case 'accent':
      return 'bg-gradient-to-br from-primary-50 to-blue-50 dark:from-gray-900 dark:to-primary-900/20';
    default:
      return 'bg-white dark:bg-gray-900';
  }
});
</script> 