<?php
// LyricsScraper.php
class LyricsScraper {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function searchAndSaveLyrics($downloadId, $songTitle, $artist = '') {
        try {
            // Şarkı adını ve sanatçıyı URL için hazırla
            $searchQuery = urlencode($this->cleanTitle($songTitle));
            if (!empty($artist)) {
                $searchQuery .= '+' . urlencode($artist);
            }

            // Lyrics.com arama sayfasını çek
            $searchUrl = "https://www.lyrics.com/serp.php?st=" . $searchQuery;
            $searchHtml = $this->fetchUrl($searchUrl);

            // İlk sonucu bul
            preg_match('/<a href="\/lyric\/[0-9]+\/[^"]+"/', $searchHtml, $matches);
            
            if (empty($matches)) {
                throw new Exception("Şarkı sözü bulunamadı: $songTitle");
            }

            // Şarkı sözü sayfası URL'sini al
            preg_match('/\/lyric\/[0-9]+\/[^"]+/', $matches[0], $linkMatches);
            $lyricUrl = "https://www.lyrics.com" . $linkMatches[0];

            // Şarkı sözü sayfasını çek
            $lyricHtml = $this->fetchUrl($lyricUrl);

            // Şarkı sözlerini çıkar
            preg_match('/<pre id="lyric-body-text"[^>]*>(.*?)<\/pre>/s', $lyricHtml, $lyricMatches);
            
            if (empty($lyricMatches[1])) {
                throw new Exception("Şarkı sözü çıkarılamadı");
            }

            $lyrics = trim(strip_tags(html_entity_decode($lyricMatches[1])));

            // Veritabanına kaydet
            $stmt = $this->db->prepare("
                INSERT INTO lyrics (download_id, lyrics_text, source, language) 
                VALUES (?, ?, 'lyrics.com', 'unknown')
                ON DUPLICATE KEY UPDATE lyrics_text = ?, source = 'lyrics.com'
            ");

            $stmt->bind_param("iss", $downloadId, $lyrics, $lyrics);
            $stmt->execute();

            return [
                'success' => true,
                'lyrics' => $lyrics
            ];

        } catch (Exception $e) {
            error_log("Lyrics error: " . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    private function fetchUrl($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        
        $result = curl_exec($ch);
        
        if (curl_errno($ch)) {
            throw new Exception("CURL Error: " . curl_error($ch));
        }
        
        curl_close($ch);
        return $result;
    }

    private function cleanTitle($title) {
        // Parantez içindekiler ve gereksiz metinleri temizle
        $title = preg_replace('/\([^)]*\)|\[[^\]]*\]|Official Video|Official Music Video|Official Audio|Lyrics|HD|HQ/i', '', $title);
        // Gereksiz boşlukları temizle
        $title = trim(preg_replace('/\s+/', ' ', $title));
        return $title;
    }
}
?>