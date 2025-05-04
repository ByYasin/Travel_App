<template>
  <div>
    <!-- Rezervasyon Özeti -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
      <!-- Toplam Rezervasyon -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <div class="flex items-center">
          <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900 mr-4">
            <svg class="w-6 h-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
          </div>
          <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Toplam Rezervasyon</p>
            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ formatNumber(summary.totalReservations) }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
              <span :class="getDeltaClass(summary.reservationDelta)">
                {{ getDeltaPrefix(summary.reservationDelta) }}{{ Math.abs(summary.reservationDelta) }}%
              </span>
              <span class="ml-1">geçen dönem</span>
            </p>
          </div>
        </div>
      </div>
      
      <!-- Tamamlanan Rezervasyon -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <div class="flex items-center">
          <div class="p-3 rounded-full bg-green-100 dark:bg-green-900 mr-4">
            <svg class="w-6 h-6 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
          </div>
          <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tamamlanan</p>
            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ formatNumber(summary.completedReservations) }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
              <span :class="getDeltaClass(summary.completedDelta)">
                {{ getDeltaPrefix(summary.completedDelta) }}{{ Math.abs(summary.completedDelta) }}%
              </span>
              <span class="ml-1">geçen dönem</span>
            </p>
          </div>
        </div>
      </div>
      
      <!-- İptal Edilen Rezervasyon -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <div class="flex items-center">
          <div class="p-3 rounded-full bg-red-100 dark:bg-red-900 mr-4">
            <svg class="w-6 h-6 text-red-600 dark:text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </div>
          <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">İptal Edilen</p>
            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ formatNumber(summary.cancelledReservations) }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
              <span :class="getInverseDeltaClass(summary.cancelledDelta)">
                {{ getDeltaPrefix(summary.cancelledDelta) }}{{ Math.abs(summary.cancelledDelta) }}%
              </span>
              <span class="ml-1">geçen dönem</span>
            </p>
          </div>
        </div>
      </div>
      
      <!-- Doluluk Oranı -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <div class="flex items-center">
          <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900 mr-4">
            <svg class="w-6 h-6 text-purple-600 dark:text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
          </div>
          <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Doluluk Oranı</p>
            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ summary.occupancyRate }}%</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
              <span :class="getDeltaClass(summary.occupancyDelta)">
                {{ getDeltaPrefix(summary.occupancyDelta) }}{{ Math.abs(summary.occupancyDelta) }}%
              </span>
              <span class="ml-1">geçen dönem</span>
            </p>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Rezervasyon Grafikleri -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Aylık Rezervasyon</h3>
        <div class="h-80">
          <MonthlyReservationChart 
            :chart-data="monthlyReservations" 
            :chart-options="chartOptions"
          />
        </div>
      </div>
      
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Rezervasyon Durumları</h3>
        <div class="h-80">
          <ReservationStatusChart 
            :chart-data="reservationStatuses" 
            :chart-options="chartOptions"
          />
        </div>
      </div>
    </div>
    
    <!-- İleri Analiz Kartları -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
      <!-- Rezervasyon Kaynak Analizi -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Rezervasyon Kaynakları</h3>
        <div class="space-y-4">
          <div v-for="(value, source) in reservationSources" :key="source" class="flex items-center">
            <span class="text-sm font-medium text-gray-600 dark:text-gray-300 w-24 flex items-center">
              <span class="w-3 h-3 rounded-full mr-2" :class="getSourceColor(source)"></span>
              {{ getSourceName(source) }}
            </span>
            <div class="flex-grow h-2 bg-gray-200 dark:bg-gray-700 rounded-full mx-2">
              <div class="h-full rounded-full" :class="getSourceColor(source)" :style="{ width: `${getPercentage(value, reservationSources)}%` }"></div>
            </div>
            <span class="text-sm text-gray-600 dark:text-gray-300 min-w-[50px] text-right">{{ getPercentage(value, reservationSources) }}%</span>
          </div>
        </div>
      </div>
      
      <!-- Rezervasyon İptal Nedenleri -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">İptal Nedenleri</h3>
        <div class="space-y-4">
          <div v-for="(reason, index) in cancellationReasons" :key="index" class="flex items-center">
            <span class="text-sm font-medium text-gray-600 dark:text-gray-300 w-24 truncate" :title="reason.reason">{{ reason.reason }}</span>
            <div class="flex-grow h-2 bg-gray-200 dark:bg-gray-700 rounded-full mx-2">
              <div class="h-full rounded-full bg-red-500 dark:bg-red-400" :style="{ width: `${reason.percentage}%` }"></div>
            </div>
            <span class="text-sm text-gray-600 dark:text-gray-300 min-w-[50px] text-right">{{ reason.percentage }}%</span>
          </div>
        </div>
      </div>
      
      <!-- Rezervasyon Lead Time -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Rezervasyon Zamanı</h3>
        <div class="flex flex-col h-40 justify-center">
          <div class="flex items-center justify-between mb-2">
            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Ortalama Önceden Rezervasyon</span>
            <span class="text-lg font-semibold text-gray-900 dark:text-white">{{ leadTime.average }} gün</span>
          </div>
          <div class="flex items-center justify-between mb-2">
            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Erken Rezervasyon (>30 gün)</span>
            <span class="text-lg font-semibold text-gray-900 dark:text-white">{{ leadTime.early }}%</span>
          </div>
          <div class="flex items-center justify-between mb-2">
            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Son Dakika (<7 gün)</span>
            <span class="text-lg font-semibold text-gray-900 dark:text-white">{{ leadTime.lastMinute }}%</span>
          </div>
          <div class="flex items-center justify-between">
            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Değişim</span>
            <span :class="getDeltaClass(leadTime.delta)" class="text-sm font-medium">
              {{ getDeltaPrefix(leadTime.delta) }}{{ Math.abs(leadTime.delta) }} gün
            </span>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Turların Rezervasyon Doluluğu -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
      <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Tur Doluluk Oranları</h3>
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
          <thead>
            <tr>
              <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tur</th>
              <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kapasite</th>
              <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Rezervasyon</th>
              <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Doluluk</th>
              <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Trend</th>
            </tr>
          </thead>
          <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
            <tr v-for="tour in tourOccupancy" :key="tour.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
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
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ tour.capacity }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ tour.reservations }}</td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="w-32 bg-gray-200 dark:bg-gray-700 rounded-full h-2.5 mr-2">
                    <div :class="getOccupancyColor(tour.occupancyRate)" class="h-2.5 rounded-full" :style="{ width: `${tour.occupancyRate}%` }"></div>
                  </div>
                  <span class="text-sm text-gray-500 dark:text-gray-400">{{ tour.occupancyRate }}%</span>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full" :class="getDeltaClass(tour.trend)">
                  {{ getDeltaPrefix(tour.trend) }}{{ Math.abs(tour.trend) }}%
                </span>
              </td>
            </tr>
            <tr v-if="!tourOccupancy || tourOccupancy.length === 0">
              <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">Veri bulunamadı</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
