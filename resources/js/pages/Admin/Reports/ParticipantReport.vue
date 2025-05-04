<template>
  <div>
    <!-- Katılımcı Dağılımı Grafikleri -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Cinsiyet Dağılımı</h3>
        <div class="h-80">
          <GenderDistributionChart 
            :chart-data="formatGenderData" 
            :chart-options="chartOptions"
          />
        </div>
      </div>
      
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Yaş Dağılımı</h3>
        <div class="h-80">
          <AgeDistributionChart 
            :chart-data="formatAgeData" 
            :chart-options="chartOptions"
          />
        </div>
      </div>
    </div>
    
    <!-- Aylık Katılımcı Grafiği -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
      <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Aylık Katılımcı İstatistikleri</h3>
      <div class="h-80">
        <MonthlyParticipantsChart 
          :chart-data="participantStats.monthlyParticipants" 
          :chart-options="chartOptions"
        />
      </div>
    </div>
    
    <!-- Demografik Bilgiler -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Katılımcı İstatistikleri</h3>
        <div class="space-y-6">
          <div>
            <div class="flex justify-between items-center mb-1">
              <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Toplam Katılımcı</span>
              <span class="text-sm font-medium text-gray-900 dark:text-white">{{ formatNumber(participantStats.totalParticipants) }}</span>
            </div>
            <div class="flex justify-between items-center mb-1">
              <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Ortalama Yaş</span>
              <span class="text-sm font-medium text-gray-900 dark:text-white">{{ userDemographics.averageAge }}
                <span :class="getDeltaClass(userDemographics.ageDelta)" class="ml-1 text-xs">
                  {{ getDeltaPrefix(userDemographics.ageDelta) }}{{ Math.abs(userDemographics.ageDelta) }}
                </span>
              </span>
            </div>
            <div class="flex justify-between items-center mb-1">
              <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Tekrar Katılım Oranı</span>
              <span class="text-sm font-medium text-gray-900 dark:text-white">{{ participantStats.repeatRate }}%
                <span :class="getDeltaClass(participantStats.repeatDelta)" class="ml-1 text-xs">
                  {{ getDeltaPrefix(participantStats.repeatDelta) }}{{ Math.abs(participantStats.repeatDelta) }}%
                </span>
              </span>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Doluluk Oranı</span>
              <span class="text-sm font-medium text-gray-900 dark:text-white">{{ participantStats.occupancyRate }}%
                <span :class="getDeltaClass(participantStats.occupancyDelta)" class="ml-1 text-xs">
                  {{ getDeltaPrefix(participantStats.occupancyDelta) }}{{ Math.abs(participantStats.occupancyDelta) }}%
                </span>
              </span>
            </div>
          </div>
          
          <div>
            <h4 class="text-md font-medium text-gray-700 dark:text-gray-300 mb-3">Yaş Grupları</h4>
            <div class="space-y-4">
              <div v-for="(group, index) in userDemographics.ageGroups" :key="index" class="flex items-center">
                <span class="text-sm font-medium text-gray-600 dark:text-gray-300 w-16">{{ group.range }}</span>
                <div class="flex-grow h-2 bg-gray-200 dark:bg-gray-700 rounded-full mx-2">
                  <div class="h-full rounded-full bg-indigo-500 dark:bg-indigo-400" :style="{ width: `${group.percentage}%` }"></div>
                </div>
                <span class="text-sm text-gray-600 dark:text-gray-300 min-w-[40px] text-right">{{ group.percentage }}%</span>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Cinsiyet Dağılımı</h3>
        <div class="flex justify-center items-center h-60">
          <div class="grid grid-cols-3 gap-4 w-full">
            <div v-for="(value, gender) in userDemographics.genderDistribution" :key="gender" class="flex flex-col items-center justify-center">
              <div class="w-20 h-20 rounded-full flex items-center justify-center" :class="getGenderColor(gender)">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path v-if="gender === 'male'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                  <path v-else-if="gender === 'female'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                  <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
              </div>
              <span class="mt-2 text-sm font-medium text-gray-900 dark:text-white">{{ getGenderName(gender) }}</span>
              <span class="text-xl font-bold text-gray-900 dark:text-white">{{ value }}%</span>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Turlara Göre Katılımcı Dağılımı -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
      <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Turlara Göre Katılımcı Dağılımı</h3>
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
          <thead>
            <tr>
              <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tur</th>
              <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Toplam Katılımcı</th>
              <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Doluluk Oranı</th>
              <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ortalama Yaş</th>
              <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Değişim</th>
            </tr>
          </thead>
          <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
            <tr v-for="tour in participantStats.tourParticipants" :key="tour.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
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
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ formatNumber(tour.participants) }}</td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="w-32 bg-gray-200 dark:bg-gray-700 rounded-full h-2.5 mr-2">
                    <div class="bg-blue-600 dark:bg-blue-500 h-2.5 rounded-full" :style="{ width: `${tour.occupancyRate}%` }"></div>
                  </div>
                  <span class="text-sm text-gray-600 dark:text-gray-400">{{ tour.occupancyRate }}%</span>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ tour.averageAge }}</td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full" :class="getDeltaClass(tour.delta)">
                  {{ getDeltaPrefix(tour.delta) }}{{ Math.abs(tour.delta) }}%
                </span>
              </td>
            </tr>
            <tr v-if="!participantStats.tourParticipants || participantStats.tourParticipants.length === 0">
              <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">Veri bulunamadı</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
