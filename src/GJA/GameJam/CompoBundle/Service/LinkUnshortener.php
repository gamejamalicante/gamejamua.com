<?php

namespace GJA\GameJam\CompoBundle\Service;

class LinkUnshortener
{
    public function findAndUnshortenLinks($content)
    {
        preg_match_all("/(?<url>https?\:\/\/t.co\/(?:.*?)(?:$| ))/i", $content, $matches);

        foreach ($matches['url'] as $url) {
            $content = str_replace(trim($url), $this->resolveShortedLocation($url), $content);
        }

        return $content;
    }

    protected function resolveShortedLocation($url)
    {
        $ch = curl_init($url);

        curl_setopt_array($ch, array(
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => true,
        ));

        curl_exec($ch);

        return curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
    }
}
