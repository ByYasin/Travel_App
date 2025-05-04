import { defineStore } from 'pinia';
import { ref, watch } from 'vue';

export const useThemeStore = defineStore('theme', () => {
    // State
    const theme = ref(localStorage.getItem('theme') || getPreferredTheme());
    const isSystemTheme = ref(localStorage.getItem('theme') === null);

    // Sistem temasını tercih et (karanlık veya açık)
    function getPreferredTheme() {
        return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    }

    // Temayı güncelle
    function setTheme(newTheme) {
        theme.value = newTheme;
        localStorage.setItem('theme', newTheme);
        isSystemTheme.value = false;
        applyTheme(newTheme);
        
        // Tema değişikliği olayını tetikle
        window.dispatchEvent(new CustomEvent('theme-changed', { 
            detail: { theme: newTheme }
        }));
    }

    // Sistem teması kullan
    function useSystemTheme() {
        const preferredTheme = getPreferredTheme();
        theme.value = preferredTheme;
        localStorage.removeItem('theme');
        isSystemTheme.value = true;
        applyTheme(preferredTheme);
        
        // Tema değişikliği olayını tetikle
        window.dispatchEvent(new CustomEvent('theme-changed', { 
            detail: { theme: preferredTheme }
        }));
    }

    // Temayı uygula
    function applyTheme(selectedTheme) {
        if (selectedTheme === 'dark') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    }

    // Tema değişimini izle
    watch(theme, (newTheme) => {
        applyTheme(newTheme);
    });

    // Temayı değiştir
    function toggleTheme() {
        const newTheme = theme.value === 'dark' ? 'light' : 'dark';
        setTheme(newTheme);
    }

    // Sistem temasını dinle
    function setupSystemThemeListener() {
        const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
        
        const handleThemeChange = (e) => {
            if (isSystemTheme.value) {
                const newTheme = e.matches ? 'dark' : 'light';
                theme.value = newTheme;
                applyTheme(newTheme);
            }
        };
        
        // Olayı dinle
        mediaQuery.addEventListener('change', handleThemeChange);
        
        // Temizleme işlevi
        return () => mediaQuery.removeEventListener('change', handleThemeChange);
    }

    // Başlangıçta temayı uygula
    function initTheme() {
        applyTheme(theme.value);
        return setupSystemThemeListener();
    }

    return {
        theme,
        isSystemTheme,
        setTheme,
        useSystemTheme,
        toggleTheme,
        initTheme
    };
}, {
    persist: {
        key: 'theme-store',
        storage: localStorage,
        paths: ['theme', 'isSystemTheme']
    }
}); 