<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Doğrulama Dil Satırları
    |--------------------------------------------------------------------------
    |
    | Aşağıdaki dil satırları, doğrulama sınıfı tarafından kullanılan varsayılan
    | hata mesajlarını içermektedir. Bazı kuralların birden fazla versiyonu
    | bulunmaktadır, bu mesajlardan hangisinin görüntüleneceğini belirlemek için
    | "size" kurallarını özelleştirebilirsiniz.
    |
    */

    'accepted' => ':attribute kabul edilmelidir.',
    'accepted_if' => ':other alanı :value olduğunda :attribute kabul edilmelidir.',
    'active_url' => ':attribute geçerli bir URL olmalıdır.',
    'after' => ':attribute, :date tarihinden sonraki bir tarih olmalıdır.',
    'after_or_equal' => ':attribute, :date tarihinden sonra veya aynı tarihte olmalıdır.',
    'alpha' => ':attribute sadece harflerden oluşmalıdır.',
    'alpha_dash' => ':attribute sadece harfler, rakamlar, tireler ve alt çizgiler içerebilir.',
    'alpha_num' => ':attribute sadece harfler ve rakamlar içerebilir.',
    'array' => ':attribute bir dizi olmalıdır.',
    'ascii' => ':attribute yalnızca tek baytlık alfasayısal karakterler ve semboller içermelidir.',
    'before' => ':attribute, :date tarihinden önceki bir tarih olmalıdır.',
    'before_or_equal' => ':attribute, :date tarihinden önce veya aynı tarihte olmalıdır.',
    'between' => [
        'array' => ':attribute, :min ile :max arasında öğe içermelidir.',
        'file' => ':attribute, :min ile :max kilobayt arasında olmalıdır.',
        'numeric' => ':attribute, :min ile :max arasında olmalıdır.',
        'string' => ':attribute, :min ile :max karakter arasında olmalıdır.',
    ],
    'boolean' => ':attribute alanı true veya false olmalıdır.',
    'can' => ':attribute alanı yetkisiz bir değer içeriyor.',
    'confirmed' => ':attribute doğrulaması eşleşmiyor.',
    'current_password' => 'Parola yanlış.',
    'date' => ':attribute geçerli bir tarih olmalıdır.',
    'date_equals' => ':attribute, :date ile aynı tarihte olmalıdır.',
    'date_format' => ':attribute, :format formatına uygun olmalıdır.',
    'decimal' => ':attribute :decimal ondalık basamağa sahip olmalıdır.',
    'declined' => ':attribute reddedilmelidir.',
    'declined_if' => ':other alanı :value olduğunda :attribute reddedilmelidir.',
    'different' => ':attribute ile :other birbirinden farklı olmalıdır.',
    'digits' => ':attribute :digits basamaklı olmalıdır.',
    'digits_between' => ':attribute :min ile :max basamak arasında olmalıdır.',
    'dimensions' => ':attribute geçersiz resim boyutlarına sahip.',
    'distinct' => ':attribute alanında yinelenen bir değer var.',
    'doesnt_end_with' => ':attribute şunlardan biriyle bitmemelidir: :values.',
    'doesnt_start_with' => ':attribute şunlardan biriyle başlamamalıdır: :values.',
    'email' => ':attribute geçerli bir e-posta adresi olmalıdır.',
    'ends_with' => ':attribute şunlardan biriyle bitmelidir: :values.',
    'enum' => 'Seçilen :attribute geçersiz.',
    'exists' => 'Seçilen :attribute geçersiz.',
    'extensions' => ':attribute şu uzantılardan birine sahip olmalıdır: :values.',
    'file' => ':attribute bir dosya olmalıdır.',
    'filled' => ':attribute alanı bir değer içermelidir.',
    'gt' => [
        'array' => ':attribute, :value öğeden daha fazla öğe içermelidir.',
        'file' => ':attribute, :value kilobayttan büyük olmalıdır.',
        'numeric' => ':attribute, :value değerinden büyük olmalıdır.',
        'string' => ':attribute, :value karakterden daha uzun olmalıdır.',
    ],
    'gte' => [
        'array' => ':attribute, :value veya daha fazla öğe içermelidir.',
        'file' => ':attribute, :value kilobayt veya daha büyük olmalıdır.',
        'numeric' => ':attribute, :value değerinden büyük veya eşit olmalıdır.',
        'string' => ':attribute, :value karakter veya daha uzun olmalıdır.',
    ],
    'hex_color' => ':attribute geçerli bir onaltılık renk kodu olmalıdır.',
    'image' => ':attribute bir resim olmalıdır.',
    'in' => 'Seçilen :attribute geçersiz.',
    'in_array' => ':attribute alanı :other içinde mevcut değil.',
    'integer' => ':attribute bir tamsayı olmalıdır.',
    'ip' => ':attribute geçerli bir IP adresi olmalıdır.',
    'ipv4' => ':attribute geçerli bir IPv4 adresi olmalıdır.',
    'ipv6' => ':attribute geçerli bir IPv6 adresi olmalıdır.',
    'json' => ':attribute geçerli bir JSON dizesi olmalıdır.',
    'lowercase' => ':attribute küçük harf olmalıdır.',
    'lt' => [
        'array' => ':attribute, :value öğeden daha az öğe içermelidir.',
        'file' => ':attribute, :value kilobayttan küçük olmalıdır.',
        'numeric' => ':attribute, :value değerinden küçük olmalıdır.',
        'string' => ':attribute, :value karakterden daha kısa olmalıdır.',
    ],
    'lte' => [
        'array' => ':attribute, :value öğeden daha fazla öğe içermemelidir.',
        'file' => ':attribute, :value kilobayt veya daha küçük olmalıdır.',
        'numeric' => ':attribute, :value değerinden küçük veya eşit olmalıdır.',
        'string' => ':attribute, :value karakter veya daha kısa olmalıdır.',
    ],
    'mac_address' => ':attribute geçerli bir MAC adresi olmalıdır.',
    'max' => [
        'array' => ':attribute, :max öğeden fazla öğe içermemelidir.',
        'file' => ':attribute, :max kilobayttan büyük olmamalıdır.',
        'numeric' => ':attribute, :max değerinden büyük olmamalıdır.',
        'string' => ':attribute, :max karakterden uzun olmamalıdır.',
    ],
    'max_digits' => ':attribute en fazla :max basamak içermelidir.',
    'mimes' => ':attribute, şu dosya türlerinden biri olmalıdır: :values.',
    'mimetypes' => ':attribute, şu dosya türlerinden biri olmalıdır: :values.',
    'min' => [
        'array' => ':attribute en az :min öğe içermelidir.',
        'file' => ':attribute en az :min kilobayt olmalıdır.',
        'numeric' => ':attribute en az :min olmalıdır.',
        'string' => ':attribute en az :min karakter olmalıdır.',
    ],
    'min_digits' => ':attribute en az :min basamak içermelidir.',
    'missing' => ':attribute alanı eksik olmalıdır.',
    'missing_if' => ':other alanı :value olduğunda :attribute alanı eksik olmalıdır.',
    'missing_unless' => ':other alanı :value olmadığı sürece :attribute alanı eksik olmalıdır.',
    'missing_with' => ':values mevcut olduğunda :attribute alanı eksik olmalıdır.',
    'missing_with_all' => ':values mevcut olduğunda :attribute alanı eksik olmalıdır.',
    'multiple_of' => ':attribute, :value değerinin katı olmalıdır.',
    'not_in' => 'Seçilen :attribute geçersiz.',
    'not_regex' => ':attribute formatı geçersiz.',
    'numeric' => ':attribute bir sayı olmalıdır.',
    'password' => [
        'letters' => ':attribute en az bir harf içermelidir.',
        'mixed' => ':attribute en az bir büyük harf ve bir küçük harf içermelidir.',
        'numbers' => ':attribute en az bir rakam içermelidir.',
        'symbols' => ':attribute en az bir sembol içermelidir.',
        'uncompromised' => 'Verilen :attribute bir veri sızıntısında görünmüştür. Lütfen farklı bir :attribute seçin.',
    ],
    'present' => ':attribute alanı mevcut olmalıdır.',
    'present_if' => ':other alanı :value olduğunda :attribute alanı mevcut olmalıdır.',
    'present_unless' => ':other alanı :value olmadığı sürece :attribute alanı mevcut olmalıdır.',
    'present_with' => ':values mevcut olduğunda :attribute alanı mevcut olmalıdır.',
    'present_with_all' => ':values mevcut olduğunda :attribute alanı mevcut olmalıdır.',
    'prohibited' => ':attribute alanı yasaktır.',
    'prohibited_if' => ':other alanı :value olduğunda :attribute alanı yasaktır.',
    'prohibited_unless' => ':other alanı :values içinde olmadığı sürece :attribute alanı yasaktır.',
    'prohibits' => ':attribute alanı, :other alanının mevcut olmasını yasaklar.',
    'regex' => ':attribute formatı geçersiz.',
    'required' => ':attribute alanı gereklidir.',
    'required_array_keys' => ':attribute alanı, :values için girişler içermelidir.',
    'required_if' => ':other alanı :value olduğunda :attribute alanı gereklidir.',
    'required_if_accepted' => ':other alanı kabul edildiğinde :attribute alanı gereklidir.',
    'required_unless' => ':other alanı :values içinde olmadığı sürece :attribute alanı gereklidir.',
    'required_with' => ':values mevcut olduğunda :attribute alanı gereklidir.',
    'required_with_all' => ':values mevcut olduğunda :attribute alanı gereklidir.',
    'required_without' => ':values mevcut olmadığında :attribute alanı gereklidir.',
    'required_without_all' => ':values alanlarının hiçbiri mevcut olmadığında :attribute alanı gereklidir.',
    'same' => ':attribute ile :other eşleşmelidir.',
    'size' => [
        'array' => ':attribute :size öğe içermelidir.',
        'file' => ':attribute :size kilobayt olmalıdır.',
        'numeric' => ':attribute :size olmalıdır.',
        'string' => ':attribute :size karakter olmalıdır.',
    ],
    'starts_with' => ':attribute şunlardan biriyle başlamalıdır: :values.',
    'string' => ':attribute bir dize olmalıdır.',
    'timezone' => ':attribute geçerli bir saat dilimi olmalıdır.',
    'unique' => ':attribute zaten alınmış.',
    'uploaded' => ':attribute yüklenemedi.',
    'uppercase' => ':attribute büyük harf olmalıdır.',
    'url' => ':attribute geçerli bir URL olmalıdır.',
    'ulid' => ':attribute geçerli bir ULID olmalıdır.',
    'uuid' => ':attribute geçerli bir UUID olmalıdır.',

    /*
    |--------------------------------------------------------------------------
    | Özelleştirilmiş Doğrulama Mesajları
    |--------------------------------------------------------------------------
    |
    | Bu alanda her niteleyici (attribute) ve kural (rule) ikilisine özel hata
    | mesajları tanımlayabilirsiniz. Bu özellik, son kullanıcıya daha gerçekçi
    | metinler göstermeniz için oldukça faydalıdır.
    |
    | Örnek olarak:
    |
    | 'email.email': 'Girdiğiniz e-posta adresi geçerli değil.'
    | 'x.regex': 'x alanı için "a-b.c" formatında veri girmelisiniz.'
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
        'email' => [
            'unique' => 'Bu e-posta adresi zaten kullanılmaktadır.',
        ],
        'password' => [
            'min' => 'Şifre en az :min karakter olmalıdır.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Özelleştirilmiş Niteleyici İsimleri
    |--------------------------------------------------------------------------
    |
    | Bu alandaki bilgiler "email" gibi niteleyici isimlerini "e-posta adresi"
    | gibi daha okunabilir metinlere çevirmek için kullanılır. Bu bilgiler
    | hata mesajlarının daha temiz olmasını sağlar.
    |
    | Örnek olarak:
    |
    | 'email' => 'e-posta adresi',
    | 'password' => 'parola',
    |
    */

    'attributes' => [
        'name' => 'ad',
        'username' => 'kullanıcı adı',
        'email' => 'e-posta',
        'first_name' => 'ad',
        'last_name' => 'soyad',
        'password' => 'şifre',
        'password_confirmation' => 'şifre doğrulama',
        'city' => 'şehir',
        'country' => 'ülke',
        'address' => 'adres',
        'phone' => 'telefon',
        'mobile' => 'cep telefonu',
        'age' => 'yaş',
        'sex' => 'cinsiyet',
        'gender' => 'cinsiyet',
        'day' => 'gün',
        'month' => 'ay',
        'year' => 'yıl',
        'hour' => 'saat',
        'minute' => 'dakika',
        'second' => 'saniye',
        'title' => 'başlık',
        'content' => 'içerik',
        'description' => 'açıklama',
        'excerpt' => 'alıntı',
        'date' => 'tarih',
        'time' => 'zaman',
        'available' => 'uygun',
        'size' => 'boyut',
    ],

]; 