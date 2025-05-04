<template>
  <AdminLayout>
    <div class="container mx-auto">
      <h1 class="text-2xl font-semibold text-gray-900 dark:text-white mb-6">
        Admin Dashboard
      </h1>
      
      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 transition-all duration-300">
          <div class="flex items-center justify-between">
            <div>
              <h2 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Toplam Tur</h2>
              <p class="text-2xl font-bold text-gray-900 dark:text-white">24</p>
            </div>
            <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900/50 transition-colors">
              <svg class="w-6 h-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
              </svg>
            </div>
          </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 transition-all duration-300">
          <div class="flex items-center justify-between">
            <div>
              <h2 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Toplam Rezervasyon</h2>
              <p class="text-2xl font-bold text-gray-900 dark:text-white">156</p>
            </div>
            <div class="p-3 rounded-full bg-green-100 dark:bg-green-900/50 transition-colors">
              <svg class="w-6 h-6 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
              </svg>
            </div>
          </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 transition-all duration-300">
          <div class="flex items-center justify-between">
            <div>
              <h2 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Toplam Kullanıcı</h2>
              <p class="text-2xl font-bold text-gray-900 dark:text-white">543</p>
            </div>
            <div class="p-3 rounded-full bg-indigo-100 dark:bg-indigo-900/50 transition-colors">
              <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
              </svg>
            </div>
          </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 transition-all duration-300">
          <div class="flex items-center justify-between">
            <div>
              <h2 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Toplam Gelir</h2>
              <p class="text-2xl font-bold text-gray-900 dark:text-white">₺25,600</p>
            </div>
            <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-900/50 transition-colors">
              <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Recent Activity & Charts Section -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 md:gap-8 mb-8">
        <!-- Recent Reservations -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 transition-all duration-300">
          <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Son Rezervasyonlar</h2>
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead>
                <tr class="text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                  <th class="px-4 py-3">Müşteri</th>
                  <th class="px-4 py-3">Tur</th>
                  <th class="px-4 py-3">Tarih</th>
                  <th class="px-4 py-3">Durum</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                <tr v-for="(reservation, index) in recentReservations" :key="index" class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                  <td class="px-4 py-3">{{ reservation.customer }}</td>
                  <td class="px-4 py-3">{{ reservation.tour }}</td>
                  <td class="px-4 py-3">{{ reservation.date }}</td>
                  <td class="px-4 py-3">
                    <span 
                      class="px-2 py-1 text-xs rounded-full"
                      :class="{
                        'bg-green-100 text-green-800 dark:bg-green-900/60 dark:text-green-300': reservation.status === 'confirmed',
                        'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/60 dark:text-yellow-300': reservation.status === 'pending',
                        'bg-red-100 text-red-800 dark:bg-red-900/60 dark:text-red-300': reservation.status === 'cancelled'
                      }"
                    >
                      {{ getStatusText(reservation.status) }}
                    </span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="mt-4 text-right">
            <router-link to="/admin/reservations" class="text-sm font-medium text-primary-600 dark:text-primary-400 hover:underline">
              Tüm rezervasyonları görüntüle →
            </router-link>
          </div>
        </div>
        
        <!-- Monthly Income Chart -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 transition-all duration-300">
          <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Aylık Gelir</h2>
          <div class="h-64">
            <MonthlyIncomeChart :chart-data="monthlyIncomeData" :options="chartOptions" />
          </div>
          <div class="mt-4 text-right">
            <router-link to="/admin/reports" class="text-sm font-medium text-primary-600 dark:text-primary-400 hover:underline">
              Tüm raporları görüntüle →
            </router-link>
          </div>
        </div>
      </div>
      
      <!-- Additional Charts Section -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 md:gap-8 mb-8">
        <!-- Tour Categories Chart -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 transition-all duration-300">
          <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Tur Kategorileri</h2>
          <div class="h-64">
            <TourCategoriesChart :chart-data="tourCategoriesData" :options="pieChartOptions" />
          </div>
        </div>
        
        <!-- Reservation Trends Chart -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 transition-all duration-300">
          <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Rezervasyon Trendleri</h2>
          <div class="h-64">
            <ReservationTrendsChart :chart-data="reservationTrendsData" :options="chartOptions" />
          </div>
        </div>
      </div>
      
      <!-- Quick Actions -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-8 transition-all duration-300">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Hızlı İşlemler</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
          <router-link to="/admin/tours/create" class="flex items-center p-4 bg-blue-50 dark:bg-blue-900/30 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/50 transition-colors">
            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            <span class="text-sm font-medium text-blue-700 dark:text-blue-300">Tur Ekle</span>
          </router-link>
          <router-link to="/admin/reservations/create" class="flex items-center p-4 bg-green-50 dark:bg-green-900/30 rounded-lg hover:bg-green-100 dark:hover:bg-green-900/50 transition-colors">
            <svg class="w-6 h-6 text-green-600 dark:text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            <span class="text-sm font-medium text-green-700 dark:text-green-300">Rezervasyon Oluştur</span>
          </router-link>
          <router-link to="/admin/reports" class="flex items-center p-4 bg-purple-50 dark:bg-purple-900/30 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-900/50 transition-colors">
            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            <span class="text-sm font-medium text-purple-700 dark:text-purple-300">Rapor Oluştur</span>
          </router-link>
          <router-link to="/admin/settings" class="flex items-center p-4 bg-yellow-50 dark:bg-yellow-900/30 rounded-lg hover:bg-yellow-100 dark:hover:bg-yellow-900/50 transition-colors">
            <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            <span class="text-sm font-medium text-yellow-700 dark:text-yellow-300">Ayarlar</span>
          </router-link>
        </div>
      </div>
      
      <!-- Recent Activity Log -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 transition-all duration-300">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Son Aktiviteler</h2>
        <ul class="space-y-3">
          <li v-for="(activity, index) in recentActivities" :key="index" class="flex items-start space-x-3 py-2 border-b border-gray-100 dark:border-gray-700 last:border-0">
            <div class="flex-shrink-0">
              <span class="inline-flex items-center justify-center h-8 w-8 rounded-full" :class="getActivityIconBg(activity.type)">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" :class="getActivityIconColor(activity.type)">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="getActivityIcon(activity.type)"></path>
                </svg>
              </span>
            </div>
            <div class="min-w-0 flex-1">
              <p class="text-sm text-gray-800 dark:text-gray-200">
                <span class="font-medium">{{ activity.user }}</span> {{ activity.action }}
              </p>
              <p class="text-xs text-gray-500 dark:text-gray-400">
                {{ activity.time }}
              </p>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { Chart as ChartJS, CategoryScale, LinearScale, PointElement, LineElement, BarElement, ArcElement, Title, Tooltip, Legend } from 'chart.js';
