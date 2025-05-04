<template>
  <div class="w-full overflow-x-hidden">
    <Hero />
    <DiscountedTours />
    <WhyChooseUs />
    <Newsletter />
  </div>
</template>

<script setup>
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Autoplay, Navigation, Pagination } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import Hero from '@/components/Hero.vue';
import DiscountedTours from '@/components/DiscountedTours.vue';
import WhyChooseUs from '@/components/WhyChooseUs.vue';
import Newsletter from '@/components/Newsletter.vue';

const discountedTours = [
  {
    id: 1,
    title: 'Kapadokya Balon Turu',
    description: 'Eşsiz peri bacalarının üzerinde süzülürken güneşin doğuşunu izleyin.',
    image: 'https://images.pexels.com/photos/2325446/pexels-photo-2325446.jpeg?auto=compress&cs=tinysrgb&w=1200',
    price: '₺2,999',
    duration: '3 gün 2 gece',
    location: 'Kapadokya',
    reviews: 128,
    activity: 'Macera'
  },
  {
    id: 2,
    title: 'Pamukkale Turu',
    description: 'Beyaz travertenlerin eşsiz güzelliğini keşfedin.',
    image: 'https://images.pexels.com/photos/3894868/pexels-photo-3894868.jpeg?auto=compress&cs=tinysrgb&w=1200',
    price: '₺1,999',
    duration: '2 gün 1 gece',
    location: 'Denizli',
    reviews: 96,
    activity: 'Doğa'
  },
  {
    id: 3,
    title: 'Efes Antik Kenti Turu',
    description: 'Antik kentin büyüleyici atmosferinde tarihe yolculuk yapın.',
    image: 'https://images.pexels.com/photos/11421498/pexels-photo-11421498.jpeg?auto=compress&cs=tinysrgb&w=1200',
    price: '₺1,499',
    duration: '1 gün',
    location: 'İzmir',
    reviews: 64,
    activity: 'Tarihi'
  }
];
</script>

<style>
.swiper-button-next,
.swiper-button-prev {
  color: theme('colors.primary.600') !important;
}

.swiper-pagination-bullet-active {
  background: theme('colors.primary.600') !important;
}

.dark .swiper-button-next,
.dark .swiper-button-prev {
  color: theme('colors.primary.400') !important;
}

.dark .swiper-pagination-bullet-active {
  background: theme('colors.primary.400') !important;
}
</style> 