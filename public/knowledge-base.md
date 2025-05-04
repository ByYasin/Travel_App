# Bilgi Tabanı - Hatalar ve Çözümleri

Bu dosya, proje geliştirme sürecinde karşılaşılan hataları ve çözümlerini içermektedir.

## Model Yapıları

### TourReview Modeli
- `tour_id`, `user_id`, `rating`, `comment`, `status`, `is_visible` alanlarına sahiptir
- Tour ve User modelleriyle ilişkileri vardır
- Beğeni ve yanıtları yönetmek için metotları bulunur
- `isLikedByUser` methodu, belirli bir kullanıcının değerlendirmeyi beğenip beğenmediğini kontrol eder

### TourReviewReply Modeli
- `review_id`, `user_id`, `content`, `status`, `is_visible` alanlarına sahiptir
- TourReview ve User modelleriyle ilişkileri vardır
- Beğenileri yönetmek için metotları bulunur
- `isLikedByUser` methodu, belirli bir kullanıcının yanıtı beğenip beğenmediğini kontrol eder

### TourReviewLike Modeli
- `review_id`, `user_id` alanlarına sahiptir
- TourReview ve User modelleriyle ilişkileri vardır

### TourReviewReplyLike Modeli
- `reply_id`, `user_id` alanlarına sahiptir
- TourReviewReply ve User modelleriyle ilişkileri vardır

## Controller İşlemleri

### TourReviewController
- Değerlendirmeleri listeleme (index)
- Yeni değerlendirme oluşturma (store)
- Değerlendirme güncelleme (update)
- Değerlendirme silme (destroy)
- Değerlendirme beğenme/beğenmeme (toggleLike)
- Değerlendirmeye yanıt ekleme (addReply)
- Yanıt beğenme/beğenmeme (toggleReplyLike)

## JWT Token Auth Hatası
Çözüm: config/jwt.php dosyasında TTL değerini arttırarak token süresini uzatabilirsiniz.

## Beğeni ve Yanıt İstatistikleri Gösterilmiyor
Çözüm: İlgili ilişkileri eager loading ile yükleyerek (with() metodu) performansı artırabilir ve gerekli istatistikleri gösterebilirsiniz.

## Auth Middleware Hatası
Çözüm: Routelarda auth middleware'inin doğru şekilde tanımlandığından emin olun ve AuthServiceProvider'da gerekli yetkilendirmeleri tanımlayın. 