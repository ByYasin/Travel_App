<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Navbar altında 1 satır boşluk -->
    <div class="h-4"></div>

    <!-- Sayfa İçeriği -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Hero Section -->
      <div class="relative bg-white dark:bg-gray-900 overflow-hidden">
        <!-- Dekoratif Arka Plan -->
        <div class="absolute inset-0">
          <div class="absolute top-0 left-0 w-full h-full bg-[radial-gradient(circle_at_50%_50%,rgba(59,130,246,0.1),transparent_50%)]"></div>
          <div class="absolute top-0 left-0 w-full h-full bg-[linear-gradient(45deg,transparent_48%,rgba(59,130,246,0.1)_50%,transparent_52%)] bg-[length:20px_20px]"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <!-- Sol Kolon - İçerik -->
            <div class="relative">
              <!-- Dekoratif Çizgi -->
              <div class="absolute -left-8 top-0 w-1 h-24 bg-gradient-to-b from-primary-500 to-primary-300 rounded-full"></div>
              
              <div class="space-y-8">
                <div class="space-y-4">
                  <h2 class="text-sm font-semibold text-primary-500 tracking-wider uppercase">Hakkımızda</h2>
                  <h1 class="text-5xl md:text-6xl font-bold text-gray-900 dark:text-white leading-tight">
                    Seyahat Tutkunları İçin<br>
                    <span class="relative">
                      <span class="relative z-10 text-primary-500">Unutulmaz Deneyimler</span>
                      <span class="absolute bottom-0 left-0 w-full h-3 bg-primary-100 dark:bg-primary-900/30 -z-10"></span>
                    </span>
                  </h1>
                </div>

                <p class="text-xl text-gray-600 dark:text-gray-300 leading-relaxed">
                  13 yıldır Türkiye'nin dört bir yanında unutulmaz seyahat deneyimleri sunuyoruz. Profesyonel ekibimiz ve özenle seçilmiş rotalarımızla hayalinizdeki tatili gerçeğe dönüştürüyoruz.
                </p>

                <!-- İstatistikler -->
                <div class="grid grid-cols-3 gap-8 pt-8 border-t border-gray-200 dark:border-gray-700">
                  <div class="text-center">
                    <div class="text-3xl font-bold text-primary-500 mb-2">10K+</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Mutlu Müşteri</div>
                  </div>
                  <div class="text-center">
                    <div class="text-3xl font-bold text-primary-500 mb-2">%98</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Müşteri Memnuniyeti</div>
                  </div>
                  <div class="text-center">
                    <div class="text-3xl font-bold text-primary-500 mb-2">50+</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Tur Rotaları</div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Sağ Kolon - İnteraktif Kartlar -->
            <div class="relative">
              <div class="grid grid-cols-2 gap-6">
                <!-- Kart 1 -->
                <div class="group relative bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-500 hover:-translate-y-2">
                  <div class="absolute inset-0 bg-gradient-to-br from-primary-500/10 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                  <div class="relative">
                    <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900 rounded-xl flex items-center justify-center mb-4">
                      <svg class="w-6 h-6 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                    </div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white mb-2">13+</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Yıllık Deneyim</div>
                  </div>
                </div>

                <!-- Kart 2 -->
                <div class="group relative bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-500 hover:-translate-y-2">
                  <div class="absolute inset-0 bg-gradient-to-br from-primary-500/10 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                  <div class="relative">
                    <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900 rounded-xl flex items-center justify-center mb-4">
                      <svg class="w-6 h-6 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                      </svg>
                    </div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white mb-2">100+</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Profesyonel Rehber</div>
                  </div>
                </div>

                <!-- Kart 3 -->
                <div class="group relative bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-500 hover:-translate-y-2">
                  <div class="absolute inset-0 bg-gradient-to-br from-primary-500/10 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                  <div class="relative">
                    <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900 rounded-xl flex items-center justify-center mb-4">
                      <svg class="w-6 h-6 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                      </svg>
                    </div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white mb-2">24/7</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Destek</div>
                  </div>
                </div>

                <!-- Kart 4 -->
                <div class="group relative bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-500 hover:-translate-y-2">
                  <div class="absolute inset-0 bg-gradient-to-br from-primary-500/10 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                  <div class="relative">
                    <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900 rounded-xl flex items-center justify-center mb-4">
                      <svg class="w-6 h-6 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                      </svg>
                    </div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white mb-2">%100</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Güvenli Seyahat</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Misyon ve Vizyon -->
      <section class="py-20 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Misyon -->
            <div class="relative group">
              <div class="absolute inset-0 bg-gradient-to-br from-primary-500/10 to-primary-600/10 rounded-3xl transform group-hover:scale-105 transition-transform duration-500"></div>
              <div class="relative bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-xl">
                <div class="flex items-center mb-6">
                  <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900 rounded-xl flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                  </div>
                  <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Misyonumuz</h2>
                </div>
                <p class="text-lg text-gray-600 dark:text-gray-300 leading-relaxed">
                  Misyonumuz, misafirlerimize unutulmaz seyahat deneyimleri sunmak ve Türkiye'nin doğal, tarihi ve kültürel zenginliklerini en iyi şekilde tanıtmaktır. Profesyonel ekibimiz ve kaliteli hizmet anlayışımızla, her turumuzda misafirlerimizin beklentilerini aşmayı hedefliyoruz.
                </p>
                <div class="mt-8 flex items-center text-primary-600 dark:text-primary-400">
                  <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  <span class="font-medium">Kalite ve Güven</span>
                </div>
              </div>
            </div>

            <!-- Vizyon -->
            <div class="relative group">
              <div class="absolute inset-0 bg-gradient-to-br from-primary-500/10 to-primary-600/10 rounded-3xl transform group-hover:scale-105 transition-transform duration-500"></div>
              <div class="relative bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-xl">
                <div class="flex items-center mb-6">
                  <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900 rounded-xl flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                    </svg>
                  </div>
                  <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Vizyonumuz</h2>
                </div>
                <p class="text-lg text-gray-600 dark:text-gray-300 leading-relaxed">
                  Vizyonumuz, Türkiye'nin önde gelen tur operatörlerinden biri olarak, sürdürülebilir turizm anlayışıyla doğal ve kültürel mirasımızı koruyarak, gelecek nesillere aktarmaktır. Yenilikçi yaklaşımlarımız ve teknolojik altyapımızla, seyahat deneyimini en üst seviyeye taşımayı hedefliyoruz.
                </p>
                <div class="mt-8 flex items-center text-primary-600 dark:text-primary-400">
                  <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  <span class="font-medium">Sürdürülebilir Turizm</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Neden Biz -->
      <div class="mb-24">
        <div class="text-center mb-16">
          <span class="inline-block px-4 py-2 rounded-full bg-primary-100 dark:bg-primary-900 text-primary-600 dark:text-primary-300 text-sm mb-4">
            Ayrıcalıklarımız
          </span>
          <h2 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">
            Neden Bizi Tercih Etmelisiniz?
          </h2>
          <p class="text-xl text-gray-600 dark:text-gray-400 max-w-3xl mx-auto">
            20 yıllık deneyimimiz ve profesyonel ekibimizle size en iyi hizmeti sunuyoruz
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div class="group bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 p-8 rounded-2xl shadow-xl transition duration-500 hover:shadow-2xl hover:-translate-y-2">
            <div class="bg-gradient-to-br from-primary-500 to-primary-600 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6 transform transition duration-500 group-hover:scale-110 group-hover:rotate-3">
              <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
              </svg>
            </div>
            <h3 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4 text-center">Güvenli Seyahat</h3>
            <p class="text-gray-600 dark:text-gray-300 text-center">Tüm turlarımız tam kapsamlı seyahat sigortası ile güvence altındadır. TÜRSAB belgeli profesyonel rehberlerimiz eşliğinde güvenli bir seyahat deneyimi yaşarsınız.</p>
          </div>

          <div class="group bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 p-8 rounded-2xl shadow-xl transition duration-500 hover:shadow-2xl hover:-translate-y-2">
            <div class="bg-gradient-to-br from-primary-500 to-primary-600 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6 transform transition duration-500 group-hover:scale-110 group-hover:rotate-3">
              <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <h3 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4 text-center">Esnek Programlar</h3>
            <p class="text-gray-600 dark:text-gray-300 text-center">Gruplara ve özel isteklere göre özelleştirilebilir tur programları sunuyoruz. İstediğiniz tarihlerde, size özel rotalar planlıyoruz.</p>
          </div>

          <div class="group bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 p-8 rounded-2xl shadow-xl transition duration-500 hover:shadow-2xl hover:-translate-y-2">
            <div class="bg-gradient-to-br from-primary-500 to-primary-600 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6 transform transition duration-500 group-hover:scale-110 group-hover:rotate-3">
              <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
              </svg>
            </div>
            <h3 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4 text-center">Kaliteli Hizmet</h3>
            <p class="text-gray-600 dark:text-gray-300 text-center">5 yıldızlı oteller, lüks ulaşım araçları ve deneyimli rehberler ile en üst düzey hizmet standardını garanti ediyoruz.</p>
          </div>

          <div class="group bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 p-8 rounded-2xl shadow-xl transition duration-500 hover:shadow-2xl hover:-translate-y-2">
            <div class="bg-gradient-to-br from-primary-500 to-primary-600 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6 transform transition duration-500 group-hover:scale-110 group-hover:rotate-3">
              <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
            </div>
            <h3 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4 text-center">Küçük Gruplar</h3>
            <p class="text-gray-600 dark:text-gray-300 text-center">Maximum 15 kişilik küçük gruplarla, daha kişisel ve kaliteli bir tur deneyimi sunuyoruz. Her misafirimize özel ilgi gösteriyoruz.</p>
          </div>

          <div class="group bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 p-8 rounded-2xl shadow-xl transition duration-500 hover:shadow-2xl hover:-translate-y-2">
            <div class="bg-gradient-to-br from-primary-500 to-primary-600 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6 transform transition duration-500 group-hover:scale-110 group-hover:rotate-3">
              <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
              </svg>
            </div>
            <h3 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4 text-center">7/24 Destek</h3>
            <p class="text-gray-600 dark:text-gray-300 text-center">Seyahatiniz boyunca 7/24 müşteri desteği sağlıyoruz. Acil durumlarda anında müdahale ekibimiz hazır bekliyor.</p>
          </div>

          <div class="group bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 p-8 rounded-2xl shadow-xl transition duration-500 hover:shadow-2xl hover:-translate-y-2">
            <div class="bg-gradient-to-br from-primary-500 to-primary-600 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6 transform transition duration-500 group-hover:scale-110 group-hover:rotate-3">
              <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <h3 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4 text-center">Uygun Fiyat</h3>
            <p class="text-gray-600 dark:text-gray-300 text-center">En iyi fiyat garantisi sunuyoruz. Erken rezervasyon ve grup indirimleri ile bütçe dostu seyahat imkanı sağlıyoruz.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'

onMounted(() => {
  // Paralaks efekti için scroll event listener
  const handleScroll = () => {
    const parallaxElements = document.querySelectorAll('.scroll-parallax')
    parallaxElements.forEach(element => {
      const scrolled = window.pageYOffset
      element.style.transform = `translate3d(0, ${scrolled * 0.4}px, 0) scale(1.1)`
    })
  }

  window.addEventListener('scroll', handleScroll)

  // Component unmount olduğunda event listener'ı temizle
  return () => {
    window.removeEventListener('scroll', handleScroll)
  }
})
</script>

<style scoped>
.animate-fade-in-up {
  animation: fadeInUp 1s ease-out;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.scroll-parallax {
  will-change: transform;
}

.animate-blob {
  animation: blob 7s infinite;
}

.animation-delay-2000 {
  animation-delay: 2s;
}

.animation-delay-4000 {
  animation-delay: 4s;
}

@keyframes blob {
  0% {
    transform: translate(0px, 0px) scale(1);
  }
  33% {
    transform: translate(30px, -50px) scale(1.1);
  }
  66% {
    transform: translate(-20px, 20px) scale(0.9);
  }
  100% {
    transform: translate(0px, 0px) scale(1);
  }
}
</style> 