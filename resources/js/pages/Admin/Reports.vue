<template>
  <AdminLayout>
    <div class="container mx-auto">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
          Raporlar
        </h1>
        
        <div class="flex space-x-2">
          <button 
            @click="exportReport('pdf')" 
            class="px-4 py-2 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-lg border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors flex items-center"
            :disabled="loading || exportLoading"
          >
            <svg v-if="!exportLoading" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
            </svg>
            <svg v-else class="w-5 h-5 mr-2 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            PDF İndir
          </button>
          <button 
            @click="exportReport('excel')" 
            class="px-4 py-2 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-lg border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors flex items-center"
            :disabled="loading || exportLoading"
          >
            <svg v-if="!exportLoading" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
            </svg>
            <svg v-else class="w-5 h-5 mr-2 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Excel İndir
          </button>
        </div>
      </div>
      
      <!-- Filtreler -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
          <div class="flex items-center">
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300 mr-3">Dönem:</span>
            <select 
              v-model="selectedPeriod" 
              @change="fetchReportData"
              class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-white focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400"
              :disabled="loading"
            >
              <option value="today">Bugün</option>
              <option value="yesterday">Dün</option>
              <option value="this-week">Bu Hafta</option>
              <option value="last-week">Geçen Hafta</option>
              <option value="this-month">Bu Ay</option>
              <option value="last-month">Geçen Ay</option>
              <option value="last-3-months">Son 3 Ay</option>
              <option value="last-6-months">Son 6 Ay</option>
              <option value="this-year">Bu Yıl</option>
              <option value="last-year">Geçen Yıl</option>
              <option value="all-time">Tüm Zamanlar</option>
              <option value="custom">Özel Tarih Aralığı</option>
            </select>
          </div>
          
          <div v-if="selectedPeriod === 'custom'" class="flex flex-wrap items-center space-x-2">
            <div class="flex items-center space-x-2">
              <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Başlangıç:</span>
              <input type="date" v-model="customStartDate" class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-white focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400" />
            </div>
          <div class="flex items-center space-x-2">
              <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Bitiş:</span>
              <input type="date" v-model="customEndDate" class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-white focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400" />
            </div>
            <button 
              @click="fetchReportData" 
              class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors"
              :disabled="loading"
            >
              Uygula
            </button>
          </div>
        </div>
      </div>
      
      <!-- Yükleniyor Göstergesi -->
      <div v-if="loading" class="flex justify-center my-10">
        <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-primary-600"></div>
      </div>
      
      <!-- Hata Mesajı -->
      <div v-else-if="loadError" class="bg-red-100 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg mb-6">
          <div class="flex items-center">
          <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
              </svg>
          <p>{{ loadError }}</p>
        </div>
            </div>
      
      <!-- Rapor İçeriği -->
      <div v-else>
        <!-- Özet Kartlar -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
          <!-- Toplam Gelir -->
          <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Toplam Gelir</p>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mt-2">{{ formatPrice(summary.totalIncome) }}</h2>
              </div>
              <div :class="[
                'flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                summary.incomeChange > 0 ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400' : 
                summary.incomeChange < 0 ? 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400' : 
                'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300'
              ]">
                <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="summary.incomeChange > 0 ? 'M5 10l7-7m0 0l7 7m-7-7v18' : summary.incomeChange < 0 ? 'M19 14l-7 7m0 0l-7-7m7 7V3' : 'M5 12h14'" />
                </svg>
                {{ Math.abs(summary.incomeChange).toFixed(1) }}%
              </div>
            </div>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Önceki döneme göre</p>
        </div>
        
          <!-- Toplam Rezervasyon -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <div class="flex justify-between items-start">
              <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Toplam Rezervasyon</p>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mt-2">{{ summary.totalReservations }}</h2>
              </div>
              <div :class="[
                'flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                summary.reservationsChange > 0 ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400' : 
                summary.reservationsChange < 0 ? 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400' : 
                'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300'
              ]">
                <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="summary.reservationsChange > 0 ? 'M5 10l7-7m0 0l7 7m-7-7v18' : summary.reservationsChange < 0 ? 'M19 14l-7 7m0 0l-7-7m7 7V3' : 'M5 12h14'" />
              </svg>
                {{ Math.abs(summary.reservationsChange).toFixed(1) }}%
              </div>
            </div>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Önceki döneme göre</p>
          </div>
          
          <!-- Ortalama Satış -->
          <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Ortalama Satış</p>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mt-2">{{ formatPrice(summary.averageSale) }}</h2>
              </div>
              <div :class="[
                'flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                summary.averageSaleChange > 0 ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400' : 
                summary.averageSaleChange < 0 ? 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400' : 
                'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300'
              ]">
                <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="summary.averageSaleChange > 0 ? 'M5 10l7-7m0 0l7 7m-7-7v18' : summary.averageSaleChange < 0 ? 'M19 14l-7 7m0 0l-7-7m7 7V3' : 'M5 12h14'" />
                </svg>
                {{ Math.abs(summary.averageSaleChange).toFixed(1) }}%
              </div>
            </div>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Önceki döneme göre</p>
          </div>
        </div>
        
        <!-- Grafikler -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
          <!-- Aylık Gelir Grafiği -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Dönemsel Gelir</h2>
            <div class="h-80">
              <MonthlyIncomeChart 
                :chart-data="{
                  labels: monthlyIncome.labels,
                  datasets: [
                    {
                      label: 'Gelir (₺)',
                      data: monthlyIncome.data,
                      backgroundColor: 'rgba(59, 130, 246, 0.2)',
                      borderColor: 'rgba(59, 130, 246, 1)',
                      borderWidth: 2,
                      tension: 0.4,
                      fill: true
                    }
                  ]
                }" 
                :options="chartOptions" 
              />
            </div>
          </div>
          
          <!-- Kullanıcı Demografisi -->
          <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Kullanıcı Demografisi</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="h-60">
                <ParticipantDistributionChart 
                  :chart-data="getAgeGroupsChartData()"
                  :options="demographicChartOptions" 
                />
              </div>
              <div class="h-60">
                <ParticipantDistributionChart 
                  :chart-data="getGenderChartData()"
                  :options="pieChartOptions" 
                />
        </div>
      </div>
          </div>
        </div>
        
        <!-- Katılımcı İstatistikleri -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
          <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Katılımcı İstatistikleri</h2>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="col-span-2 h-72">
              <ParticipantDistributionChart 
                :chart-data="getParticipantChartData()" 
                :options="{
                  responsive: true,
                  maintainAspectRatio: false,
                  plugins: {
                    title: {
                      display: true,
                      text: 'Rezervasyon Başına Katılımcı Dağılımı',
                      font: {
                        size: 16,
                        weight: 'bold'
                      },
                      padding: {
                        top: 10,
                        bottom: 20
                      }
                    }
                  }
                }" 
              />
            </div>
            <div class="flex flex-col justify-center items-center">
              <div class="text-5xl font-bold text-primary-600 dark:text-primary-400">
                {{ participantStats.averageParticipants }}
              </div>
              <div class="text-base text-gray-600 dark:text-gray-400 text-center mt-2">
                Ortalama Katılımcı Sayısı
              </div>
              <div class="text-5xl font-bold text-primary-600 dark:text-primary-400 mt-6">
                {{ participantStats.totalParticipants }}
              </div>
              <div class="text-base text-gray-600 dark:text-gray-400 text-center mt-2">
                Toplam Katılımcı
          </div>
        </div>
      </div>
        </div>
        
        <!-- En Çok Satan Turlar -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
          <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">En Çok Satan Turlar</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full whitespace-nowrap">
            <thead>
              <tr class="text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider bg-gray-50 dark:bg-gray-700">
                  <th class="px-6 py-3">Tur</th>
                  <th class="px-6 py-3">Rezervasyon</th>
                  <th class="px-6 py-3">Gelir</th>
                  <th class="px-6 py-3">Ort. Satış</th>
                  <th class="px-6 py-3">Trend</th>
              </tr>
            </thead>
              <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                <tr v-for="tour in topTours" :key="tour.id" class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                <td class="px-6 py-4">
                  <div class="flex items-center">
                    <img :src="tour.image" alt="Tour Image" class="h-10 w-10 rounded-full object-cover mr-3">
                      <div>
                    <div class="font-medium text-gray-900 dark:text-white">{{ tour.name }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ tour.destination || 'Bilgi yok' }}</div>
                    </div>
                  </div>
                </td>
                  <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ tour.reservationCount }}</td>
                  <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ formatPrice(tour.totalSales) }}</td>
                  <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ formatPrice(tour.totalSales / (tour.reservationCount || 1)) }}</td>
                <td class="px-6 py-4">
                  <span 
                      class="px-2 py-1 text-xs rounded-full inline-flex items-center"
                      :class="getTrendClass(tour.trend)"
                    >
                      <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="getTrendIcon(tour.trend)" />
                      </svg>
                    {{ getTrendText(tour.trend) }}
                  </span>
                </td>
              </tr>
                <tr v-if="!topTours || topTours.length === 0">
                  <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">Veri bulunamadı</td>
              </tr>
            </tbody>
          </table>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script>
