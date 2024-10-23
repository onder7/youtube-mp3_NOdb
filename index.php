<?php
// index.php
require 'vendor/autoload.php';

class YoutubeDownloader {
    private $downloadPath;

    public function __construct() {
        $this->downloadPath = __DIR__ . '/downloads/';
        if (!file_exists($this->downloadPath)) {
            mkdir($this->downloadPath, 0777, true);
        }
    }

    public function download($url) {
        try {
            // YouTube-DL komutunu hazırla
            $output = $this->downloadPath . '%(title)s.%(ext)s';
            $command = "yt-dlp -x --audio-format mp3 -o \"$output\" \"$url\"";
            
            // Komutu çalıştır
            exec($command . " 2>&1", $output, $returnCode);
            
            if ($returnCode === 0) {
                // Son indirilen dosyayı bul
                $files = glob($this->downloadPath . "*.mp3");
                $lastFile = end($files);
                
                return [
                    'success' => true,
                    'file' => basename($lastFile),
                    'path' => 'downloads/' . basename($lastFile)
                ];
            } else {
                throw new Exception("İndirme hatası: " . implode("\n", $output));
            }
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>YouTube MP3 İndirici</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .download-btn { 
            min-width: 130px; 
        }
        .alert {
            word-break: break-word;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">YouTube MP3 İndirici</h3>
                    </div>
                    <div class="card-body">
                        <form method="post" class="mb-4">
                            <div class="input-group">
                                <input type="text" name="url" class="form-control" 
                                       placeholder="YouTube URL'sini yapıştırın" required>
                                <button type="submit" class="btn btn-primary download-btn">
                                    İndir
                                </button>
                            </div>
                        </form>

                        <?php
                        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['url'])) {
                            $downloader = new YoutubeDownloader();
                            $result = $downloader->download($_POST['url']);

                            if ($result['success']) {
                                echo "<div class='alert alert-success'>";
                                echo "Dosya başarıyla indirildi!<br>";
                                echo "<a href='{$result['path']}' class='btn btn-success mt-2' download>
                                        MP3'ü İndir</a>";
                                echo "</div>";
                            } else {
                                echo "<div class='alert alert-danger'>Hata: {$result['error']}</div>";
                            }
                        }
                        ?>

                        <div class="mt-4">
                            <h5>Son İndirilenler:</h5>
                            <ul class="list-group">
                                <?php
                                $files = glob('downloads/*.mp3');
                                $files = array_slice(array_reverse($files), 0, 5);
                                foreach ($files as $file) {
                                    $name = basename($file);
                                    echo "<li class='list-group-item d-flex justify-content-between align-items-center'>";
                                    echo htmlspecialchars($name);
                                    echo "<a href='downloads/" . urlencode($name) . "' class='btn btn-sm btn-success download-btn' download>
                                            İndir</a>";
                                    echo "</li>";
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>