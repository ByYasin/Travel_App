<template>
  <Line :data="chartData" :options="mergedOptions" />
</template>

<script>
import { defineComponent, computed } from 'vue';
import {
  Chart,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend
} from 'chart.js';
import { Line } from 'vue-chartjs';
import { merge } from 'lodash';

// Tüm gerekli bileşenleri kaydedelim
Chart.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend
);

export default defineComponent({
  name: 'MonthlyIncomeChart',
  components: {
    Line
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
                label += new Intl.NumberFormat('tr-TR', { 
                  style: 'currency', 
                  currency: 'TRY' 
                }).format(context.parsed.y);
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
            callback: function(value) {
              return new Intl.NumberFormat('tr-TR', { 
                style: 'currency', 
                currency: 'TRY' 
              }).format(value);
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

<style scoped>
.chart {
  width: 100%;
  height: 100%;
}
</style> 