import { defineComponent, ref, onMounted, computed } from 'vue';
import AdminLayout from '@/layouts/AdminLayout.vue';
import MonthlyIncomeChart from '@/components/Admin/Charts/MonthlyIncomeChart.vue';
import ParticipantDistributionChart from '@/components/Admin/Charts/ParticipantDistributionChart.vue';
import axios from 'axios';

export default defineComponent({
  name: 'Reports',
  components: {
    AdminLayout,
    MonthlyIncomeChart,
    ParticipantDistributionChart
  },
  setup() {
    // State
    const loading = ref(false);
    const exportLoading = ref(false);
    const loadError = ref(null);
    const selectedPeriod = ref('this-month');
    const customStartDate = ref(null);
    const customEndDate = ref(null);
    
    // Data
    const summary = ref({
      totalIncome: 0,
      incomeChange: 0,
      totalReservations: 0,
      reservationsChange: 0,
      averageSale: 0,
      averageSaleChange: 0
    });
    
    const monthlyIncome = ref({
      labels: [],
      data: []
    });
    
    const topTours = ref([]);
    
    const userDemographics = ref({
      ageGroups: [],
      genderDistribution: {}
    });
    
    const participantStats = ref({
      totalParticipants: 0,
      averageParticipants: 0,
      participantCounts: {}
    });
    
    // Grafik seçenekleri
    const chartOptions = ref({
      responsive: true,
      maintainAspectRatio: false,
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
      },
      plugins: {
        tooltip: {
          callbacks: {
            label: function(context) {
              return new Intl.NumberFormat('tr-TR', {
                style: 'currency',
                currency: 'TRY'
              }).format(context.parsed.y);
            }
          }
        }
      }
    });
    
    const demographicChartOptions = ref({
      responsive: true,
      maintainAspectRatio: false,
      indexAxis: 'y',
      scales: {
        x: {
          beginAtZero: true,
          grid: {
            display: false
          }
        }
      }
    });
    
    const pieChartOptions = ref({
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'right'
        }
      }
    });
    
    // Methods
    const fetchReportData = async () => {
      loading.value = true;
      loadError.value = null;
      
      try {
        // API parametrelerini hazırla
        const params = { period: selectedPeriod.value };
        
        // Özel tarih aralığı seçildiyse tarihleri ekle
        if (selectedPeriod.value === 'custom') {
          if (!customStartDate.value || !customEndDate.value) {
            loadError.value = 'Lütfen başlangıç ve bitiş tarihlerini belirtin.';
            loading.value = false;
            return;
          }
          params.start_date = customStartDate.value;
          params.end_date = customEndDate.value;
        }
        
        // Tüm raporları paralel olarak çek
        const [summaryResponse, monthlyIncomeResponse, userDemographicsResponse, participantStatsResponse] = await Promise.all([
          axios.get('/api/admin/reports/summary', { params }),
          axios.get('/api/admin/reports/monthly-income', { params }),
          axios.get('/api/admin/reports/user-demographics', { params }),
          axios.get('/api/admin/reports/participant-stats', { params })
        ]);
        
        // API yanıtlarını state'e kaydet
        if (summaryResponse.data && summaryResponse.data.summary) {
          summary.value = summaryResponse.data.summary;
        }
        
        if (monthlyIncomeResponse.data && monthlyIncomeResponse.data.monthlyIncome) {
          monthlyIncome.value = monthlyIncomeResponse.data.monthlyIncome;
        }
        
        // topTours boş dizi olarak bırakıyoruz (API çağrısı kaldırıldı)
        
        if (userDemographicsResponse.data && userDemographicsResponse.data.userDemographics) {
          userDemographics.value = userDemographicsResponse.data.userDemographics;
        }
        
        if (participantStatsResponse.data && participantStatsResponse.data.participantStats) {
          participantStats.value = participantStatsResponse.data.participantStats;
        }
        
      } catch (error) {
        console.error('Rapor verileri alınırken hata oluştu:', error);
        loadError.value = 'Rapor verileri alınırken bir hata oluştu. Lütfen daha sonra tekrar deneyin.';
      } finally {
        loading.value = false;
      }
    };
    
    const exportReport = async (format) => {
      exportLoading.value = true;
      
      try {
        // API parametrelerini hazırla
        const params = {
          period: selectedPeriod.value,
          format: format
        };
        
        // Özel tarih aralığı seçildiyse tarihleri ekle
        if (selectedPeriod.value === 'custom') {
          if (!customStartDate.value || !customEndDate.value) {
            alert('Lütfen başlangıç ve bitiş tarihlerini belirtin.');
            exportLoading.value = false;
            return;
          }
          params.start_date = customStartDate.value;
          params.end_date = customEndDate.value;
        }
        
        const response = await axios.post('/api/admin/reports/export', params, {
          responseType: 'blob'
        });
        
        // Dosya ismini belirle
        const contentDisposition = response.headers['content-disposition'];
        let filename = 'report.' + format;
        
        if (contentDisposition) {
          const filenameMatch = contentDisposition.match(/filename="?(.+?)"?$/);
          if (filenameMatch && filenameMatch[1]) {
            filename = filenameMatch[1];
          }
        }
        
        // Dosyayı indir
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', filename);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
      } catch (error) {
        console.error('Rapor dışa aktarılırken hata oluştu:', error);
        alert('Rapor dışa aktarılırken bir hata oluştu. Lütfen daha sonra tekrar deneyin.');
      } finally {
        exportLoading.value = false;
      }
    };
    
    // Yaş grupları grafiği için veri hazırlama
    const getAgeGroupsChartData = () => {
      if (!userDemographics.value.ageGroups || userDemographics.value.ageGroups.length === 0) {
        return {
          labels: ['18-24', '25-34', '35-44', '45-54', '55+'],
          datasets: [{
            label: 'Yaş Dağılımı',
            data: [0, 0, 0, 0, 0],
            backgroundColor: 'rgba(59, 130, 246, 0.7)',
            borderColor: 'rgba(59, 130, 246, 1)',
            borderWidth: 1
          }]
        };
      }
      
      return {
        labels: userDemographics.value.ageGroups.map(group => group.age_group),
        datasets: [{
          label: 'Yaş Dağılımı',
          data: userDemographics.value.ageGroups.map(group => group.count),
          backgroundColor: 'rgba(59, 130, 246, 0.7)',
          borderColor: 'rgba(59, 130, 246, 1)',
          borderWidth: 1
        }]
      };
    };
    
    // Cinsiyet dağılımı grafiği için veri hazırlama
    const getGenderChartData = () => {
      if (!userDemographics.value.genderDistribution) {
        return {
          labels: ['Erkek', 'Kadın', 'Diğer'],
          datasets: [{
            label: 'Cinsiyet Dağılımı',
            data: [0, 0, 0],
            backgroundColor: [
              'rgba(59, 130, 246, 0.7)',
              'rgba(236, 72, 153, 0.7)',
              'rgba(168, 85, 247, 0.7)'
            ],
            borderColor: [
              'rgba(59, 130, 246, 1)',
              'rgba(236, 72, 153, 1)',
              'rgba(168, 85, 247, 1)'
            ],
            borderWidth: 1
          }]
        };
      }
      
      const labels = [];
      const data = [];
      const backgroundColors = [];
      const borderColors = [];
      
      if ('male' in userDemographics.value.genderDistribution) {
        labels.push('Erkek');
        data.push(userDemographics.value.genderDistribution.male);
        backgroundColors.push('rgba(59, 130, 246, 0.7)');
        borderColors.push('rgba(59, 130, 246, 1)');
      }
      
      if ('female' in userDemographics.value.genderDistribution) {
        labels.push('Kadın');
        data.push(userDemographics.value.genderDistribution.female);
        backgroundColors.push('rgba(236, 72, 153, 0.7)');
        borderColors.push('rgba(236, 72, 153, 1)');
      }
      
      if ('other' in userDemographics.value.genderDistribution) {
        labels.push('Diğer');
        data.push(userDemographics.value.genderDistribution.other);
        backgroundColors.push('rgba(168, 85, 247, 0.7)');
        borderColors.push('rgba(168, 85, 247, 1)');
      }
      
      return {
        labels,
        datasets: [{
          label: 'Cinsiyet Dağılımı',
          data,
          backgroundColor: backgroundColors,
          borderColor: borderColors,
          borderWidth: 1
        }]
      };
    };
    
    // Katılımcı dağılımı grafiği için veri hazırlama
    const getParticipantChartData = () => {
      if (!participantStats.value.participantCounts) {
        return {
          labels: ['1', '2', '3', '4', '5+'],
          datasets: [{
            label: 'Rezervasyon Sayısı',
            data: [0, 0, 0, 0, 0],
            backgroundColor: 'rgba(16, 185, 129, 0.7)',
            borderColor: 'rgba(16, 185, 129, 1)',
            borderWidth: 1
          }]
        };
      }
      
      const counts = participantStats.value.participantCounts;
      const labels = Object.keys(counts).sort((a, b) => Number(a) - Number(b));
      const data = labels.map(key => counts[key]);
      
      return {
        labels,
        datasets: [{
          label: 'Rezervasyon Sayısı',
          data,
          backgroundColor: 'rgba(16, 185, 129, 0.7)',
          borderColor: 'rgba(16, 185, 129, 1)',
          borderWidth: 1
        }]
      };
    };
    
    // Yardımcı metodlar
