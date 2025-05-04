<template>
  <div class="container mx-auto px-4 py-6">
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Raporlar</h1>
      <p class="text-gray-500 dark:text-gray-400">Tur satışları ve katılımcı analizlerinizi görüntüleyin</p>
    </div>

    <!-- Filtre Seçenekleri -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 mb-6">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tarih Aralığı</label>
          <select v-model="dateRange" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
            <option value="7">Son 7 Gün</option>
            <option value="30">Son 30 Gün</option>
            <option value="90">Son 90 Gün</option>
            <option value="180">Son 180 Gün</option>
            <option value="365">Son 1 Yıl</option>
            <option value="custom">Özel Aralık</option>
          </select>
        </div>
        
        <div v-if="dateRange === 'custom'" class="grid grid-cols-2 gap-2">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Başlangıç</label>
            <input type="date" v-model="startDate" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Bitiş</label>
            <input type="date" v-model="endDate" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
          </div>
        </div>
        
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kategori</label>
          <select v-model="selectedCategory" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
            <option value="">Tüm Kategoriler</option>
            <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}</option>
          </select>
        </div>
        
        <div class="flex items-end">
          <button @click="fetchReportData" class="w-full px-5 py-2.5 text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:ring-primary-300 rounded-lg dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
            <svg v-if="loading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Raporu Güncelle
          </button>
        </div>
      </div>
    </div>
    
    <!-- Özet Kartlar -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Toplam Satış</h3>
          <span class="inline-flex items-center justify-center p-2 bg-blue-100 dark:bg-blue-900 rounded-md">
            <svg class="w-6 h-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </span>
        </div>
        <p class="mt-4 text-2xl font-bold text-gray-900 dark:text-white">{{ formatPrice(reportData.totalSales || 0) }}</p>
        <div class="mt-2 flex items-center">
          <span :class="getDeltaClass(reportData.salesDelta)">
            {{ getDeltaPrefix(reportData.salesDelta) }}{{ Math.abs(reportData.salesDelta || 0) }}%
          </span>
          <span class="text-gray-500 dark:text-gray-400 text-sm ml-2">geçen döneme göre</span>
        </div>
      </div>
      
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Rezervasyon</h3>
          <span class="inline-flex items-center justify-center p-2 bg-emerald-100 dark:bg-emerald-900 rounded-md">
            <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
          </span>
        </div>
        <p class="mt-4 text-2xl font-bold text-gray-900 dark:text-white">{{ reportData.totalReservations || 0 }}</p>
        <div class="mt-2 flex items-center">
          <span :class="getDeltaClass(reportData.reservationsDelta)">
            {{ getDeltaPrefix(reportData.reservationsDelta) }}{{ Math.abs(reportData.reservationsDelta || 0) }}%
          </span>
          <span class="text-gray-500 dark:text-gray-400 text-sm ml-2">geçen döneme göre</span>
        </div>
      </div>
      
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Ortalama Satış</h3>
          <span class="inline-flex items-center justify-center p-2 bg-purple-100 dark:bg-purple-900 rounded-md">
            <svg class="w-6 h-6 text-purple-600 dark:text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
          </span>
        </div>
        <p class="mt-4 text-2xl font-bold text-gray-900 dark:text-white">{{ formatPrice(reportData.averageSale || 0) }}</p>
        <div class="mt-2 flex items-center">
          <span :class="getDeltaClass(reportData.averageSaleDelta)">
            {{ getDeltaPrefix(reportData.averageSaleDelta) }}{{ Math.abs(reportData.averageSaleDelta || 0) }}%
          </span>
          <span class="text-gray-500 dark:text-gray-400 text-sm ml-2">geçen döneme göre</span>
        </div>
      </div>
      
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Katılımcı</h3>
          <span class="inline-flex items-center justify-center p-2 bg-amber-100 dark:bg-amber-900 rounded-md">
            <svg class="w-6 h-6 text-amber-600 dark:text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
          </span>
        </div>
        <p class="mt-4 text-2xl font-bold text-gray-900 dark:text-white">{{ formatNumber(participantStats.totalParticipants || 0) }}</p>
        <div class="mt-2 flex items-center">
          <span :class="getDeltaClass(participantStats.participantsDelta)">
            {{ getDeltaPrefix(participantStats.participantsDelta) }}{{ Math.abs(participantStats.participantsDelta || 0) }}%
          </span>
          <span class="text-gray-500 dark:text-gray-400 text-sm ml-2">geçen döneme göre</span>
        </div>
      </div>
    </div>

    <!-- Sekme Menüsü -->
    <div class="border-b border-gray-200 dark:border-gray-700 mb-6">
      <ul class="flex flex-wrap -mb-px">
        <li class="mr-2">
          <a href="#" @click.prevent="activeTab = 'sales'" :class="[
            'inline-block p-4 rounded-t-lg border-b-2', 
            activeTab === 'sales' 
              ? 'text-primary-600 border-primary-600 dark:text-primary-500 dark:border-primary-500' 
              : 'border-transparent hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300'
          ]">
            Satış Raporu
          </a>
        </li>
        <li class="mr-2">
          <a href="#" @click.prevent="activeTab = 'participants'" :class="[
            'inline-block p-4 rounded-t-lg border-b-2', 
            activeTab === 'participants' 
              ? 'text-primary-600 border-primary-600 dark:text-primary-500 dark:border-primary-500' 
              : 'border-transparent hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300'
          ]">
            Katılımcı Raporu
          </a>
        </li>
      </ul>
    </div>

    <!-- Satış Raporu Sekmesi -->
    <SalesReport 
      v-if="activeTab === 'sales'" 
      :monthly-income="monthlyIncome"
      :top-tours="topTours"
      :chart-options="chartOptions"
      :format-price="formatPrice"
      :get-trend-text="getTrendText"
      :get-trend-class="getTrendClass"
      :get-trend-icon="getTrendIcon"
      v-show="!loading"
    />

    <!-- Katılımcı Raporu Sekmesi -->
    <ParticipantReport 
      v-if="activeTab === 'participants'" 
      :participant-stats="participantStats"
      :user-demographics="userDemographics"
      :chart-options="chartOptions"
      :format-number="formatNumber"
      :get-gender-name="getGenderName"
      v-show="!loading"
    />

    <!-- Yükleniyor Göstergesi -->
    <div v-if="loading" class="flex items-center justify-center h-64">
      <div class="text-center">
        <svg class="animate-spin h-10 w-10 text-primary-600 dark:text-primary-500 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <p class="mt-4 text-gray-600 dark:text-gray-400">Rapor yükleniyor...</p>
      </div>
    </div>

    <!-- Rapor Araçları -->
    <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg shadow-md p-4">
      <div class="flex flex-col sm:flex-row items-center justify-between">
        <div class="mb-4 sm:mb-0">
          <span class="text-sm text-gray-500 dark:text-gray-400">Son güncelleme: {{ lastUpdated }}</span>
        </div>
        <div class="flex space-x-2">
          <button @click="exportReport('pdf')" class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:ring-red-300 rounded-lg dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
            <svg class="w-4 h-4 mr-1 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            PDF
          </button>
          <button @click="exportReport('excel')" class="px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:ring-green-300 rounded-lg dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
            <svg class="w-4 h-4 mr-1 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Excel
          </button>
          <button @click="printReport" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 hover:bg-gray-300 focus:ring-4 focus:ring-gray-200 rounded-lg dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-white dark:focus:ring-gray-700">
            <svg class="w-4 h-4 mr-1 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            Yazdır
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { defineComponent, ref, computed, onMounted, watch } from 'vue';
import axios from 'axios';
import SalesReport from './SalesReport.vue';
import ParticipantReport from './ParticipantReport.vue';

