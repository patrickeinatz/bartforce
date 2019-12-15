<?php

namespace App\Services;

class ForumService
{
    /**
     * @param string $ytLink
     * @return string
     */
    public function makeYouTubeEmbedLink(string $ytLink): string
    {
        if(!strpos($ytLink, 'embed/')){
            $embedLink = str_replace('watch?v=','embed/', $ytLink);
            return $embedLink;
        }

        return $ytLink;
    }
}