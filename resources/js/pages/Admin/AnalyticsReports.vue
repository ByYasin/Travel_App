<template>
  <AdminLayout>
    <div class="container mx-auto">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
          Analitik Raporlar
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
          <div class="flex flex-wrap items-center gap-2">
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
            
            <div class="flex items-center">
              <span class="text-sm font-medium text-gray-700 dark:text-gray-300 mr-3">Tür:</span>
              <select 
                v-model="selectedReportType" 
                @change="fetchReportData"
                class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-white focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400"
                :disabled="loading"
              >
                <option value="general">Genel Bakış</option>
                <option value="sales">Satış Analizi</option>
                <option value="demographics">Demografik Analiz</option>
                <option value="destinations">Destinasyon Analizi</option>
                <option value="comparison">Karşılaştırmalı Analiz</option>
              </select>
            </div>
          </div>
          
          <div v-if="selectedPeriod === 'custom'" class="flex flex-wrap items-center gap-2">
            <div class="flex items-center">
              <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Başlangıç:</span>
              <input type="date" v-model="customStartDate" class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-white focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400" />
            </div>
            <div class="flex items-center">
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

          <div v-if="selectedReportType === 'comparison'" class="flex flex-wrap items-center gap-2">
            <div class="flex items-center">
              <span class="text-sm font-medium text-gray-700 dark:text-gray-300 mr-3">Karşılaştırma Dönemi:</span>
              <select 
                v-model="comparisonPeriod" 
                @change="fetchReportData"
                class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-white focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400"
                :disabled="loading"
              >
                <option value="previous-period">Önceki Dönem</option>
                <option value="last-year-same-period">Geçen Yıl Aynı Dönem</option>
                <option value="custom">Özel Dönem</option>
              </select>
            </div>
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
      
      <!-- Ana İçerik -->
      <div v-else>
        <!-- Özet Bilgiler -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
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
          
          <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <div class="flex justify-between items-start">
              <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Doluluk Oranı</p>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mt-2">{{ occupancyRate.toFixed(1) }}%</h2>
              </div>
              <div :class="[
                'flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                occupancyRateChange > 0 ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400' : 
                occupancyRateChange < 0 ? 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400' : 
                'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300'
              ]">
                <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="occupancyRateChange > 0 ? 'M5 10l7-7m0 0l7 7m-7-7v18' : occupancyRateChange < 0 ? 'M19 14l-7 7m0 0l-7-7m7 7V3' : 'M5 12h14'" />
                </svg>
                {{ Math.abs(occupancyRateChange).toFixed(1) }}%
              </div>
            </div>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Önceki döneme göre</p>
          </div>
        </div>
        
        <!-- Rapor tipine göre içerik göster -->
        <component :is="currentReportComponent" 
          :monthly-income="monthlyIncome"
          :top-tours="topTours"
          :user-demographics="userDemographics"
          :participant-stats="participantStats"
          :chart-options="chartOptions"
          :demographic-chart-options="demographicChartOptions"
          :pie-chart-options="pieChartOptions"
          :destination-data="destinationData"
          :comparison-data="comparisonData"
          :format-price="formatPrice"
          :get-trend-text="getTrendText"
          :get-trend-class="getTrendClass"
          :get-trend-icon="getTrendIcon"
          :get-gender-name="getGenderName"
        />
      </div>
    </div>
  </AdminLayout>
</template>

<script>
import { defineComponent, ref, onMounted, computed } from 'vue';
import AdminLayout from '@/layouts/AdminLayout.vue';
import MonthlyIncomeChart from '@/components/Admin/Charts/MonthlyIncomeChart.vue';
import ParticipantDistributionChart from '@/components/Admin/Charts/ParticipantDistributionChart.vue';
import GeneralReport from './Reports/GeneralReport.vue';
import SalesReport from './Reports/SalesReport.vue';
import DemographicsReport from './Reports/DemographicsReport.vue';
import DestinationsReport from './Reports/DestinationsReport.vue';
import ComparisonReport from './Reports/ComparisonReport.vue';
import axios from 'axios';
import { Pie } from 'vue-chartjs';
import { Chart as ChartJS, ArcElement, Tooltip, Legend, Title } from 'chart.js';

