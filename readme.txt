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


Müzik ve şarkı sözleri için bir veritabanı yapısı oluşturalım.
-- music_db.sql
CREATE TABLE downloads (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    artist VARCHAR(255),
    youtube_url VARCHAR(255) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    download_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    file_size INT,
    duration VARCHAR(10),
    download_count INT DEFAULT 0
);

CREATE TABLE lyrics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    download_id INT,
    lyrics_text TEXT,
    language VARCHAR(50),
    source VARCHAR(255),
    added_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (download_id) REFERENCES downloads(id) ON DELETE CASCADE
);

-- İndeks ekleyelim
CREATE INDEX idx_youtube_url ON downloads(youtube_url);
CREATE INDEX idx_download_id ON lyrics(download_id);

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