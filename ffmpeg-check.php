<?php
// ffmpeg-check.php
class FFmpegInstaller {
    private $systemPath = 'C:\\Windows\\System32\\';
    private $tempPath = 'C:\\temp\\';
    private $ffmpegUrl = 'https://www.gyan.dev/ffmpeg/builds/ffmpeg-release-essentials.zip';

    public function checkFFmpeg() {
        exec('ffmpeg -version', $output, $returnCode);
        return $returnCode === 0;
    }

    public function install() {
        try {
            echo "FFmpeg kurulumu başlıyor...<br>";
            
            // Temp klasörü oluştur
            if (!file_exists($this->tempPath)) {
                mkdir($this->tempPath);
            }
            
            // FFmpeg indir
            $zipFile = $this->tempPath . 'ffmpeg.zip';
            echo "FFmpeg indiriliyor...<br>";
            file_put_contents($zipFile, file_get_contents($this->ffmpegUrl));
            
            // ZIP dosyasını çıkart
            echo "Dosyalar çıkartılıyor...<br>";
            $zip = new ZipArchive;
            if ($zip->open($zipFile) === TRUE) {
                $zip->extractTo($this->tempPath);
                $zip->close();
                
                // Gerekli dosyaları System32'ye kopyala
                $binPath = $this->tempPath . 'ffmpeg-*\\bin\\';
                $binFiles = glob($binPath . '*.exe');
                
                foreach ($binFiles as $file) {
                    $fileName = basename($file);
                    copy($file, $this->systemPath . $fileName);
                    echo "$fileName kopyalandı.<br>";
                }
                
                // Temp dosyalarını temizle
                $this->cleanup();
                
                echo "FFmpeg kurulumu tamamlandı!<br>";
                return true;
            }
            
            throw new Exception("ZIP dosyası açılamadı");
            
        } catch (Exception $e) {
            echo "Hata: " . $e->getMessage() . "<br>";
            return false;
        }
    }

    private function cleanup() {
        // Temp dosyalarını sil
        array_map('unlink', glob($this->tempPath . '*'));
        rmdir($this->tempPath);
    }
}

// Kullanımı
$installer = new FFmpegInstaller();

if (!$installer->checkFFmpeg()) {
    echo "FFmpeg bulunamadı. Kurulum başlatılıyor...<br>";
    if ($installer->install()) {
        echo "FFmpeg başarıyla kuruldu!<br>";
    } else {
        echo "FFmpeg kurulumu başarısız oldu. Lütfen manuel kurulum yapın.<br>";
    }
} else {
    echo "FFmpeg zaten kurulu!<br>";
}
?>