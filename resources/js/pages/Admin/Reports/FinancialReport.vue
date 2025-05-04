<template>
  <div>
    <!-- Finansal Özet Kartları -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Toplam Gelir</h3>
          <div class="p-2 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </div>
        <div class="text-3xl font-bold text-gray-900 dark:text-white mb-1">
          {{ formatPrice(financialData.totalRevenue) }}
        </div>
        <div class="flex items-center text-sm">
          <span :class="getChangeClass(financialData.revenueChange)">
            <svg v-if="financialData.revenueChange > 0" class="w-3 h-3 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
            </svg>
            <svg v-else-if="financialData.revenueChange < 0" class="w-3 h-3 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
            </svg>
            {{ Math.abs(financialData.revenueChange) }}% 
          </span>
          <span class="text-gray-500 dark:text-gray-400 ml-1">bir önceki döneme göre</span>
        </div>
      </div>
      
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Toplam Gider</h3>
          <div class="p-2 rounded-full bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4m8 7V5" />
            </svg>
          </div>
        </div>
        <div class="text-3xl font-bold text-gray-900 dark:text-white mb-1">
          {{ formatPrice(financialData.totalExpenses) }}
        </div>
        <div class="flex items-center text-sm">
          <span :class="getChangeClass(-financialData.expensesChange)">
            <svg v-if="financialData.expensesChange < 0" class="w-3 h-3 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
            </svg>
            <svg v-else-if="financialData.expensesChange > 0" class="w-3 h-3 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
            </svg>
            {{ Math.abs(financialData.expensesChange) }}% 
          </span>
          <span class="text-gray-500 dark:text-gray-400 ml-1">bir önceki döneme göre</span>
        </div>
      </div>
      
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Net Kar</h3>
          <div class="p-2 rounded-full bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
          </div>
        </div>
        <div class="text-3xl font-bold text-gray-900 dark:text-white mb-1">
          {{ formatPrice(financialData.netProfit) }}
        </div>
        <div class="flex items-center text-sm">
          <span :class="getChangeClass(financialData.profitChange)">
            <svg v-if="financialData.profitChange > 0" class="w-3 h-3 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
            </svg>
            <svg v-else-if="financialData.profitChange < 0" class="w-3 h-3 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
            </svg>
            {{ Math.abs(financialData.profitChange) }}% 
          </span>
          <span class="text-gray-500 dark:text-gray-400 ml-1">bir önceki döneme göre</span>
        </div>
      </div>
    </div>
    
    <!-- Gelir ve Gider Grafiği -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
      <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Gelir ve Gider Dağılımı</h3>
      <div class="h-80">
        <RevenueExpenseChart :chart-data="revenueExpenseChartData" />
      </div>
    </div>
    
    <!-- Finansal Analiz Göstergeleri -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Finansal Göstergeler</h3>
        <div class="space-y-4">
          <div>
            <div class="flex justify-between mb-1">
              <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Kar Marjı</span>
              <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ financialData.profitMargin }}%</span>
            </div>
            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
              <div class="bg-green-500 dark:bg-green-400 h-2 rounded-full" :style="{ width: `${financialData.profitMargin}%` }"></div>
            </div>
          </div>
          
          <div>
            <div class="flex justify-between mb-1">
              <span class="text-sm font-medium text-gray-700 dark:text-gray-300">İşletme Gideri Oranı</span>
              <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ financialData.operatingExpenseRatio }}%</span>
            </div>
            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
              <div class="bg-red-500 dark:bg-red-400 h-2 rounded-full" :style="{ width: `${financialData.operatingExpenseRatio}%` }"></div>
            </div>
          </div>
          
          <div>
            <div class="flex justify-between mb-1">
              <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Ödeme Toplama Endeksi</span>
              <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ financialData.paymentCollectionIndex }}%</span>
            </div>
            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
              <div class="bg-blue-500 dark:bg-blue-400 h-2 rounded-full" :style="{ width: `${financialData.paymentCollectionIndex}%` }"></div>
            </div>
          </div>
          
          <div>
            <div class="flex justify-between mb-1">
              <span class="text-sm font-medium text-gray-700 dark:text-gray-300">İptal Oranı Maliyeti</span>
              <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ financialData.cancellationCostRatio }}%</span>
            </div>
            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
              <div class="bg-yellow-500 dark:bg-yellow-400 h-2 rounded-full" :style="{ width: `${financialData.cancellationCostRatio}%` }"></div>
            </div>
          </div>
        </div>
      </div>
      
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">En Yüksek Ciro Getiren Turlar</h3>
        <ul class="space-y-3">
          <li v-for="tour in topRevenueTours" :key="tour.id" class="flex items-center p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/40">
            <img v-if="tour.image" :src="tour.image" alt="" class="h-10 w-10 rounded-full mr-3 object-cover">
            <div v-else class="h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center mr-3">
              <svg class="h-6 w-6 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
              </svg>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ tour.name }}</p>
              <p class="text-sm text-gray-500 dark:text-gray-400 truncate">{{ tour.destination }}</p>
            </div>
            <div class="inline-flex items-center text-sm font-semibold text-gray-900 dark:text-white">
              {{ formatPrice(tour.revenue) }}
              <svg :class="getTrendIconClass(tour.trend)" class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="getTrendIcon(tour.trend)" />
              </svg>
            </div>
          </li>
        </ul>
      </div>
    </div>
    
    <!-- Açık Ödemeler -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
      <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Açık Ödemeler</h3>
        <span class="text-sm px-3 py-1 rounded-full bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-400">
          {{ pendingPayments.length }} bekleyen ödeme
        </span>
      </div>
      
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
          <thead>
            <tr>
              <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Rezervasyon No</th>
              <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Müşteri</th>
              <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tutar</th>
              <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Son Ödeme</th>
              <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Durum</th>
            </tr>
          </thead>
          <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
            <tr v-for="payment in pendingPayments" :key="payment.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ payment.reservationNumber }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ payment.customerName }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ formatPrice(payment.amount) }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ payment.dueDate }}</td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full" 
                      :class="payment.status === 'overdue' ? 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400' : 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-400'">
                  {{ payment.status === 'overdue' ? 'Gecikmiş' : 'Bekliyor' }}
                </span>
              </td>
            </tr>
            <tr v-if="!pendingPayments || pendingPayments.length === 0">
              <td colspan="5" class="px-6 py-4 text-sm text-center text-gray-500 dark:text-gray-400">Bekleyen ödeme bulunmamaktadır</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
