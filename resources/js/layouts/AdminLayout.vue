<template>
  <div class="flex flex-col min-h-screen admin-layout">
    <AdminSidebar :sidebarOpen="sidebarOpen" @close-sidebar="closeSidebar" />
    
    <div 
      class="flex-1 transition-all duration-300"
      :class="{
        'lg:ml-64 md:ml-16': sidebarOpen,
        'ml-0': !sidebarOpen
      }"
    >
      <AdminHeader 
        :sidebarOpen="sidebarOpen" 
        @toggle-sidebar="toggleSidebar" 
        :pageTitle="pageTitle" 
      />
      
      <main class="p-4 md:p-6 admin-content">
        <slot />
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import AdminSidebar from '@/components/Admin/AdminSidebar.vue';
import AdminHeader from '@/components/Admin/AdminHeader.vue';

const props = defineProps({
  pageTitle: {
    type: String,
    default: 'Dashboard'
  }
});

// Sidebar state
const sidebarOpen = ref(true);

// Toggle sidebar
const toggleSidebar = () => {
  sidebarOpen.value = !sidebarOpen.value;
};

// Close sidebar (generally used from mobile)
const closeSidebar = () => {
  sidebarOpen.value = false;
};

// Handle resize to manage sidebar on window resize
const handleResize = () => {
  if (window.innerWidth < 1024) {
    // Mobil görünümde sidebar otomatik kapansın
    sidebarOpen.value = false;
  } else {
    // Desktop görünümde sidebar açık olsun
    sidebarOpen.value = true;
  }
};

onMounted(() => {
  document.title = 'Admin Panel - TravelApp';
  
  // Initial check
  handleResize();
  
  // Add event listener for window resize
  window.addEventListener('resize', handleResize);
});

onUnmounted(() => {
  // Remove event listener when component is unmounted
  window.removeEventListener('resize', handleResize);
});
</script> 