<template>
  <Bar
    :data="chartData"
    :options="options"
    class="chart"
  />
</template>

<script setup>
import { computed } from 'vue';
import { Bar } from 'vue-chartjs';

const props = defineProps({
  chartData: {
    type: Object,
    required: true
  },
  options: {
    type: Object,
    default: () => ({})
  }
});
</script>

<style scoped>
.chart {
  width: 100%;
  height: 100%;
}
</style> 