# YouTube MP3 Downloader

![Version](https://img.shields.io/badge/versiyon-1.0.0-blue)
![PHP](https://img.shields.io/badge/PHP-7.4+-green)
![License](https://img.shields.io/badge/lisans-MIT-orange)

<div align="center">

![YouTube MP3 Downloader](images/screenshot.png)

*YouTube Video to MP3 Dönüştürücü*

</div>

## ⚠️ Önemli Not
> [!WARNING]
> YouTube'dan video indirirken telif hakları konusunda dikkatli olun ve sadece izin verilen içerikleri indirin.

## 📋 Genel Bakış
Bu uygulama, YouTube videolarını MP3 formatına dönüştürmenizi sağlayan PHP tabanlı bir araçtır.

## ✨ Özellikler
- ✅ YouTube videolarından MP3 dönüşümü
- ✅ Yüksek kalitede ses çıktısı
- ✅ İndirilen dosyalar listesi
- ✅ Kolay kullanımlı arayüz
- ✅ İlerleme göstergesi
- ✅ Hata yönetimi
- ✅ Güvenlik kontrolleri

## 🚀 Kurulum Adımları

### 1️⃣ Gerekli Paketlerin Kurulumu
```bash
composer require norkunas/youtube-dl-php
```

### 2️⃣ yt-dlp Kurulumu (Windows)

> [!IMPORTANT]
> **Adım 1: yt-dlp İndirme ve Kurulum**
> 1. [yt-dlp releases](https://github.com/yt-dlp/yt-dlp/releases) sayfasından `yt-dlp.exe`'yi indirin
> 2. İndirilen dosyayı `C:\Windows\System32` klasörüne kopyalayın
> 3. Kurulumu kontrol edin:
>    ```bash
>    yt-dlp --version
>    ```

### 3️⃣ FFmpeg Kurulumu (Windows)

```bash
# 1. FFmpeg'i indirin
https://www.gyan.dev/ffmpeg/builds/

# 2. Zip dosyasını açın

# 3. Aşağıdaki dosyaları C:\Windows\System32'ye kopyalayın:
- ffmpeg.exe
- ffprobe.exe
- ffplay.exe
```

## 🛠️ Kurulum Sorun Giderme

### Antivirüs Sorunları
1. Antivirüs programınızı geçici olarak devre dışı bırakın
2. Kurulumu tekrar deneyin
3. Başarılı kurulumdan sonra antivirüsü tekrar etkinleştirin

### Yönetici İzinleri
```bash
# Command Prompt'u yönetici olarak çalıştırın
copy yt-dlp.exe C:\Windows\System32
```

### Alternatif Kurulum
```php
$command = ".\yt-dlp.exe -x --audio-format mp3 -o \"$output\" \"$url\"";
```

## 📖 Kullanım

### Basit Kullanım
```php
require 'vendor/autoload.php';

$downloader = new YouTubeMP3Downloader();
$downloader->convert('https://www.youtube.com/watch?v=VIDEO_ID');
```

### İleri Seviye Kullanım
```php
$options = [
    'quality' => 'high',
    'output' => 'downloads/{title}.mp3',
    'progress' => true
];

$downloader->convertWithOptions('VIDEO_URL', $options);
```

## ⚙️ Güvenlik Önlemleri

> [!IMPORTANT]
> ### Uygulanan Kontroller
> - Dosya boyutu limitleri
> - Dosya türü doğrulaması
> - URL doğrulaması
> - Rate limiting
> - Telif hakkı kontrolü

## 🔍 Hata Kontrolleri
1. FFmpeg Kontrolü
```php
if (!file_exists('ffmpeg-check.php')) {
    die('FFmpeg kurulu değil!');
}
```

2. URL Doğrulama
```php
if (!filter_var($url, FILTER_VALIDATE_URL)) {
    throw new Exception('Geçersiz URL');
}
```

## 📝 Kod Örnekleri

### İlerleme Göstergesi
```php
$downloader->onProgress(function ($progress) {
    echo "İndirme İlerlemesi: $progress%\n";
});
```

### Hata Yönetimi
```php
try {
    $downloader->convert($url);
} catch (Exception $e) {
    echo "Hata: " . $e->getMessage();
}
```

## 📱 İletişim
- 📧 E-posta: [onder7@gmail.com]
- 🌐 GitHub: [github.com/onder7]

## ⚖️ Lisans
Bu proje MIT lisansı altında lisanslanmıştır.

## 🤝 Katkıda Bulunma
1. Fork edin
2. Feature branch oluşturun
3. Değişikliklerinizi commit edin
4. Branch'inizi push edin
5. Pull Request oluşturun

## 📚 SSS

> [!NOTE]
> **S: İndirme hızı düşük, ne yapabilirim?**
> - İnternet bağlantınızı kontrol edin
> - Rate limiting ayarlarını kontrol edin
> - Proxy kullanmayı deneyin
> 
> **S: Ses kalitesi düşük, nasıl artırabilirim?**
> - Yüksek kalite parametresini kullanın:
> ```php
> --audio-quality 0
> ```

---

<div align="center">

**..:: Onder Monder ::..**

*Profesyonel IT Çözümleri*

</div>