import MonthlyIncomeChart from '@/components/Admin/Charts/MonthlyIncomeChart.vue';
import TourCategoriesChart from '@/components/Admin/Charts/TourCategoriesChart.vue';
import ReservationTrendsChart from '@/components/Admin/Charts/ReservationTrendsChart.vue';

// Chart.js registerları
ChartJS.register(CategoryScale, LinearScale, PointElement, LineElement, BarElement, ArcElement, Title, Tooltip, Legend);

// Tema renkleri
const getLightThemeColors = () => {
  return {
    primary: '#3B82F6',
    secondary: '#10B981',
    warning: '#FBBF24',
    danger: '#EF4444',
    info: '#6366F1',
    success: '#22C55E',
    background: '#FFFFFF',
    backgroundSecondary: '#F9FAFB',
    text: '#111827',
    textSecondary: '#4B5563',
    border: '#E5E7EB',
    accent1: '#8B5CF6',
    accent2: '#EC4899',
    accent3: '#06B6D4'
  };
};

const getDarkThemeColors = () => {
  return {
    primary: '#60A5FA',
    secondary: '#34D399',
    warning: '#FBBF24',
    danger: '#F87171',
    info: '#A5B4FC',
    success: '#4ADE80',
    background: '#1F2937',
    backgroundSecondary: '#111827',
    text: '#F9FAFB',
    textSecondary: '#D1D5DB',
    border: '#374151',
    accent1: '#A78BFA',
    accent2: '#F472B6',
    accent3: '#22D3EE'
  };
};

// isDarkMode değerini otomatik al
const isDarkMode = computed(() => {
  if (typeof window !== 'undefined') {
    return document.documentElement.classList.contains('dark');
  }
  return false;
});

// Tema renklerini belirle
const themeColors = computed(() => {
  return isDarkMode.value ? getDarkThemeColors() : getLightThemeColors();
});

