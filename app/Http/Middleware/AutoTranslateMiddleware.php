<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response as IlluminateResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Stichoza\GoogleTranslate\GoogleTranslate;
use DOMDocument;
use DOMXPath;

class AutoTranslateMiddleware
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        try {
            // Sirf Arabic aur sirf normal HTML responses par
            if (App::getLocale() !== 'ar' || !($response instanceof IlluminateResponse)) {
                return $response;
            }

            $contentType = $response->headers->get('Content-Type', '');
            if (stripos($contentType, 'text/html') === false) {
                return $response;
            }

            $html = $response->getContent();
            if (!is_string($html) || trim($html) === '') {
                return $response;
            }

            // <html> me RTL & lang inject
            $html = preg_replace('/<html\b([^>]*)>/i', '<html$1 lang="ar" dir="rtl">', $html, 1);

            // UTF-8 ensure
            if (stripos($html, '<meta charset=') === false) {
                $html = preg_replace('/<head\b([^>]*)>/i', '<head$1><meta charset="UTF-8">', $html, 1);
            }

            libxml_use_internal_errors(true);
            $dom = new DOMDocument('1.0', 'UTF-8');
            $dom->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOERROR | LIBXML_NOWARNING);

            $xpath = new DOMXPath($dom);
            // Script/Style k text ko skip karo
            $nodes = $xpath->query('//text()[not(ancestor::script) and not(ancestor::style) and not(ancestor::noscript) and normalize-space()]');

            $tr = new GoogleTranslate('ar');
            $tr->setSource('en');

            foreach ($nodes as $node) {
                $original = $node->nodeValue;

                // Sirf wo text jisme alphabets hon (numbers/punctuation skip)
                if (preg_match('/^\s*[\p{N}\p{P}]+$/u', $original)) {
                    continue;
                }

                $cacheKey = 'tr:ar:' . md5($original);
                $translated = Cache::remember($cacheKey, now()->addDays(30), function () use ($tr, $original) {
                    return $tr->translate($original);
                });

                if (is_string($translated) && $translated !== '') {
                    $node->nodeValue = $translated;
                }
            }

            $newHtml = $dom->saveHTML();

            // Basic RTL CSS (agar chahiye)
            if (stripos($newHtml, '/*rtl-injected*/') === false) {
                $rtlCss = '<style>/*rtl-injected*/ body{direction:rtl;text-align:right}</style>';
                $newHtml = preg_replace('/<\/head>/i', $rtlCss . '</head>', $newHtml, 1);
            }

            $response->setContent($newHtml);
        } catch (\Throwable $e) {
            Log::error('AutoTranslate failed: '.$e->getMessage());

            // Safe client-side fallback inject (no Blade change)
            $html = $response->getContent();
            $snippet = <<<HTML
<div id="google_translate_element" style="display:none"></div>
<script>
function googleTranslateElementInit(){
  new google.translate.TranslateElement({pageLanguage:'en',includedLanguages:'ar,en',autoDisplay:false},'google_translate_element');
}
</script>
<script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
<script>
document.addEventListener('DOMContentLoaded',function(){
  var s=setInterval(function(){
    var sel=document.querySelector('select.goog-te-combo');
    if(sel){ sel.value='ar'; sel.dispatchEvent(new Event('change')); clearInterval(s); }
  },500);
});
</script>
HTML;
            $html = preg_replace('/<\/body>/i', $snippet . '</body>', $html, 1);
            $response->setContent($html);
        }

        return $response;
    }
}