const formatPrice = (price) => {
      return new Intl.NumberFormat('tr-TR', {
        style: 'currency',
        currency: 'TRY'
      }).format(price || 0);
};

const getTrendText = (trend) => {
      switch (trend) {
        case 'up': return 'Yükseliyor';
        case 'down': return 'Düşüyor';
        case 'stable': return 'Stabil';
        default: return 'Bilinmiyor';
      }
    };
    
    const getTrendClass = (trend) => {
      switch (trend) {
        case 'up': return 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400';
        case 'down': return 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400';
        case 'stable': return 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-400';
        default: return 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300';
      }
    };
    
    const getTrendIcon = (trend) => {
      switch (trend) {
        case 'up': return 'M5 10l7-7m0 0l7 7m-7-7v18';
        case 'down': return 'M19 14l-7 7m0 0l-7-7m7 7V3';
        case 'stable': return 'M5 12h14';
        default: return 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z';
      }
    };
    
    const getGenderName = (gender) => {
      switch (gender) {
        case 'male': return 'Erkek';
        case 'female': return 'Kadın';
        case 'other': return 'Diğer';
        default: return 'Bilinmiyor';
      }
    };
    
    // Sayfa yüklendiğinde verileri çek
    onMounted(() => {
      fetchReportData();
    });
    
    return {
      loading,
      exportLoading,
      loadError,
      selectedPeriod,
      customStartDate,
      customEndDate,
      summary,
      monthlyIncome,
      topTours,
      userDemographics,
      participantStats,
      chartOptions,
      demographicChartOptions,
      pieChartOptions,
      fetchReportData,
      exportReport,
      getAgeGroupsChartData,
      getGenderChartData,
      getParticipantChartData,
      formatPrice,
      getTrendText,
      getTrendClass,
      getTrendIcon,
      getGenderName
    };
  }
});
</script> 