import { defineComponent, ref, computed } from 'vue';
import MonthlyReservationChart from '@/components/Admin/Charts/MonthlyReservationChart.vue';
import ReservationStatusChart from '@/components/Admin/Charts/ReservationStatusChart.vue';

export default defineComponent({
  name: 'ReservationReport',
  components: {
    MonthlyReservationChart,
    ReservationStatusChart
  },
  props: {
    summary: {
      type: Object,
      required: true
    },
    monthlyReservations: {
      type: Object,
      required: true
    },
    reservationStatuses: {
      type: Object,
      required: true
    },
    chartOptions: {
      type: Object,
      required: true
    },
    formatNumber: {
      type: Function,
      required: true
    }
  },
  setup() {
    // Örnek rezervasyon kaynakları
    const reservationSources = ref({
      'website': 45,
      'mobile': 30,
      'partner': 15,
      'phone': 10
    });
    
    // İptal nedenleri örnek verileri
    const cancellationReasons = ref([
      { reason: 'Kişisel Nedenler', percentage: 35 },
      { reason: 'Sağlık Sorunları', percentage: 25 },
      { reason: 'Plan Değişikliği', percentage: 20 },
      { reason: 'Finansal Nedenler', percentage: 15 },
      { reason: 'Diğer', percentage: 5 }
    ]);
    
    // Rezervasyon lead time verileri
    const leadTime = ref({
      average: 21,
      early: 35,
      lastMinute: 25,
      delta: 2.5
    });
    
    // Örnek tur doluluk oranları
    const tourOccupancy = ref([
      { id: 1, name: 'İstanbul Turu', destination: 'İstanbul', capacity: 30, reservations: 28, occupancyRate: 93, trend: 5, image: null },
      { id: 2, name: 'Kapadokya Macerası', destination: 'Nevşehir', capacity: 20, reservations: 18, occupancyRate: 90, trend: 2, image: null },
      { id: 3, name: 'Ege Kıyıları', destination: 'İzmir', capacity: 25, reservations: 20, occupancyRate: 80, trend: -3, image: null },
      { id: 4, name: 'Akdeniz Esintisi', destination: 'Antalya', capacity: 35, reservations: 24, occupancyRate: 69, trend: 4, image: null },
      { id: 5, name: 'Karadeniz Yaylaları', destination: 'Trabzon', capacity: 15, reservations: 7, occupancyRate: 47, trend: -8, image: null }
    ]);
    
    // Artış/Azalış değeri için renk sınıfı
    const getDeltaClass = (delta) => {
      if (!delta) return 'text-gray-500 dark:text-gray-400';
      return delta > 0 
        ? 'text-green-600 dark:text-green-400' 
        : 'text-red-600 dark:text-red-400';
    };
    
    // İptal için ters renk sınıfı (azalma iyidir)
    const getInverseDeltaClass = (delta) => {
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
    
    // Rezervasyon kaynağı isimleri
    const getSourceName = (source) => {
      const sources = {
        'website': 'Web Site',
        'mobile': 'Mobil App',
        'partner': 'Partner',
        'phone': 'Telefon'
      };
      
      return sources[source] || source;
    };
    
    // Rezervasyon kaynağı renkleri
    const getSourceColor = (source) => {
      const colors = {
        'website': 'bg-blue-500 dark:bg-blue-400',
        'mobile': 'bg-green-500 dark:bg-green-400',
        'partner': 'bg-yellow-500 dark:bg-yellow-400',
        'phone': 'bg-purple-500 dark:bg-purple-400'
      };
      
      return colors[source] || 'bg-gray-500 dark:bg-gray-400';
    };
    
    // Doluluk oranına göre renk belirle
    const getOccupancyColor = (rate) => {
      if (rate >= 90) return 'bg-green-600 dark:bg-green-500';
      if (rate >= 70) return 'bg-blue-600 dark:bg-blue-500';
      if (rate >= 50) return 'bg-yellow-600 dark:bg-yellow-500';
      return 'bg-red-600 dark:bg-red-500';
    };
    
    return {
      reservationSources,
      cancellationReasons,
      leadTime,
      tourOccupancy,
      getDeltaClass,
      getInverseDeltaClass,
      getDeltaPrefix,
      getPercentage,
      getSourceName,
      getSourceColor,
      getOccupancyColor
    };
  }
});
</script> 