export default defineComponent({
  name: 'MainReports',
  components: {
    SalesReport,
    ParticipantReport
  },
  setup() {
    const loading = ref(false);
    const activeTab = ref('sales');
    const dateRange = ref('30');
    const startDate = ref('');
    const endDate = ref('');
    const selectedCategory = ref('');
    const categories = ref([]);
    const lastUpdated = ref('');
    
    // Rapor verileri
    const reportData = ref({
      totalSales: 0,
      totalReservations: 0,
      averageSale: 0,
      salesDelta: 0,
      reservationsDelta: 0,
      averageSaleDelta: 0
    });
    
    const monthlyIncome = ref({
      labels: [],
      datasets: []
    });
    
    const topTours = ref([]);
    
    const userDemographics = ref({
      genderDistribution: {},
      ageGroups: [],
      averageAge: 0,
      ageDelta: 0
    });
    
    const participantStats = ref({
      totalParticipants: 0,
      participantsDelta: 0,
      repeatRate: 0,
      repeatDelta: 0,
      occupancyRate: 0,
      occupancyDelta: 0,
      monthlyParticipants: {
        labels: [],
        datasets: []
      },
      tourParticipants: []
    });
    
    // Grafik seçenekleri
    const chartOptions = ref({
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        y: {
          beginAtZero: true,
          grid: {
            display: true,
            drawBorder: false,
          }
        },
        x: {
          grid: {
            display: false,
            drawBorder: false,
          }
        }
      },
      plugins: {
        legend: {
          position: 'top',
          labels: {
            boxWidth: 10,
            usePointStyle: true
          }
        },
        tooltip: {
          enabled: true
        }
      }
    });
    
    // Rapor verilerini getir
    const fetchReportData = async () => {
      loading.value = true;
      
      try {
        // Filtre parametreleri
        const params = {
          date_range: dateRange.value,
          category_id: selectedCategory.value || undefined
        };
        
        if (dateRange.value === 'custom') {
          params.start_date = startDate.value;
          params.end_date = endDate.value;
        }
        
        // Paralel API istekleri
        const [summaryRes, monthlyIncomeRes, topToursRes, userDemographicsRes, participantStatsRes] = await Promise.all([
          axios.get('/api/admin/reports/summary', { params }),
          axios.get('/api/admin/reports/monthly-income', { params }),
          axios.get('/api/admin/reports/top-tours', { params }),
          axios.get('/api/admin/reports/user-demographics', { params }),
          axios.get('/api/admin/reports/participant-stats', { params })
        ]);
        
        // Verileri güncelle
        reportData.value = summaryRes.data;
        monthlyIncome.value = monthlyIncomeRes.data;
        topTours.value = topToursRes.data;
        userDemographics.value = userDemographicsRes.data;
        participantStats.value = participantStatsRes.data;
        
        // Son güncelleme zamanını ayarla
        lastUpdated.value = new Date().toLocaleString('tr-TR');
      } catch (error) {
        console.error('Rapor verileri alınırken hata oluştu:', error);
      } finally {
        loading.value = false;
      }
    };
    
    // Kategori listesini getir
    const fetchCategories = async () => {
      try {
        const response = await axios.get('/api/admin/categories');
        categories.value = response.data;
      } catch (error) {
        console.error('Kategoriler alınırken hata oluştu:', error);
      }
    };
    
    // Raporu dışa aktar
    const exportReport = async (format) => {
      loading.value = true;
      
      try {
        // Filtre parametreleri
        const params = {
          date_range: dateRange.value,
          category_id: selectedCategory.value || undefined,
          format: format,
          tab: activeTab.value
        };
        
        if (dateRange.value === 'custom') {
          params.start_date = startDate.value;
          params.end_date = endDate.value;
        }
        
        const response = await axios.get('/api/admin/reports/export', { 
          params,
          responseType: 'blob'
        });
        
        // Dosyayı indir
        const contentDisposition = response.headers['content-disposition'];
        let filename = 'rapor';
        
        if (contentDisposition) {
          const filenameMatch = contentDisposition.match(/filename="(.+)"/);
          if (filenameMatch.length === 2) {
            filename = filenameMatch[1];
          }
        }
        
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', filename);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
      } catch (error) {
        console.error('Rapor dışa aktarılırken hata oluştu:', error);
      } finally {
        loading.value = false;
      }
    };
    
    // Raporu yazdır
    const printReport = () => {
      window.print();
    };
    
    // Fiyat formatla
    const formatPrice = (price) => {
      return new Intl.NumberFormat('tr-TR', {
        style: 'currency',
        currency: 'TRY',
        minimumFractionDigits: 2
      }).format(price);
    };
    
    // Sayı formatla
    const formatNumber = (number) => {
      return new Intl.NumberFormat('tr-TR').format(number);
    };
    
    // Trend metni al
    const getTrendText = (trend) => {
      if (trend === 'up') return 'Yükseliyor';
      if (trend === 'down') return 'Düşüyor';
      return 'Sabit';
    };
    
    // Trend renk sınıfı al
    const getTrendClass = (trend) => {
      if (trend === 'up') return 'text-green-600 dark:text-green-400';
      if (trend === 'down') return 'text-red-600 dark:text-red-400';
      return 'text-gray-600 dark:text-gray-400';
    };
    
    // Trend ikon yolu al
    const getTrendIcon = (trend) => {
      if (trend === 'up') return 'M5 10l7-7m0 0l7 7m-7-7v18';
      if (trend === 'down') return 'M19 14l-7 7m0 0l-7-7m7 7V3';
      return 'M5 12h14';
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
    
    // Cinsiyet ismi al
    const getGenderName = (gender) => {
      const genderMap = {
        'male': 'Erkek',
        'female': 'Kadın',
        'other': 'Diğer'
      };
      
      return genderMap[gender] || gender;
    };
    
    // Sayfa yüklendiğinde ve filtreler değiştiğinde rapor verilerini getir
    onMounted(() => {
      fetchCategories();
      fetchReportData();
    });
    
    watch([dateRange, selectedCategory], () => {
      if (dateRange.value !== 'custom') {
        fetchReportData();
      }
    });
    
    return {
      loading,
      activeTab,
      dateRange,
      startDate,
      endDate,
      selectedCategory,
      categories,
      lastUpdated,
      reportData,
      monthlyIncome,
      topTours,
      userDemographics,
      participantStats,
      chartOptions,
      fetchReportData,
      exportReport,
      printReport,
      formatPrice,
      formatNumber,
      getTrendText,
      getTrendClass,
      getTrendIcon,
      getDeltaClass,
      getDeltaPrefix,
      getGenderName
    };
  }
});
</script>

<style>
@media print {
  body * {
    visibility: hidden;
  }
  .container, .container * {
    visibility: visible;
  }
  .container {
    position: absolute;
    left: 0;
    top: 0;
  }
  button, .border-b {
    display: none !important;
  }
}
</style> 