import { defineComponent, computed } from 'vue';
import GenderDistributionChart from '@/components/Admin/Charts/GenderDistributionChart.vue';
import AgeDistributionChart from '@/components/Admin/Charts/AgeDistributionChart.vue';
import MonthlyParticipantsChart from '@/components/Admin/Charts/MonthlyParticipantsChart.vue';

export default defineComponent({
  name: 'ParticipantReport',
  components: {
    GenderDistributionChart,
    AgeDistributionChart,
    MonthlyParticipantsChart
  },
  props: {
    participantStats: {
      type: Object,
      required: true
    },
    userDemographics: {
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
    },
    getGenderName: {
      type: Function,
      required: true
    }
  },
  setup(props) {
    // Cinsiyet dağılımı verilerini grafik için formatla
    const formatGenderData = computed(() => {
      const data = props.userDemographics.genderDistribution || {};
      
      return {
        labels: Object.keys(data).map(gender => props.getGenderName(gender)),
        datasets: [{
          label: 'Cinsiyet Dağılımı',
          data: Object.values(data),
          backgroundColor: [
            'rgba(54, 162, 235, 0.7)',
            'rgba(255, 99, 132, 0.7)',
            'rgba(153, 102, 255, 0.7)'
          ],
          borderColor: [
            'rgba(54, 162, 235, 1)',
            'rgba(255, 99, 132, 1)',
            'rgba(153, 102, 255, 1)'
          ],
          borderWidth: 1
        }]
      };
    });
    
    // Yaş dağılımı verilerini grafik için formatla
    const formatAgeData = computed(() => {
      const ageGroups = props.userDemographics.ageGroups || [];
      
      return {
        labels: ageGroups.map(group => group.range),
        datasets: [{
          label: 'Yaş Dağılımı',
          data: ageGroups.map(group => group.percentage),
          backgroundColor: 'rgba(75, 192, 192, 0.7)',
          borderColor: 'rgba(75, 192, 192, 1)',
          borderWidth: 1
        }]
      };
    });
    
    // Cinsiyet arka plan renkleri
    const getGenderColor = (gender) => {
      if (gender === 'male') return 'bg-blue-500 dark:bg-blue-600';
      if (gender === 'female') return 'bg-pink-500 dark:bg-pink-600';
      return 'bg-purple-500 dark:bg-purple-600';
    };
    
    // Artış/Azalış değeri için renk sınıfı
    const getDeltaClass = (delta) => {
      if (!delta) return 'text-gray-500 dark:text-gray-400';
      return delta > 0 
        ? 'text-green-600 dark:text-green-400 bg-green-100 dark:bg-green-900' 
        : 'text-red-600 dark:text-red-400 bg-red-100 dark:bg-red-900';
    };
    
    // Artış/Azalış için prefix
    const getDeltaPrefix = (delta) => {
      if (!delta) return '';
      return delta > 0 ? '+' : '';
    };
    
    return {
      formatGenderData,
      formatAgeData,
      getGenderColor,
      getDeltaClass,
      getDeltaPrefix
    };
  }
});
</script> 