<template>
  <div class="h-full">
    <Bar
      :data="chartData"
      :options="chartOptions"
    />
  </div>
</template>

<script>
import { defineComponent, ref, computed, onMounted } from 'vue';
import { 
  Chart as ChartJS, 
  CategoryScale,
  LinearScale,
  BarElement,
  Title,
  Tooltip,
  Legend 
} from 'chart.js';
import { Bar } from 'vue-chartjs';

// Chart.js bileşenlerini kaydet
ChartJS.register(
  CategoryScale,
  LinearScale,
  BarElement,
  Title,
  Tooltip,
  Legend
);

export default defineComponent({
  name: 'CategorySalesChart',
  components: {
    Bar
  },
  setup() {
    // Demo veriler - gerçek veriler props olarak geçilebilir
    const chartData = ref({
      labels: ['Şehir Turu', 'Kültür Turu', 'Doğa Turu', 'Deniz Turu', 'Kayak Turu'],
      datasets: [
        {
          label: 'Kategori Satışları',
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
          borderWidth: 1,
          data: [65000, 54000, 35000, 28000, 18000]
        }
      ]
    });

    const chartOptions = ref({
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: false
        },
        tooltip: {
          callbacks: {
            label: function(context) {
              return `${context.label}: ${new Intl.NumberFormat('tr-TR', { 
                style: 'currency', 
                currency: 'TRY' 
              }).format(context.raw)}`;
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
                currency: 'TRY',
                maximumFractionDigits: 0
              }).format(value);
            }
          }
        }
      }
    });

    return {
      chartData,
      chartOptions
    };
  }
});
</script> 