import { defineComponent, ref, computed } from 'vue';
import RevenueExpenseChart from '@/components/Admin/Charts/RevenueExpenseChart.vue';

export default defineComponent({
  name: 'FinancialReport',
  components: {
    RevenueExpenseChart
  },
  props: {
    formatPrice: {
      type: Function,
      required: true
    }
  },
  setup() {
    // Finansal Veri
    const financialData = ref({
      totalRevenue: 568950,
      totalExpenses: 326430,
      netProfit: 242520,
      revenueChange: 12.4,
      expensesChange: 8.2,
      profitChange: 18.6,
      profitMargin: 42.6,
      operatingExpenseRatio: 57.4,
      paymentCollectionIndex: 92.5,
      cancellationCostRatio: 4.8
    });
    
    // En Çok Kazanç Sağlayan Turlar
    const topRevenueTours = ref([
      {
        id: 1,
        name: 'Kapadokya Turu',
        destination: 'Nevşehir',
        revenue: 89450,
        trend: 'up',
        image: '/images/tours/cappadocia.jpg'
      },
      {
        id: 2,
        name: 'İstanbul Boğaz Turu',
        destination: 'İstanbul',
        revenue: 64320,
        trend: 'up',
        image: '/images/tours/istanbul.jpg'
      },
      {
        id: 3,
        name: 'Pamukkale & Hierapolis',
        destination: 'Denizli',
        revenue: 52860,
        trend: 'stable',
        image: '/images/tours/pamukkale.jpg'
      },
      {
        id: 4,
        name: 'Efes Antik Kenti',
        destination: 'İzmir',
        revenue: 48750,
        trend: 'up',
        image: '/images/tours/ephesus.jpg'
      },
      {
        id: 5,
        name: 'Salda Gölü Turu',
        destination: 'Burdur',
        revenue: 38620,
        trend: 'down',
        image: '/images/tours/salda.jpg'
      }
    ]);
    
    // Bekleyen Ödemeler
    const pendingPayments = ref([
      {
        id: 1,
        reservationNumber: 'RSV-5842',
        customerName: 'Ahmet Yılmaz',
        amount: 3250,
        dueDate: '15 Haziran 2023',
        status: 'pending'
      },
      {
        id: 2,
        reservationNumber: 'RSV-5836',
        customerName: 'Zeynep Kaya',
        amount: 4800,
        dueDate: '12 Haziran 2023',
        status: 'overdue'
      },
      {
        id: 3,
        reservationNumber: 'RSV-5825',
        customerName: 'Mehmet Demir',
        amount: 2950,
        dueDate: '18 Haziran 2023',
        status: 'pending'
      },
      {
        id: 4,
        reservationNumber: 'RSV-5820',
        customerName: 'Ayşe Şahin',
        amount: 5450,
        dueDate: '10 Haziran 2023',
        status: 'overdue'
      }
    ]);
    
    // Gelir ve Gider Grafiği için veri
    const revenueExpenseChartData = computed(() => {
      return {
        labels: ['Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran'],
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
    });
    
    // Yardımcı Fonksiyonlar
    const getChangeClass = (change) => {
      if (change > 0) return 'text-green-600 dark:text-green-400';
      if (change < 0) return 'text-red-600 dark:text-red-400';
      return 'text-gray-600 dark:text-gray-400';
    };
    
    const getTrendIcon = (trend) => {
      switch (trend) {
        case 'up': return 'M5 10l7-7m0 0l7 7m-7-7v18';
        case 'down': return 'M19 14l-7 7m0 0l-7-7m7 7V3';
        default: return 'M5 12h14';
      }
    };
    
    const getTrendIconClass = (trend) => {
      switch (trend) {
        case 'up': return 'text-green-600 dark:text-green-400';
        case 'down': return 'text-red-600 dark:text-red-400';
        default: return 'text-yellow-600 dark:text-yellow-400';
      }
    };
    
    return {
      financialData,
      topRevenueTours,
      pendingPayments,
      revenueExpenseChartData,
      getChangeClass,
      getTrendIcon,
      getTrendIconClass
    };
  }
});
</script> 