<template>
  <div class="h-full">
    <canvas ref="chartCanvas"></canvas>
  </div>
</template>

<script>
import { defineComponent, ref, onMounted, watch, onBeforeUnmount } from 'vue';
import { 
  Chart, 
  CategoryScale, 
  LinearScale, 
  BarElement, 
  Title, 
  Tooltip, 
  Legend 
} from 'chart.js';

// Register required components individually
Chart.register(
  CategoryScale, 
  LinearScale, 
  BarElement, 
  Title, 
  Tooltip, 
  Legend
);

export default defineComponent({
  name: 'CategoryDistributionChart',
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
        labels: ['Şehir Turu', 'Kültür Turu', 'Doğa Turu', 'Deniz Turu', 'Kayak Turu'],
        datasets: [{
          label: 'Rezervasyon Sayısı',
          data: [65, 54, 35, 28, 18],
          backgroundColor: [
            'rgba(54, 162, 235, 0.7)',
            'rgba(75, 192, 192, 0.7)',
            'rgba(255, 159, 64, 0.7)',
            'rgba(153, 102, 255, 0.7)',
            'rgba(255, 99, 132, 0.7)'
          ],
          borderColor: [
            'rgba(54, 162, 235, 1)',
            'rgba(75, 192, 192, 1)',
            'rgba(255, 159, 64, 1)',
            'rgba(153, 102, 255, 1)',
            'rgba(255, 99, 132, 1)'
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
            display: false
          },
          tooltip: {
            callbacks: {
              label: function(context) {
                return `${context.label}: ${context.raw} rezervasyon`;
              }
            }
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              precision: 0
            }
          }
        }
      };

      // Merge default options with provided options
      const options = {...defaultOptions, ...props.options};

      // Create chart
      chartInstance = new Chart(ctx, {
        type: 'bar',
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