<?php
declare(strict_types=1);

namespace Cupcake\View\Helper;

use Cake\View\Helper;
use FmLabs\Curl\Curl;

/**
 * GoogleFonts helper
 */
class AssetCacheHelper extends Helper
{
//    public function font($family, $weight = '400,700')
//    {
//        $url = 'https://fonts.googleapis.com/css?family=' . $family . ':' . $weight;
//        return $this->css($url);
//    }

    /**
     * @param string $url
     * @return string
     */
    public function css(string $url): string
    {
        $cssId = md5($url);
        $cssCachedFile = WWW_ROOT . 'cache' . DS . 'css' . DS . $cssId . '.cached.css';
        $cssLocalFile = WWW_ROOT . 'cache' . DS . 'css' . DS . $cssId . '.css';
        $cssLocalUrl = '/cache/css/' . $cssId . '.css';


        if (file_exists($cssLocalFile)) {
            return '<link href="' . $cssLocalUrl . '" rel="stylesheet">';
        }

        try {
            $curl = new Curl();
            $curl->init($url);
            $curl->execute();
            $body = $curl->getResponse()->getBody();

            // Regular expression pattern to extract the URL
            $pattern = "/src: url\((.*?)\)/";

            $css = preg_replace_callback($pattern, function ($matches) use ($cssId,) {
                $fontSrcUrl = $matches[1];
                $fontFaceId = md5($fontSrcUrl);
                //debug("Font src URL: $fontSrcUrl ($cssId / $fontFaceId)");

                // get the file extension from the font url
                $ext = pathinfo($fontSrcUrl, PATHINFO_EXTENSION);
                $fontCachedFile = WWW_ROOT . 'cache' . DS . 'fonts' . DS . $fontFaceId . '.' . $ext;

                $curl = new Curl();
                $curl->init($fontSrcUrl);
                $curl->execute();
                $body = $curl->getResponse()->getBody();

                file_put_contents($fontCachedFile, $body);

                return "src: url('/cache/fonts/$fontFaceId.$ext')";
            }, $body);
            //debug($css);

            file_put_contents($cssCachedFile, $body);
            file_put_contents($cssLocalFile, $css);
            $url = $cssLocalUrl;
        } catch (\Exception $e) {
            debug($e->getMessage());
        }

        return '<link href="' . $url . '" rel="stylesheet">';
    }
}
