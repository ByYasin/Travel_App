<template>
  <div>
    <!-- Satış Grafikleri -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Aylık Satış</h3>
        <div class="h-80">
          <MonthlyIncomeChart 
            :chart-data="monthlyIncome" 
            :chart-options="chartOptions"
          />
        </div>
      </div>
      
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Kategorilere Göre Satış</h3>
        <div class="h-80">
          <CategorySalesChart />
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
              <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Toplam Satış</th>
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
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ formatPrice(tour.total_sales) }}</td>
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
    
    <!-- İleri Analiz Kartları -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Günlere Göre Satış</h3>
        <div class="space-y-4">
          <div v-for="(value, day) in dailySales" :key="day" class="flex items-center">
            <span class="text-sm font-medium text-gray-600 dark:text-gray-300 w-24">{{ getDayName(day) }}</span>
            <div class="flex-grow h-2 bg-gray-200 dark:bg-gray-700 rounded-full mx-2">
              <div class="h-full rounded-full bg-blue-500 dark:bg-blue-400" :style="{ width: `${getPercentage(value, dailySales)}%` }"></div>
            </div>
            <span class="text-sm text-gray-600 dark:text-gray-300 min-w-[60px] text-right">{{ formatPrice(value) }}</span>
          </div>
        </div>
      </div>
      
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Ödeme Yöntemleri</h3>
        <div class="space-y-4">
          <div v-for="(value, method) in paymentMethods" :key="method" class="flex items-center">
            <span class="text-sm font-medium text-gray-600 dark:text-gray-300 w-24 flex items-center">
              <span class="w-4 h-4 rounded-full mr-2" :class="getPaymentColor(method)"></span>
              {{ getPaymentName(method) }}
            </span>
            <div class="flex-grow h-2 bg-gray-200 dark:bg-gray-700 rounded-full mx-2">
              <div class="h-full rounded-full" :class="getPaymentColor(method)" :style="{ width: `${getPercentage(value, paymentMethods)}%` }"></div>
            </div>
            <span class="text-sm text-gray-600 dark:text-gray-300 min-w-[60px] text-right">{{ formatPrice(value) }}</span>
          </div>
        </div>
      </div>
      
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">İptal Oranı Analizi</h3>
        <div class="flex justify-center items-center h-40">
          <div class="w-40 h-40 relative">
            <svg viewBox="0 0 100 100" class="w-full h-full">
              <circle cx="50" cy="50" r="40" fill="none" stroke="#f3f4f6" stroke-width="10" />
              <circle cx="50" cy="50" r="40" fill="none" stroke="#ef4444" stroke-width="10" stroke-dasharray="251.2" :stroke-dashoffset="getCircleOffset(cancellationRate)" transform="rotate(-90 50 50)" />
            </svg>
            <div class="absolute inset-0 flex flex-col items-center justify-center">
              <span class="text-3xl font-bold text-gray-900 dark:text-white">{{ cancellationRate }}%</span>
              <span class="text-sm text-gray-500 dark:text-gray-400">İptal Oranı</span>
            </div>
          </div>
        </div>
        <div class="mt-4">
          <div class="flex justify-between text-sm">
            <span class="text-gray-500 dark:text-gray-400">Toplam İptal: {{ cancellations }}</span>
            <span :class="getDeltaClass(cancellationDelta)">
              {{ getDeltaPrefix(cancellationDelta) }}{{ Math.abs(cancellationDelta) }}%
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
import CategorySalesChart from '@/components/Admin/Charts/CategorySalesChart.vue';

export default defineComponent({
  name: 'SalesReport',
  components: {
    MonthlyIncomeChart,
    CategorySalesChart
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
    // Örnek günlük satış verileri
    const dailySales = ref({
      'monday': 4250,
      'tuesday': 3800,
      'wednesday': 5100,
      'thursday': 6200,
      'friday': 7500,
      'saturday': 9800,
      'sunday': 6700
    });
    
    // Örnek ödeme yöntemi verileri
    const paymentMethods = ref({
      'credit_card': 25600,
      'bank_transfer': 12400,
      'cash': 8700,
      'crypto': 2300
    });
    
    // İptal oranı verileri
    const cancellationRate = ref(8);
    const cancellations = ref(32);
    const cancellationDelta = ref(-2.5);
    
    // Artış/Azalış değeri için renk sınıfı
    const getDeltaClass = (delta) => {
      if (!delta) return 'text-gray-500 dark:text-gray-400';
      return delta < 0 
        ? 'text-green-600 dark:text-green-400' 
        : 'text-red-600 dark:text-red-400';
    };
    
    // Artış/Azalış için prefix
    const getDeltaPrefix = (delta) => {
      if (!delta) return '';
      return delta > 0 ? '+' : '';
    };
    
    // Yüzde hesapla
    const getPercentage = (value, dataObj) => {
      const total = Object.values(dataObj).reduce((sum, val) => sum + val, 0);
      return total === 0 ? 0 : Math.round((value / total) * 100);
    };
    
    // Gün isimleri
    const getDayName = (day) => {
      const days = {
        'monday': 'Pazartesi',
        'tuesday': 'Salı',
        'wednesday': 'Çarşamba',
        'thursday': 'Perşembe',
        'friday': 'Cuma',
        'saturday': 'Cumartesi',
        'sunday': 'Pazar'
      };
      
      return days[day] || day;
    };
    
    // Ödeme yöntemi isimleri
    const getPaymentName = (method) => {
      const methods = {
        'credit_card': 'Kredi Kartı',
        'bank_transfer': 'Havale',
        'cash': 'Nakit',
        'crypto': 'Kripto'
      };
      
      return methods[method] || method;
    };
    
    // Ödeme yöntemi renkleri
    const getPaymentColor = (method) => {
      const colors = {
        'credit_card': 'bg-blue-500 dark:bg-blue-400',
        'bank_transfer': 'bg-green-500 dark:bg-green-400',
        'cash': 'bg-yellow-500 dark:bg-yellow-400',
        'crypto': 'bg-purple-500 dark:bg-purple-400'
      };
      
      return colors[method] || 'bg-gray-500 dark:bg-gray-400';
    };
    
    // İptal oranı çemberi için offset hesapla
    const getCircleOffset = (rate) => {
      const circumference = 2 * Math.PI * 40; // 2πr
      return circumference * (1 - rate / 100);
    };
    
    return {
      dailySales,
      paymentMethods,
      cancellationRate,
      cancellations,
      cancellationDelta,
      getDeltaClass,
      getDeltaPrefix,
      getPercentage,
      getDayName,
      getPaymentName,
      getPaymentColor,
      getCircleOffset
    };
  }
});
</script> 