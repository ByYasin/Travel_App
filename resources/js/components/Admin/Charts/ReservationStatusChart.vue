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
  name: 'ReservationStatusChart',
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
        labels: ['Onaylandı', 'Bekliyor', 'İptal', 'Tamamlandı'],
        datasets: [{
          data: [124, 36, 18, 210],
          backgroundColor: [
            'rgba(96, 165, 250, 0.7)',   // blue-400
            'rgba(251, 191, 36, 0.7)',   // amber-400
            'rgba(239, 68, 68, 0.7)',    // red-500
            'rgba(16, 185, 129, 0.7)'    // emerald-500
          ],
          borderColor: [
            'rgba(96, 165, 250, 1)',
            'rgba(251, 191, 36, 1)',
            'rgba(239, 68, 68, 1)',
            'rgba(16, 185, 129, 1)'
          ],
          borderWidth: 1
        }]
      };

      // Use props.chartData if provided, otherwise use default data
      const data = props.chartData.labels && props.chartData.labels.length > 0 
        ? props.chartData 
        : defaultData;

      // Default options
      const defaultOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'right',
            labels: {
              boxWidth: 12,
              padding: 15
            }
          },
          tooltip: {
            callbacks: {
              label: function(context) {
                const label = context.label || '';
                const value = context.raw || 0;
                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                return `${label}: ${value} (${percentage}%)`;
              }
            }
          }
        }
      };

      // Merge default options with provided options
      const options = {...defaultOptions, ...props.options};

      // Create chart
      chartInstance = new Chart(ctx, {
        type: 'pie',
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