import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '../store/auth';
import authService from '../services/auth';

// Yalnızca Ana sayfa için hızlı erişim için eager loading, diğerleri lazy loading
const Home = () => import(/* webpackChunkName: "home" */ '../pages/Home.vue');

// Tüm route tanımları
const routes = [
    {
        path: '/',
        name: 'home',
        component: Home,
        meta: {
            title: 'Ana Sayfa - Tur Projesi',
            metaTags: [
                {
                    name: 'description',
                    content: 'Tur Projesi ile en iyi tur deneyimlerini keşfedin. Kapadokya, Pamukkale, Efes ve daha fazlası.'
                },
                {
                    name: 'keywords',
                    content: 'tur, gezi, seyahat, tatil, kapadokya, pamukkale'
                }
            ]
        }
    },
    {
        path: '/tours',
        name: 'tours',
        component: () => import(/* webpackChunkName: "tours" */ '../pages/Tours.vue'),
        meta: {
            title: 'Turlar - Tur Projesi',
            metaTags: [
                {
                    name: 'description',
                    content: 'Birbirinden güzel turlarımızı keşfedin. Şehir turları, doğa gezileri ve daha fazlasını bulabilirsiniz.'
                }
            ]
        }
    },
    {
        path: '/turlar',
        redirect: '/tours'
    },
    {
        path: '/tour/:id',
        name: 'tour-detail',
        component: () => import(/* webpackChunkName: "tour-detail" */ '../pages/TourDetail.vue'),
        meta: {
            title: 'Tur Detayları - Tur Projesi',
            metaTags: [
                {
                    name: 'description',
                    content: 'Seçtiğiniz turun tüm detaylarını inceleyin, fiyat ve program bilgilerini öğrenin.'
                }
            ]
        }
    },
    {
        path: '/about',
        name: 'about',
        component: () => import(/* webpackChunkName: "about" */ '../pages/About.vue'),
        meta: {
            title: 'Hakkımızda - Tur Projesi',
            metaTags: [
                {
                    name: 'description',
                    content: 'Tur Projesi hakkında bilgi alın. Hikayemiz, vizyonumuz ve misyonumuz.'
                }
            ]
        }
    },
    {
        path: '/contact',
        name: 'contact',
        component: () => import(/* webpackChunkName: "contact" */ '../pages/Contact.vue'),
        meta: {
            title: 'İletişim - Tur Projesi',
            metaTags: [
                {
                    name: 'description',
                    content: 'Bizimle iletişime geçin. Sorularınız, önerileriniz ve talepleriniz için buradayız.'
                }
            ]
        }
    },
    {
        path: '/tours/:id/reservation',
        name: 'reservation',
        component: () => import(/* webpackChunkName: "reservation" */ '../pages/Reservation.vue')
    },
    {
        path: '/sss',
        name: 'sss',
        component: () => import(/* webpackChunkName: "sss" */ '../pages/SSS.vue')
    },
    {
        path: '/profile',
        name: 'profile',
        component: () => import(/* webpackChunkName: "profile" */ '../pages/Profile.vue'),
        meta: { requiresAuth: true }
    },
    {
        path: '/payment',
        name: 'payment',
        component: () => import(/* webpackChunkName: "payment" */ '../pages/Payment.vue'),
        meta: { 
            title: 'Ödeme - Tur Projesi',
            metaTags: [
                {
                    name: 'description',
                    content: 'Güvenli bir şekilde ödemenizi tamamlayın.'
                }
            ]
        }
    },
    {
        path: '/payment-success',
        name: 'payment-success',
        component: () => import(/* webpackChunkName: "payment-success" */ '../pages/PaymentSuccess.vue'),
        props: route => ({ 
            amount: route.query.amount,
            installment: parseInt(route.query.installment) || 1
        }),
        meta: { requiresAuth: true }
    },
    {
        path: '/profile/reservations/:id/modify',
        name: 'modify-reservation',
        component: () => import(/* webpackChunkName: "modify-reservation" */ '../pages/Profile/ModifyReservation.vue'),
        meta: { requiresAuth: true }
    },
    {
        path: '/login',
        name: 'login',
        component: () => import(/* webpackChunkName: "auth" */ '../pages/Auth/Login.vue'),
        meta: { guest: true }
    },
    {
        path: '/register',
        name: 'register',
        component: () => import(/* webpackChunkName: "auth" */ '../pages/Auth/Register.vue'),
        meta: { guest: true }
    },
    // Admin Routes
    {
        path: '/admin',
        name: 'admin-dashboard',
        component: () => import(/* webpackChunkName: "admin" */ '../pages/Admin/Dashboard.vue'),
        meta: { 
            requiresAuth: true,
            requiresAdmin: true,
            title: 'Admin Dashboard - Tur Projesi'
        }
    },
    {
        path: '/admin/tours',
        name: 'admin-tours',
        component: () => import(/* webpackChunkName: "admin-tours" */ '../pages/Admin/Tours.vue'),
        meta: { 
            requiresAuth: true,
            requiresAdmin: true,
            title: 'Turlar Yönetimi - Admin Panel'
        }
    },
    {
        path: '/admin/reservations',
        name: 'admin-reservations',
        component: () => import(/* webpackChunkName: "admin-reservations" */ '../pages/Admin/Reservations.vue'),
        meta: { 
            requiresAuth: true,
            requiresAdmin: true,
            title: 'Rezervasyonlar Yönetimi - Admin Panel'
        }
    },
    {
        path: '/admin/users',
        name: 'admin-users',
        component: () => import('../pages/Admin/Users.vue'),
        meta: { 
            requiresAuth: true,
            requiresAdmin: true,
            title: 'Kullanıcılar Yönetimi - Admin Panel'
        }
    },
    {
        path: '/admin/reports',
        name: 'admin-reports',
        component: () => import('../pages/Admin/Reports.vue'),
        meta: { 
            requiresAuth: true,
            requiresAdmin: true,
            title: 'Raporlar - Admin Panel'
        }
    },
    {
        path: '/admin/settings',
        name: 'admin-settings',
        component: () => import('../pages/Admin/Settings.vue'),
        meta: { 
            requiresAuth: true,
            requiresAdmin: true,
            title: 'Ayarlar - Admin Panel'
        }
    },
    {
        path: '/admin/categories',
        name: 'admin-categories',
        component: () => import('../pages/Admin/Categories.vue'),
        meta: { 
            requiresAuth: true,
            requiresAdmin: true,
            title: 'Kategoriler - Admin Panel'
        }
    },
    {
        path: '/admin/contact',
        name: 'admin-contact',
        component: () => import('../pages/Admin/Contact.vue'),
        meta: { 
            requiresAuth: true,
            requiresAdmin: true,
            title: 'İletişim Yönetimi - Admin Panel'
        }
    },
    // Profil alt sayfaları için yönlendirmeler
    {
        path: '/profile/reservations',
        name: 'profile-reservations',
        component: () => import(/* webpackChunkName: "profile" */ '../pages/Profile.vue'),
        props: { activeTab: 'reservations' },
        meta: { requiresAuth: true }
    },
    {
        path: '/profile/favorites',
        name: 'profile-favorites',
        component: () => import(/* webpackChunkName: "profile" */ '../pages/Profile.vue'),
        props: { activeTab: 'favorites' },
        meta: { requiresAuth: true }
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
    scrollBehavior() {
        return { top: 0 }
    }
});


