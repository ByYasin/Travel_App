<template>
  <div class="h-full">
    <canvas ref="chartCanvas"></canvas>
  </div>
</template>

<script>
import { defineComponent, ref, onMounted, watch, onBeforeUnmount } from 'vue';
import { Chart, registerables } from 'chart.js';

Chart.register(...registerables);

export default defineComponent({
  name: 'RevenueExpenseChart',
  props: {
    chartData: {
      type: Object,
      default: () => ({
        labels: [],
        datasets: []
      })
    },
    options: {
      type: Object,
      default: () => ({})
    }
  },
  setup(props) {
    const chartCanvas = ref(null);
    let chartInstance = null;

    const createChart = () => {
      const ctx = chartCanvas.value.getContext('2d');
      
      // Demo data if no data is provided
      const defaultData = {
        labels: ['Oca', 'Şub', 'Mar', 'Nis', 'May', 'Haz'],
        datasets: [
          {
            label: 'Gelir',
            data: [48500, 52300, 61400, 67800, 82950, 89700],
            borderColor: 'rgba(34, 197, 94, 1)',
            backgroundColor: 'rgba(34, 197, 94, 0.2)',
            borderWidth: 2,
            fill: true,
            tension: 0.4
          },
          {
            label: 'Gider',
            data: [28750, 30150, 35200, 38900, 45600, 48300],
            borderColor: 'rgba(239, 68, 68, 1)',
            backgroundColor: 'rgba(239, 68, 68, 0.2)',
            borderWidth: 2,
            fill: true,
            tension: 0.4
          }
        ]
      };

      // Use props.chartData if provided, otherwise use default data
      const data = props.chartData.labels && props.chartData.labels.length > 0 
        ? props.chartData 
        : defaultData;

      // Default options
      const defaultOptions = {
        responsive: true,
        maintainAspectRatio: false,
        interaction: {
          mode: 'index',
          intersect: false,
        },
        plugins: {
          legend: {
            position: 'top',
            labels: {
              boxWidth: 12,
              usePointStyle: true
            }
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
          x: {
            grid: {
              display: false
            }
          },
          y: {
            beginAtZero: true,
            ticks: {
              callback: function(value) {
                return new Intl.NumberFormat('tr-TR', {
                  style: 'currency',
                  currency: 'TRY',
                  maximumFractionDigits: 0
                }).format(value);
              }
            }
          }
        }
      };

      // Merge default options with provided options
      const options = {...defaultOptions, ...props.options};

      // Create chart
      chartInstance = new Chart(ctx, {
        type: 'line',
        data,
        options
      });
    };

    const updateChart = () => {
      if (chartInstance) {
        // Update chart data if chartData has been provided and is valid
        if (props.chartData.labels && props.chartData.labels.length > 0) {
          chartInstance.data.labels = props.chartData.labels;
          chartInstance.data.datasets = props.chartData.datasets;
          chartInstance.update();
        }
      }
    };

    onMounted(() => {
      if (chartCanvas.value) {
        createChart();
      }
    });

    watch(() => props.chartData, () => {
      updateChart();
    }, { deep: true });

    onBeforeUnmount(() => {
      if (chartInstance) {
        chartInstance.destroy();
      }
    });

    return {
      chartCanvas
    };
  }
});
</script> 