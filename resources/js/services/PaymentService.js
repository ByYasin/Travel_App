import axios from 'axios';

class PaymentService {
  /**
   * Ödeme işlemini başlatır
   * @param {Object} paymentData - Ödeme verileri
   * @returns {Promise} - API yanıtı
   */
  async processPayment(paymentData) {
    try {
      const response = await axios.post('/api/payments/process', paymentData);
      return response.data;
    } catch (error) {
      console.error('Ödeme işlemi sırasında hata:', error);
      throw error;
    }
  }

  /**
   * Ödeme durumunu kontrol eder
   * @param {String} transactionId - İşlem ID
   * @returns {Promise} - API yanıtı
   */
  async checkPaymentStatus(transactionId) {
    try {
      const response = await axios.get(`/api/payments/status/${transactionId}`);
      return response.data;
    } catch (error) {
      console.error('Ödeme durumu kontrol edilirken hata:', error);
      throw error;
    }
  }

  /**
   * İptal işlemini başlatır
   * @param {Object} refundData - İptal verileri
   * @returns {Promise} - API yanıtı
   */
  async refundPayment(refundData) {
    try {
      const response = await axios.post('/api/payments/refund', refundData);
      return response.data;
    } catch (error) {
      console.error('İptal işlemi sırasında hata:', error);
      throw error;
    }
  }

  /**
   * Kredi kartı simülasyonu için rastgele başarılı sonuç döndürür
   * Demo amaçlıdır, gerçek API çağrısı olmadan simüle eder
   * @param {Object} paymentData - Ödeme verileri
   * @returns {Promise} - Simüle edilmiş API yanıtı
   */
  simulatePayment(paymentData) {
    return new Promise((resolve, reject) => {
      // 90% başarı oranı simülasyonu
      const isSuccess = Math.random() <= 0.9;
      
      setTimeout(() => {
        if (isSuccess) {
          const transactionId = `TR${Date.now()}${Math.floor(Math.random() * 1000)}`;
          resolve({
            success: true,
            message: 'Ödeme başarıyla tamamlandı',
            transaction_id: transactionId,
            payment_details: {
              card_number: `${paymentData.card_number.substring(0, 4)} **** **** ${paymentData.card_number.substring(12, 16)}`,
              card_holder: paymentData.card_holder,
              transaction_id: transactionId,
              payment_date: new Date().toISOString(),
              installment: paymentData.installment || 1
            }
          });
        } else {
          reject({
            response: {
              data: {
                success: false,
                message: 'Ödeme işlemi sırasında bir hata oluştu',
                error_code: 'PAYMENT_FAILED',
              }
            }
          });
        }
      }, 1500); // 1.5 saniye gecikme ile simüle et
    });
  }

  /**
   * Farklı ödeme seçeneklerini döndür (demo)
   * @returns {Array} Ödeme seçenekleri
   */
  getPaymentOptions() {
    return [
      {
        id: 'credit_card',
        name: 'Kredi Kartı',
        description: 'Kredi kartı ile güvenli ödeme',
        icon: 'credit-card',
        installmentOptions: [1, 2, 3, 6, 9, 12]
      },
      {
        id: 'bank_transfer',
        name: 'Banka Havalesi',
        description: 'Banka hesabına havale yapın',
        icon: 'bank',
        installmentOptions: []
      },
      {
        id: 'cash',
        name: 'Nakit Ödeme',
        description: 'Tur başlangıcında rehbere nakit ödeme',
        icon: 'money-bill',
        installmentOptions: []
      }
    ];
  }

  /**
   * Ödeme yönteminin adını döndür
   * @param {String} method - Ödeme yöntemi kodu
   * @returns {String} Ödeme yöntemi adı
   */
  getPaymentMethodName(method) {
    const methods = {
      credit_card: 'Kredi Kartı',
      bank_transfer: 'Banka Havalesi',
      cash: 'Nakit Ödeme'
    };
    
    return methods[method] || method;
  }
}

export default new PaymentService(); 