router.beforeEach(async (to, from, next) => {
    const authStore = useAuthStore();
    
    const isAuthenticated = authStore.isLoggedIn || !!localStorage.getItem('user');
    const requiresAuth = to.matched.some(record => record.meta.requiresAuth);
    const requiresAdmin = to.matched.some(record => record.meta.requiresAdmin);
    const isGuestRoute = to.matched.some(record => record.meta.guest);
    
    console.log('Router koruması çalışıyor:');
    console.log('- Mevcut sayfa:', from.path);
    console.log('- Hedef sayfa:', to.path);
    console.log('- Auth durumu:', isAuthenticated ? 'Giriş yapılmış' : 'Giriş yapılmamış');
    console.log('- Route meta:', { requiresAuth, requiresAdmin, isGuestRoute });
    
    
    if (to.name === 'login') {
        
        console.log('Login sayfasına gidiliyor, herhangi bir yönlendirme yapılmayacak');
        return next();
    }
    
    
    if (requiresAuth && !isAuthenticated) {
        console.log('Oturum açılmamış, login sayfasına yönlendiriliyor');
        return next({ name: 'login', query: { redirect: to.fullPath } });
    }
    
   
    if (requiresAdmin) {
        
        if (!authStore.user) {
            try {
                console.log('Admin yetkisi kontrolü için kullanıcı bilgileri alınıyor...');
                await authStore.refreshUserData();
            } catch (error) {
                console.error('Admin yetkisi kontrolü için kullanıcı bilgileri alınamadı:', error);
                return next({ name: 'login' });
            }
        }
        
        
        const localStorageUser = JSON.parse(localStorage.getItem('user') || '{}');
        const lsRoleId = localStorageUser?.role_id;
        const storeRoleId = authStore.user?.role_id;
        
        console.log('Admin yetkisi kontrolü yapılıyor:');
        console.log('- LocalStorage role_id:', lsRoleId);
        console.log('- Store role_id:', storeRoleId);
        console.log('- authStore.isAdmin:', authStore.isAdmin);
        
        
        const isAdminUser = lsRoleId === 0 || lsRoleId === '0' || storeRoleId === 0 || storeRoleId === '0';
        
        if (!isAdminUser) {
            console.log('Admin yetkisi yok, ana sayfaya yönlendiriliyor');
            return next({ name: 'home' });
        }
        
        console.log('Admin yetkisi doğrulandı, admin sayfasına erişim izni verildi');
    }
    
    
    if (isGuestRoute && isAuthenticated) {
        console.log('Oturum açılmış, home sayfasına yönlendiriliyor');
        return next({ name: 'home' });
    }
    
    
    if (isAuthenticated && !authStore.isLoggedIn) {
        console.log('Local storage\'da user var ama store boş, user verilerini yeniliyoruz');
        try {
            await authStore.refreshUserData();
            next(); 
        } catch (error) {
            
            console.error('User bilgileri alınamadı:', error);
            return next({ name: 'login' });
        }
    } else {
        
        console.log('Normal sayfa yönlendirmesi');
        next();
    }
});


router.afterEach((to, from) => {
    
    const defaultTitle = 'Tur Projesi';
    const title = to.meta.title || defaultTitle;
    
    
    document.title = title;
    
    
    document.querySelectorAll('meta[data-vue-router-controlled]').forEach(el => el.remove());
    
    
    if (to.meta.metaTags) {
        to.meta.metaTags.forEach(tagDef => {
            const tag = document.createElement('meta');
            Object.keys(tagDef).forEach(key => {
                tag.setAttribute(key, tagDef[key]);
            });
            tag.setAttribute('data-vue-router-controlled', '');
            document.head.appendChild(tag);
        });
    }
    
    
    let canonicalEl = document.querySelector('link[rel="canonical"]');
    if (canonicalEl) {
        canonicalEl.setAttribute('href', window.location.origin + to.path);
    }
});

export default router; 