// Chart.js bileşenlerini kaydet
ChartJS.register(ArcElement, Tooltip, Legend, Title);

export default defineComponent({
  name: 'AnalyticsReports',
  components: {
    AdminLayout,
    MonthlyIncomeChart,
    ParticipantDistributionChart,
    Pie,
    GeneralReport,
    SalesReport,
    DemographicsReport,
    DestinationsReport,
    ComparisonReport
  },
  setup() {
    const loading = ref(false);
    const loadError = ref(null);
    const exportLoading = ref(false);
    const selectedPeriod = ref('this-month');
    const selectedReportType = ref('general');
    const comparisonPeriod = ref('previous-period');
    const customStartDate = ref('');
    const customEndDate = ref('');

    // Rapor tipi (component) mapping
    const reportComponents = {
      'general': GeneralReport,
      'sales': SalesReport,
      'demographics': DemographicsReport,
      'destinations': DestinationsReport,
      'comparison': ComparisonReport
    };

    // Geçerli rapor bileşeni
    const currentReportComponent = computed(() => {
      return reportComponents[selectedReportType.value] || GeneralReport;
    });

    // Data refs
    const summary = ref({
      totalIncome: 0,
      totalReservations: 0,
      averageSale: 0,
      incomeChange: 0,
      reservationsChange: 0,
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
      participantDistribution: [],
      averageParticipants: 0
    });

    // Yeni veri setleri
    const occupancyRate = ref(75.5);
    const occupancyRateChange = ref(3.2);
    const destinationData = ref({
      popular: [],
      emerging: [],
      seasonal: []
    });
    const comparisonData = ref({
      currentPeriod: {},
      previousPeriod: {}
    });

    // Chart options
    const chartOptions = {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        title: {
          display: true,
          text: 'Dönemsel Gelir',
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
    };

    const demographicChartOptions = {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        title: {
          display: true,
          text: 'Yaş Gruplarına Göre Kullanıcı Dağılımı',
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
    };

    const pieChartOptions = {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        title: {
          display: true,
          text: 'Cinsiyete Göre Kullanıcı Dağılımı',
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
    };

    // Metotlar
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
        
        // Temel raporları al
        const [
          summaryResponse,
          monthlyIncomeResponse,
          topToursResponse,
          userDemographicsResponse,
          participantStatsResponse
        ] = await Promise.all([
          axios.get(`/api/admin/reports/summary`, { params }),
          axios.get(`/api/admin/reports/monthly-income`, { params }),
          axios.get(`/api/admin/reports/top-tours`, { params }),
          axios.get(`/api/admin/reports/user-demographics`),
          axios.get(`/api/admin/reports/participant-stats`, { params })
        ]);
        
        // Verileri state'e kaydet ve güvenlik kontrolü yap
        if (summaryResponse.data && summaryResponse.data.summary) {
          summary.value = summaryResponse.data.summary;
        } else {
          console.error('Özet verisi beklenen formatta değil:', summaryResponse.data);
          summary.value = {
            totalIncome: 0,
            totalReservations: 0,
            averageSale: 0,
            incomeChange: 0,
            reservationsChange: 0,
            averageSaleChange: 0
          };
        }
        
        if (monthlyIncomeResponse.data) {
          monthlyIncome.value = {
            labels: monthlyIncomeResponse.data.labels || [],
            data: monthlyIncomeResponse.data.data || []
          };
        }
        
        topTours.value = Array.isArray(topToursResponse.data) ? topToursResponse.data : [];
        
        if (userDemographicsResponse.data) {
          userDemographics.value = {
            ageGroups: userDemographicsResponse.data.ageGroups || [],
            genderDistribution: userDemographicsResponse.data.genderDistribution || {}
          };
        }
        
        if (participantStatsResponse.data) {
          participantStats.value = {
            participantDistribution: participantStatsResponse.data.participantDistribution || [],
            averageParticipants: participantStatsResponse.data.averageParticipants || 0
          };
        }

        // Rapor tipine göre ek veri istekleri
        if (selectedReportType.value === 'destinations') {
          try {
            const destinationsResponse = await axios.get(`/api/admin/reports/destinations?period=${selectedPeriod.value}`);
            destinationData.value = destinationsResponse.data || {
              popular: [],
              emerging: [],
              seasonal: []
            };
          } catch (error) {
            console.error('Destinasyon verisi alınırken hata:', error);
            // Demo verileri yükle
            destinationData.value = {
              popular: [
                { name: 'İstanbul', count: 245, growth: 12 },
                { name: 'Antalya', count: 189, growth: 8 },
                { name: 'Kapadokya', count: 156, growth: 15 }
              ],
              emerging: [
                { name: 'Şirince', count: 78, growth: 42 },
                { name: 'Mardin', count: 56, growth: 35 }
              ],
              seasonal: [
                { name: 'Uludağ', peak: 'Kış', count: 89 },
                { name: 'Bodrum', peak: 'Yaz', count: 220 }
              ]
            };
          }
        }
        
        if (selectedReportType.value === 'comparison') {
          try {
            const comparisonResponse = await axios.get(`/api/admin/reports/comparison?period=${selectedPeriod.value}&comparison_period=${comparisonPeriod.value}`);
            comparisonData.value = comparisonResponse.data || {
              currentPeriod: {},
              previousPeriod: {}
            };
          } catch (error) {
            console.error('Karşılaştırma verisi alınırken hata:', error);
            // Demo verileri yükle
            comparisonData.value = {
              currentPeriod: {
                totalIncome: 25600,
                totalReservations: 156,
                averageSale: 1650,
                conversionRate: 8.5
              },
              previousPeriod: {
                totalIncome: 22800,
                totalReservations: 142,
                averageSale: 1600,
                conversionRate: 7.8
              }
            };
          }
        }
        
      } catch (error) {
        console.error('Rapor verileri alınırken hata oluştu:', error);
        loadError.value = 'Veriler yüklenirken bir hata oluştu. Lütfen daha sonra tekrar deneyin.';
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
        
        const response = await axios.post(`/api/admin/reports/export`, params, {
          responseType: 'blob'
        });
        
        // İndirme işlemi
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', `rapor-${selectedReportType.value}-${selectedPeriod.value}.${format}`);
        document.body.appendChild(link);
        link.click();
        
        // Link elementini kaldır
        document.body.removeChild(link);
        
      } catch (error) {
        console.error('Rapor dışa aktarılırken hata oluştu:', error);
        alert('Rapor dışa aktarılırken bir hata oluştu. Lütfen daha sonra tekrar deneyin.');
      } finally {
        exportLoading.value = false;
      }
    };

    const formatPrice = (price) => {
      if (price === undefined || price === null) return '₺0';
      return new Intl.NumberFormat('tr-TR', { 
        style: 'currency', 
        currency: 'TRY',
        maximumFractionDigits: 0
      }).format(price);
    };

    const getTrendText = (trend) => {
      switch (trend) {
        case 'up':
          return 'Yükseliyor';
        case 'down':
          return 'Düşüyor';
        default:
          return 'Sabit';
      }
    };

    const getTrendClass = (trend) => {
      switch (trend) {
        case 'up':
          return 'text-green-500';
        case 'down':
          return 'text-red-500';
        default:
          return 'text-gray-500';
      }
    };

    const getTrendIcon = (trend) => {
      switch (trend) {
        case 'up':
          return 'M5 10l7-7m0 0l7 7m-7-7v18';
        case 'down':
          return 'M19 14l-7 7m0 0l-7-7m7 7V3';
        default:
          return 'M5 12h14';
      }
    };

    const getGenderName = (gender) => {
      switch (gender) {
        case 'male':
          return 'Erkek';
        case 'female':
          return 'Kadın';
        default:
          return 'Diğer';
      }
    };

    // Gender chart data transformer
    const getGenderChartData = () => {
      if (!userDemographics.value.genderDistribution || Object.keys(userDemographics.value.genderDistribution).length === 0) {
        return { 
          labels: ['Veri yok'], 
          datasets: [{ 
            label: 'Kullanıcı Sayısı', 
            data: [0],
            backgroundColor: ['rgba(200, 200, 200, 0.5)'] 
          }] 
        };
      }
      
      const genderData = userDemographics.value.genderDistribution;
      const labels = Object.keys(genderData).map(key => getGenderName(key));
      const data = Object.values(genderData);
      
      return {
        labels,
        datasets: [{
          label: 'Kullanıcı Sayısı',
          data,
          backgroundColor: [
            'rgba(54, 162, 235, 0.8)',
            'rgba(255, 99, 132, 0.8)',
            'rgba(255, 206, 86, 0.8)'
          ],
          borderWidth: 1
        }]
      };
    };

    // Age groups chart data transformer
    const getAgeGroupsChartData = () => {
      if (!userDemographics.value.ageGroups || !userDemographics.value.ageGroups.length) {
        return { 
          labels: ['Veri yok'], 
          datasets: [{ 
            label: 'Kullanıcı Sayısı', 
            data: [0],
            backgroundColor: ['rgba(200, 200, 200, 0.5)'] 
          }] 
        };
      }
      
      return {
        labels: userDemographics.value.ageGroups.map(item => item.age_group),
        datasets: [{
          label: 'Kullanıcı Sayısı',
          data: userDemographics.value.ageGroups.map(item => item.count),
          backgroundColor: 'rgba(75, 192, 192, 0.7)',
          borderColor: 'rgba(75, 192, 192, 1)',
          borderWidth: 1
        }]
      };
    };

    // Participant distribution chart data transformer
    const getParticipantChartData = () => {
      if (!participantStats.value.participantDistribution || !participantStats.value.participantDistribution.length) {
        return { 
          labels: ['Veri yok'], 
          datasets: [{ 
            label: 'Rezervasyon Sayısı', 
            data: [0],
            backgroundColor: ['rgba(200, 200, 200, 0.5)'] 
          }] 
        };
      }
      
      return {
        labels: participantStats.value.participantDistribution.map(item => `${item.participant_count} Kişi`),
        datasets: [{
          label: 'Rezervasyon Sayısı',
          data: participantStats.value.participantDistribution.map(item => item.count),
          backgroundColor: 'rgba(153, 102, 255, 0.7)',
          borderColor: 'rgba(153, 102, 255, 1)',
          borderWidth: 1
        }]
      };
    };

    // İlk veri yüklemesi
    onMounted(() => {
      fetchReportData();
    });

    return {
      loading,
      loadError,
      exportLoading,
      selectedPeriod,
      selectedReportType,
      comparisonPeriod,
      customStartDate,
      customEndDate,
      currentReportComponent,
      summary,
      monthlyIncome,
      topTours,
      userDemographics,
      participantStats,
      occupancyRate,
      occupancyRateChange,
      destinationData,
      comparisonData,
      chartOptions,
      demographicChartOptions,
      pieChartOptions,
      fetchReportData,
      exportReport,
      formatPrice,
      getTrendText,
      getTrendClass,
      getTrendIcon,
      getGenderName,
      getGenderChartData,
      getAgeGroupsChartData,
      getParticipantChartData
    };
  }
});
</script> 