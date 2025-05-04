<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Response;
use App\Models\Tour;
use Carbon\Carbon;

class SitemapController extends Controller
{
    public function index()
    {
        // Sitemap cache'lenebilir, ancak burada her istek için yeniden oluşturuyoruz
        $content = $this->generateSitemapXml();
        
        return Response::make($content, 200, [
            'Content-Type' => 'application/xml'
        ]);
    }
    
    private function generateSitemapXml()
    {
        // XML başlangıcı
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>';
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        
        // Ana sayfa
        $sitemap .= $this->addUrl(URL::to('/'), Carbon::now()->toIso8601String(), 'daily', '1.0');
        
        // Statik sayfalar
        $staticPages = [
            '/about' => 'weekly',
            '/contact' => 'monthly',
            '/tours' => 'daily',
            '/sss' => 'weekly',
        ];
        
        foreach ($staticPages as $url => $changefreq) {
            $sitemap .= $this->addUrl(URL::to($url), Carbon::now()->toIso8601String(), $changefreq, '0.8');
        }
        
        // Dinamik içerik: Turlar
        try {
            $tours = Tour::all();
            foreach ($tours as $tour) {
                $sitemap .= $this->addUrl(
                    URL::to('/tour/' . $tour->id),
                    $tour->updated_at->toIso8601String(),
                    'weekly',
                    '0.8'
                );
            }
        } catch (\Exception $e) {
            // Tour modeli bulunamadıysa veya veritabanına erişim yoksa sessizce devam et
        }
        
        // XML sonlandırma
        $sitemap .= '</urlset>';
        
        return $sitemap;
    }
    
    private function addUrl($url, $lastmod, $changefreq, $priority)
    {
        return 
            '<url>' .
                '<loc>' . $url . '</loc>' .
                '<lastmod>' . $lastmod . '</lastmod>' .
                '<changefreq>' . $changefreq . '</changefreq>' .
                '<priority>' . $priority . '</priority>' .
            '</url>';
    }
}
