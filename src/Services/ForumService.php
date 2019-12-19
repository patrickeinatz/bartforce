<?php


namespace App\Services;

use Symfony\Component\Asset\Packages as AssetManager;
use Symfony\Component\Asset\VersionStrategy\StaticVersionStrategy;

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
        $this->regExp_youTube = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i';
        $this->regExp_img = '/(http)?s?:?(\/\/[^"\']*\.(?:png|jpg|jpeg|gif))/';
    }

    /**
     * @param string $ytLink
     * @return string
     */
    public function makeYouTubeEmbedLink(string $youTubeLink): string
    {
        if(preg_match($this->regExp_youTube, $youTubeLink)){

            preg_match($this->regExp_youTube,$youTubeLink, $matches);
            $youTubeLink = 'https://www.youtube.com/embed/'.$matches[1];

            return $youTubeLink;
        }

        return $this->getReplacement();
    }

    /**
     * @param string $imgLink
     * @return string
     */
    public function makeImageLink(string $imgLink): string
    {
        if(preg_match($this->regExp_img, $imgLink)){

            return $imgLink;
        }

        return $this->getReplacement();
    }

    /**
     * @return string
     */
    public function getReplacement()
    {
        return 'img/error/wrong_source.jpg';
    }

    /**
     * @param string $link
     * @return bool
     */
    public function isReplacement(string $link)
    {
        if($this->getReplacement() === $link){
            return true;
        }
        return false;
    }
}