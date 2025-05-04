const API_URL = '/api';

export const tourService = {
    async getTours() {
        const response = await fetch(`${API_URL}/tours`);
        if (!response.ok) throw new Error('Turlar alınamadı');
        return response.json();
    },

    async getTour(id) {
        const response = await fetch(`${API_URL}/tours/${id}`);
        if (!response.ok) throw new Error('Tur bilgileri alınamadı');
        return response.json();
    },

    async createReservation(data) {
        try {
            const response = await fetch(`${API_URL}/tours/create-reservation`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(data)
            });

            const responseData = await response.json();
            
            if (!response.ok) {
                const error = new Error(responseData.message || 'Rezervasyon oluşturulamadı');
                error.response = {
                    status: response.status,
                    data: responseData
                };
                throw error;
            }
            
            return responseData;
        } catch (error) {
            console.error('API Hatası:', error);
            throw error;
        }
    }
};

export const adminService = {
    async getCategories(onlyActive = false) {
        try {
            const url = onlyActive 
                ? `/admin/categories-list?active=true&_t=${Date.now()}` 
                : `/admin/categories-list?_t=${Date.now()}`;
                
            const response = await fetch(url);
            
            if (!response.ok) {
                const error = new Error('Kategoriler alınamadı');
                error.status = response.status;
                throw error;
            }
            
            return response.json();
        } catch (error) {
            console.error('Kategori API Hatası:', error);
            throw error;
        }
    },
    
    async getCategoriesActive() {
        return this.getCategories(true);
    }
}; 