// Aylık gelir verisi
const monthlyIncomeData = ref({
  labels: ['Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran', 'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık'],
  datasets: [
    {
      label: 'Aylık Gelir (₺)',
      data: [3200, 5400, 8700, 12600, 9800, 14500, 18600, 22400, 17800, 15600, 19200, 25600],
      backgroundColor: computed(() => themeColors.value.primary + '20'),
      borderColor: computed(() => themeColors.value.primary),
      borderWidth: 2,
      tension: 0.4,
      fill: true,
    }
  ]
});

// Tur kategorileri verisi
const tourCategoriesData = ref({
  labels: ['Kültür Turları', 'Doğa Turları', 'Deniz Turları', 'Lüks Turlar', 'Aile Turları'],
  datasets: [
    {
      label: 'Tur Kategorileri',
      data: [35, 25, 20, 10, 10],
      backgroundColor: [
        '#FF6384', // Parlak pembe
        '#36A2EB', // Mavi
        '#FFCE56', // Sarı
        '#4BC0C0', // Turkuaz
        '#9966FF', // Mor
      ],
      borderColor: [
        '#FF5370', // Pembe border
        '#3498db', // Mavi border
        '#f1c40f', // Sarı border
        '#1abc9c', // Turkuaz border
        '#8e44ad', // Mor border
      ],
      borderWidth: 2,
      hoverBackgroundColor: [
        '#FF8CB1', // Pembe hover
        '#7FC4FB', // Mavi hover
        '#FFE28C', // Sarı hover
        '#7FE0E0', // Turkuaz hover
        '#C4A6FF', // Mor hover
      ],
      hoverBorderColor: [
        '#FF5370', // Pembe border hover
        '#3498db', // Mavi border hover
        '#f1c40f', // Sarı border hover 
        '#1abc9c', // Turkuaz border hover
        '#8e44ad', // Mor border hover
      ],
      hoverBorderWidth: 3,
    }
  ]
});

// Rezervasyon trendleri verisi
const reservationTrendsData = ref({
  labels: ['Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran', 'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık'],
  datasets: [
    {
      label: 'Online Rezervasyonlar',
      data: [25, 32, 48, 57, 42, 65, 78, 91, 68, 59, 72, 84],
      backgroundColor: computed(() => themeColors.value.primary + '80'),
      borderColor: computed(() => themeColors.value.primary),
      borderWidth: 2,
    },
    {
      label: 'Telefon Rezervasyonlar',
      data: [18, 24, 32, 38, 29, 42, 51, 43, 36, 31, 38, 41],
      backgroundColor: computed(() => themeColors.value.accent1 + '80'),
      borderColor: computed(() => themeColors.value.accent1),
      borderWidth: 2,
    }
  ]
});

// Çizgi ve bar grafik seçenekleri
const chartOptions = ref({
  responsive: true,
  maintainAspectRatio: false,
  scales: {
    y: {
      beginAtZero: true,
      grid: {
        color: computed(() => themeColors.value.border + '40'),
      },
      ticks: {
        color: computed(() => themeColors.value.text + '80'),
      }
    },
    x: {
      grid: {
        display: false,
      },
      ticks: {
        color: computed(() => themeColors.value.text + '80'),
      }
    }
  },
  plugins: {
    legend: {
      labels: {
        color: computed(() => themeColors.value.text),
        font: {
          size: 12
        }
      }
    }
  }
});

// Pasta grafik seçenekleri
const pieChartOptions = ref({
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      position: 'bottom',
      labels: {
        color: computed(() => themeColors.value.text),
        font: {
          size: 13,
          weight: 'bold'
        },
        padding: 20,
        usePointStyle: true,
        pointStyle: 'circle'
      }
    },
    tooltip: {
      callbacks: {
        label: function(context) {
          let label = context.label || '';
          let value = context.raw || 0;
          let total = context.dataset.data.reduce((acc, val) => acc + val, 0);
          let percentage = Math.round((value / total) * 100);
          return `${label}: ${percentage}% (${value})`;
        }
      },
      backgroundColor: 'rgba(0, 0, 0, 0.7)',
      titleFont: {
        size: 14
      },
      bodyFont: {
        size: 13
      },
      padding: 10,
      cornerRadius: 6,
      displayColors: true
    }
  },
  cutout: '40%', // Daha iyi bir doughnut görünümü için
  animation: {
    animateScale: true,
    animateRotate: true
  }
});

