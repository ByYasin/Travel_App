<template>
  <Bar :data="chartData" :options="mergedOptions" />
</template>

<script>
import { defineComponent, computed } from 'vue';
import {
  Chart,
  CategoryScale,
  LinearScale,
  BarElement,
  Title,
  Tooltip,
  Legend
} from 'chart.js';
import { Bar } from 'vue-chartjs';
import { merge } from 'lodash';

// Tüm gerekli bileşenleri kaydedelim
Chart.register(
  CategoryScale,
  LinearScale,
  BarElement,
  Title,
  Tooltip,
  Legend
);

export default defineComponent({
  name: 'ParticipantDistributionChart',
  components: {
    Bar
  },
  props: {
    chartData: {
      type: Object,
      required: true
    },
    options: {
      type: Object,
      default: () => ({})
    }
  },
  setup(props) {
    const defaultOptions = {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'top',
        },
        tooltip: {
          callbacks: {
            label: function(context) {
              let label = context.dataset.label || '';
              if (label) {
                label += ': ';
              }
              if (context.parsed.y !== null) {
                label += context.parsed.y + ' kişi';
              }
              return label;
            }
          }
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            precision: 0,
            callback: function(value) {
              return value + ' kişi';
            }
          }
        }
      }
    };

    const mergedOptions = computed(() => merge({}, defaultOptions, props.options));

    return {
      mergedOptions
    };
  }
});
</script> 