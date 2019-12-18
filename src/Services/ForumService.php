<?php

namespace App\Services;

class ForumService
{
    /**
     * @var string
     */
    private $regExp_youTube;

    /**
     * @var string
     */
    private $regExp_img;

    /**
     * ForumService constructor.
     */
    public function __construct()
    {
        $this->regExp_youTube = '/^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=|\?v=)([^#\&\?]*).*/';
        $this->regExp_img = '(http(s?):)([/|.|\w|\s|-])*\.(?:jpg|gif|png)';
    }

    /**
     * @param string $ytLink
     * @return string
     */
    public function makeYouTubeEmbedLink(string $youTubeLink): string
    {
        $embedLink = '';

        if(preg_match($this->regExp_youTube, $youTubeLink)){
            if(!strpos($youTubeLink, 'embed/')){
                $embedLink = str_replace('watch?v=','embed/', $youTubeLink);
            }

            return $embedLink;
        }

        return '';
    }

    public function makeImageLink(string $imgLink): string
    {
        if(preg_match($this->regExp_youTube, $imgLink)){
            if(!strpos($imgLink, 'embed/')){
                $embedLink = str_replace('watch?v=','embed/', $imgLink);

                return $embedLink;
            }
        }

        return '';
    }
}