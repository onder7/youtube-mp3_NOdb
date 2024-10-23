youtube-mp3_NOdb
Not: YouTube'dan video indirirken telif hakları konusunda dikkatli olun ve sadece izin verilen içerikleri indirin.
Gerekli paketleri yükleyin
composer require norkunas/youtube-dl-php

Kurulum adımları:

yt-dlp'yi yükleyin:
Windows için:

https://github.com/yt-dlp/yt-dlp/releases adresinden yt-dlp.exe'yi indirin
Bu dosyayı C:\Windows\System32 klasörüne kopyalayın
Kontrol : yt-dlp --version


FFmpeg'i yükleyin:
Windows için:
https://www.gyan.dev/ffmpeg/builds/ adresinden FFmpeg'i indirin
Zip dosyasını açın
bin klasöründeki üç exe dosyasını (ffmpeg.exe, ffprobe.exe, ffplay.exe) C:\Windows\System32 klasörüne kopyalayın
Kontrol :ffmpeg-check.php

Kurulum başarısız olursa:

Antivirüs programınızın engellemiş olabilir:

Antivirüs programınızı geçici olarak devre dışı bırakın
Kurulumu tekrar deneyin


Yönetici izinleri:

Command Prompt'u yönetici olarak çalıştırın
copy yt-dlp.exe C:\Windows\System32 komutunu kullanın


Alternatif kurulum yolu:

$command = ".\yt-dlp.exe -x --audio-format mp3 -o \"$output\" \"$url\"";


Özellikler:

YouTube videolarından MP3 dönüşümü
Yüksek kalitede ses
İndirilen dosyalar listesi
Kolay kullanımlı arayüz
İlerleme göstergesi
Hata yönetimi

Güvenlik önlemleri:

İndirilen dosyaların boyut kontrolü
Dosya türü doğrulaması
URL doğrulaması
Rate limiting

Not: YouTube'dan video indirirken telif hakları konusunda dikkatli olun ve sadece izin verilen içerikleri indirin.
