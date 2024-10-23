<?php
require_once 'config.php';
require_once 'LyricsScraper.php';


class Database {
    private static $instance = null;
    private $connection;
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        $this->connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($this->connection->connect_error) {
            throw new Exception("Veritabanı bağlantı hatası: " . $this->connection->connect_error);
        }
        $this->connection->set_charset("utf8mb4");
    }
    
    public function getConnection() {
        return $this->connection;
    }
}

class MusicManager {
    private $db;
    private $downloadPath;
    private $lyricsScraper;

    public function __construct() {
        // Veritabanı bağlantısı
        $this->db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($this->db->connect_error) {
            throw new Exception("Veritabanı bağlantı hatası: " . $this->db->connect_error);
        }
        $this->db->set_charset("utf8mb4");

        // Lyrics scraper'ı başlat
        $this->lyricsScraper = new LyricsScraper($this->db);

        // İndirme klasörü
        $this->downloadPath = __DIR__ . '/downloads/';
        if (!file_exists($this->downloadPath)) {
            mkdir($this->downloadPath, 0777, true);
        }
    }

    // public function downloadMusic($url) {
    //     try {
    //         // YouTube URL'sini doğrula
    //         if (!filter_var($url, FILTER_VALIDATE_URL)) {
    //             throw new Exception("Geçersiz URL formatı");
    //         }

    //         // Önce URL'nin daha önce indirilip indirilmediğini kontrol et
    //         $stmt = $this->db->prepare("SELECT * FROM downloads WHERE youtube_url = ?");
    //         $stmt->bind_param("s", $url);
    //         $stmt->execute();
    //         $result = $stmt->get_result();

    //         if ($result->num_rows > 0) {
    //             $song = $result->fetch_assoc();
    //             return [
    //                 'success' => true,
    //                 'message' => 'Bu şarkı zaten indirilmiş',
    //                 'file' => $song['file_path'],
    //                 'title' => $song['title'],
    //                 'id' => $song['id']
    //             ];
    //         }

    //         // Video bilgilerini al
    //         $command = "yt-dlp --print title --print duration --no-playlist \"$url\"";
    //         exec($command, $info, $returnCode);

    //         if ($returnCode !== 0 || empty($info)) {
    //             throw new Exception("Video bilgileri alınamadı");
    //         }

    //         $title = $info[0] ?? 'Unknown Title';
    //         $duration = $info[1] ?? '0:00';

    //         // Dosya adını temizle
    //         $safeTitle = $this->sanitizeFileName($title);
    //         $outputFile = $this->downloadPath . $safeTitle . '.mp3';

    //         // Şarkıyı indir
    //         $command = sprintf(
    //             'yt-dlp -x --audio-format mp3 --output "%s.%%(ext)s" "%s"',
    //             $this->downloadPath . $safeTitle,
    //             $url
    //         );

    //         exec($command . " 2>&1", $output, $returnCode);

    //         if ($returnCode !== 0) {
    //             throw new Exception("İndirme başarısız: " . implode("\n", $output));
    //         }

    //         // Dosya boyutunu al
    //         $fileSize = filesize($outputFile);

    //         // Veritabanına kaydet
    //         $stmt = $this->db->prepare(
    //             "INSERT INTO downloads (title, youtube_url, file_path, file_size, duration) 
    //              VALUES (?, ?, ?, ?, ?)"
    //         );

    //         $fileName = basename($outputFile);
    //         $stmt->bind_param(
    //             "sssis",
    //             $title,
    //             $url,
    //             $fileName,
    //             $fileSize,
    //             $duration
    //         );

    //         if (!$stmt->execute()) {
    //             throw new Exception("Veritabanı kaydı başarısız");
    //         }

    //         $downloadId = $this->db->insert_id;

    //         return [
    //             'success' => true,
    //             'message' => 'İndirme başarılı',
    //             'file' => $fileName,
    //             'title' => $title,
    //             'id' => $downloadId
    //         ];

    //     } catch (Exception $e) {
    //         error_log("Download error: " . $e->getMessage());
    //         return [
    //             'success' => false,
    //             'error' => $e->getMessage()
    //         ];
    //     }
    // }

    

// MusicManager.php içindeki downloadMusic metodunu güncelle

public function downloadMusic($url) {
    try {
        // URL kontrolü
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new Exception("Geçersiz URL formatı");
        }

        // URL'nin daha önce indirilip indirilmediğini kontrol et
        $stmt = $this->db->prepare("SELECT * FROM downloads WHERE youtube_url = ?");
        $stmt->bind_param("s", $url);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $song = $result->fetch_assoc();
            return [
                'success' => true,
                'message' => 'Bu şarkı zaten indirilmiş',
                'file' => $song['file_path'],
                'title' => $song['title'],
                'id' => $song['id']
            ];
        }

        // Kuyruğa ekle
        $queueProcessor = new QueueProcessor();
        return $queueProcessor->addToQueue($url);

    } catch (Exception $e) {
        error_log("Download error: " . $e->getMessage());
        return [
            'success' => false,
            'error' => $e->getMessage()
        ];
    }
}
    
    
    public function getSongById($id) {
        $stmt = $this->db->prepare("SELECT * FROM downloads WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function getDownloadedSongs() {
        $songs = [];
        $result = $this->db->query("
            SELECT d.*, l.lyrics_text 
            FROM downloads d 
            LEFT JOIN lyrics l ON d.id = l.download_id 
            ORDER BY d.download_date DESC
        ");

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $songs[] = $row;
            }
        }

        return $songs;
    }

    private function sanitizeFileName($fileName) {
        // Dosya adından özel karakterleri temizle
        $fileName = preg_replace('/[^a-zA-Z0-9]+/', '-', $fileName);
        $fileName = trim($fileName, '-');
        return substr($fileName, 0, 200); // Max 200 karakter
    }

    public function __destruct() {
        $this->db->close();
    }
}
?>