// Son rezervasyonlar verisi (örnek veri)
const recentReservations = ref([
  { customer: 'Ahmet Yılmaz', tour: 'Kapadokya Turu', date: '15 Nisan 2025', status: 'confirmed' },
  { customer: 'Ayşe Demir', tour: 'İstanbul Boğaz Turu', date: '18 Nisan 2025', status: 'pending' },
  { customer: 'Mehmet Öz', tour: 'Pamukkale Turu', date: '20 Nisan 2025', status: 'confirmed' },
  { customer: 'Zeynep Kaya', tour: 'Efes Antik Kent Turu', date: '22 Nisan 2025', status: 'cancelled' },
]);

// Son aktiviteler (örnek veri)
const recentActivities = ref([
  { user: 'Admin', action: 'yeni bir tur ekledi: Kapadokya Balon Turu', time: '10 dakika önce', type: 'tour' },
  { user: 'Sistem', action: 'otomatik yedekleme tamamlandı', time: '1 saat önce', type: 'system' },
  { user: 'Admin', action: 'bir rezervasyonu onayladı: #34562', time: '3 saat önce', type: 'reservation' },
  { user: 'Mehmet Öz', action: 'yeni bir rezervasyon oluşturdu', time: '5 saat önce', type: 'user' },
  { user: 'Admin', action: 'fiyat güncellemesi yaptı: İstanbul Turu', time: '6 saat önce', type: 'price' },
]);

// Durumların Türkçe karşılıkları
const getStatusText = (status) => {
  const statusMap = {
    'confirmed': 'Onaylandı',
    'pending': 'Beklemede',
    'cancelled': 'İptal Edildi'
  };
  return statusMap[status] || status;
};

// Aktivite icon renk ve arka plan sınıfları
const getActivityIconBg = (type) => {
  const bgMap = {
    'tour': 'bg-blue-100 dark:bg-blue-900/30',
    'reservation': 'bg-green-100 dark:bg-green-900/30',
    'user': 'bg-purple-100 dark:bg-purple-900/30',
    'system': 'bg-gray-100 dark:bg-gray-900/30',
    'price': 'bg-yellow-100 dark:bg-yellow-900/30'
  };
  return bgMap[type] || 'bg-gray-100 dark:bg-gray-800';
};

const getActivityIconColor = (type) => {
  const colorMap = {
    'tour': 'text-blue-600 dark:text-blue-400',
    'reservation': 'text-green-600 dark:text-green-400',
    'user': 'text-purple-600 dark:text-purple-400',
    'system': 'text-gray-600 dark:text-gray-400',
    'price': 'text-yellow-600 dark:text-yellow-400'
  };
  return colorMap[type] || 'text-gray-600 dark:text-gray-400';
};

const getActivityIcon = (type) => {
  const iconMap = {
    'tour': 'M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7',
    'reservation': 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01',
    'user': 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
    'system': 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z',
    'price': 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
  };
  return iconMap[type] || 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z';
};

// Dark mode değiştiğinde grafik renklerini güncelle
onMounted(() => {
  // Dark mode sınıf değişikliklerini izle
  const darkModeObserver = new MutationObserver(() => {
    // Tema değişikliğinde grafik renklerini güncelle
    monthlyIncomeData.value = {...monthlyIncomeData.value};
    tourCategoriesData.value = {...tourCategoriesData.value};
    reservationTrendsData.value = {...reservationTrendsData.value};
  });
  
  darkModeObserver.observe(document.documentElement, {
    attributes: true,
    attributeFilter: ['class']
  });
  
  // Özel tema değişikliği eventini dinle
  window.addEventListener('theme-changed', () => {
    console.log('Dashboard: Tema değişikliği algılandı, grafikler güncelleniyor...');
    // Grafikleri güncelle
    monthlyIncomeData.value = {...monthlyIncomeData.value};
    tourCategoriesData.value = {...tourCategoriesData.value};
    reservationTrendsData.value = {...reservationTrendsData.value};
  });
  
  // Component unmount olduğunda temizlik yap
  return () => {
    darkModeObserver.disconnect();
    window.removeEventListener('theme-changed', () => {});
  };
});
</script> 