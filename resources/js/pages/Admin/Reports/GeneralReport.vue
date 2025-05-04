<template>
  <div>
    <!-- Genel İstatistikler -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Genel Gelir Dağılımı</h3>
        <div class="h-80">
          <MonthlyIncomeChart 
            :chart-data="monthlyIncome" 
            :chart-options="chartOptions"
          />
        </div>
      </div>
      
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Kategorilere Göre Rezervasyon</h3>
        <div class="h-80">
          <CategoryDistributionChart />
        </div>
      </div>
    </div>
    
    <!-- En Çok Satan Turlar -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
      <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">En Çok Satan Turlar</h3>
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
          <thead>
            <tr>
              <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tur</th>
              <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Rezervasyon</th>
              <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Gelir</th>
              <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Trend</th>
            </tr>
          </thead>
          <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
            <tr v-for="tour in topTours" :key="tour.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="h-10 w-10 flex-shrink-0">
                    <img v-if="tour.image" class="h-10 w-10 rounded-full object-cover" :src="tour.image" alt="" />
                    <div v-else class="h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center">
                      <svg class="h-6 w-6 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                      </svg>
                    </div>
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ tour.name }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ tour.destination }}</div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ tour.reservations }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ formatPrice(tour.income) }}</td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full" :class="getTrendClass(tour.trend)">
                  <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="getTrendIcon(tour.trend)" />
                  </svg>
                  {{ getTrendText(tour.trend) }}
                </span>
              </td>
            </tr>
            <tr v-if="!topTours || topTours.length === 0">
              <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">Veri bulunamadı</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    
    <!-- Rezervasyon İstatistikleri -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Rezervasyon Durumları</h3>
        <div class="h-60">
          <ReservationStatusChart :chart-data="statusChartData" />
        </div>
        <div class="mt-4 grid grid-cols-2 gap-2">
          <div v-for="(value, status) in reservationStatusCount" :key="status" class="text-center">
            <div class="text-lg font-bold text-gray-900 dark:text-white">{{ value }}</div>
            <div class="text-sm text-gray-500 dark:text-gray-400">{{ getStatusName(status) }}</div>
          </div>
        </div>
      </div>
      
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Populer Destinasyonlar</h3>
        <div class="space-y-4">
          <div v-for="(value, destination) in popularDestinations" :key="destination" class="flex items-center">
            <span class="text-sm font-medium text-gray-600 dark:text-gray-300 w-24 truncate">{{ destination }}</span>
            <div class="flex-grow h-2 bg-gray-200 dark:bg-gray-700 rounded-full mx-2">
              <div class="h-full rounded-full bg-green-500 dark:bg-green-400" :style="{ width: `${getPercentage(value, popularDestinations)}%` }"></div>
            </div>
            <span class="text-sm text-gray-600 dark:text-gray-300 min-w-[40px] text-right">{{ value }}</span>
          </div>
        </div>
      </div>
      
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Müşteri Memnuniyeti</h3>
        <div class="flex flex-col items-center justify-center h-40">
          <div class="relative w-40 h-40">
            <svg viewBox="0 0 100 100" class="w-full h-full">
              <circle cx="50" cy="50" r="40" fill="none" stroke="#f3f4f6" stroke-width="10" />
              <circle cx="50" cy="50" r="40" fill="none" :stroke="getSatisfactionColor(customerSatisfaction)" stroke-width="10" stroke-dasharray="251.2" :stroke-dashoffset="getCircleOffset(customerSatisfaction)" transform="rotate(-90 50 50)" />
            </svg>
            <div class="absolute inset-0 flex flex-col items-center justify-center">
              <span class="text-3xl font-bold text-gray-900 dark:text-white">{{ customerSatisfaction }}%</span>
              <span class="text-sm text-gray-500 dark:text-gray-400">Memnuniyet</span>
            </div>
          </div>
        </div>
        <div class="mt-4">
          <div class="flex justify-between text-sm">
            <span class="text-gray-500 dark:text-gray-400">Toplam Değerlendirme: {{ totalReviews }}</span>
            <span :class="getDeltaClass(satisfactionDelta)">
              {{ getDeltaPrefix(satisfactionDelta) }}{{ Math.abs(satisfactionDelta) }}%
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { defineComponent, ref, computed, onMounted } from 'vue';
import MonthlyIncomeChart from '@/components/Admin/Charts/MonthlyIncomeChart.vue';
import CategoryDistributionChart from '@/components/Admin/Charts/CategoryDistributionChart.vue';
import ReservationStatusChart from '@/components/Admin/Charts/ReservationStatusChart.vue';

