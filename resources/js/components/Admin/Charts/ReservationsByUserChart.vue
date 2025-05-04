<template>
  <div>
    <Bar v-if="loaded" :data="chartData" :options="options" />
  </div>
</template>

<script>
import { defineComponent, computed, ref, watch } from 'vue';
import { Bar } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js';

// Chart.js bileşenlerini kaydet
ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend);

export default defineComponent({
  name: 'ReservationsByUserChart',
  components: { Bar },
  props: {
    chartData: {
      type: Object,
      required: true
    },
    options: {
      type: Object,
      default: () => ({
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'top',
          },
          title: {
            display: true,
            text: 'Kullanıcı Demografik Dağılımı'
          }
        }
      })
    }
  },
  setup(props) {
    const loaded = ref(false);
    
    // Bileşen yüklendikten sonra grafik gösterilebilir
    watch(() => props.chartData, () => {
      loaded.value = true;
    }, { immediate: true });
    
    return { loaded };
  }
});
</script> 