export default defineComponent({
  name: 'GeneralReport',
  components: {
    MonthlyIncomeChart,
    CategoryDistributionChart,
    ReservationStatusChart
  },
  props: {
    monthlyIncome: {
      type: Object,
      required: true
    },
    topTours: {
      type: Array,
      required: true
    },
    chartOptions: {
      type: Object,
      required: true
    },
    formatPrice: {
      type: Function,
      required: true
    },
    getTrendText: {
      type: Function,
      required: true
    },
    getTrendClass: {
      type: Function,
      required: true
    },
    getTrendIcon: {
      type: Function,
      required: true
    }
  },
  setup() {
    // Rezervasyon durumları
    const reservationStatusCount = ref({
      'confirmed': 124,
      'pending': 36,
      'cancelled': 18,
      'completed': 210
    });
    
    // Popüler destinasyonlar
    const popularDestinations = ref({
      'İstanbul': 85,
      'Kapadokya': 64,
      'Antalya': 53,
      'İzmir': 41,
      'Muğla': 37
    });
    
    // Müşteri memnuniyeti
    const customerSatisfaction = ref(87);
    const totalReviews = ref(324);
    const satisfactionDelta = ref(2.7);
    
    // Rezervasyon durumları için grafik verisi
    const statusChartData = computed(() => {
      const colors = {
        'confirmed': '#60a5fa',
        'pending': '#fbbf24',
        'cancelled': '#ef4444',
        'completed': '#10b981'
      };
      
      const labels = [];
      const data = [];
      const backgroundColor = [];
      
      for (const [status, count] of Object.entries(reservationStatusCount.value)) {
        labels.push(getStatusName(status));
        data.push(count);
        backgroundColor.push(colors[status] || '#9ca3af');
      }
      
      return {
        labels,
        datasets: [{
          data,
          backgroundColor
        }]
      };
    });
    
    // Rezervasyon durumu isimleri
    const getStatusName = (status) => {
      const statuses = {
        'confirmed': 'Onaylandı',
        'pending': 'Bekliyor',
        'cancelled': 'İptal',
        'completed': 'Tamamlandı'
      };
      
      return statuses[status] || status;
    };
    
    // Yüzde hesaplama
    const getPercentage = (value, dataObj) => {
      const total = Object.values(dataObj).reduce((sum, val) => sum + val, 0);
      return total === 0 ? 0 : Math.round((value / total) * 100);
    };
    
    // Daire grafiği için offset hesaplama
    const getCircleOffset = (percent) => {
      const circumference = 2 * Math.PI * 40; // 2πr
      return circumference - (circumference * percent) / 100;
    };
    
    // Memnuniyet rengini belirleme
    const getSatisfactionColor = (percent) => {
      if (percent >= 80) return '#10b981'; // Yeşil
      if (percent >= 60) return '#60a5fa'; // Mavi
      if (percent >= 40) return '#fbbf24'; // Sarı
      return '#ef4444'; // Kırmızı
    };
    
    // Artış/Azalış değeri için renk sınıfı
    const getDeltaClass = (delta) => {
      if (!delta) return 'text-gray-500 dark:text-gray-400';
      return delta > 0 
        ? 'text-green-600 dark:text-green-400' 
        : 'text-red-600 dark:text-red-400';
    };
    
    // Artış/Azalış için prefix
    const getDeltaPrefix = (delta) => {
      if (!delta) return '';
      return delta > 0 ? '+' : '';
    };
    
    return {
      reservationStatusCount,
      popularDestinations,
      customerSatisfaction,
      totalReviews,
      satisfactionDelta,
      statusChartData,
      getStatusName,
      getPercentage,
      getCircleOffset,
      getSatisfactionColor,
      getDeltaClass,
      getDeltaPrefix
    };
  }
});
</script> 