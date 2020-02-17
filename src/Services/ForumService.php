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

        return $this->getReplacement('YouTube');
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

        return $this->getReplacement('Bild');
    }

    /**
     * @return string
     */
    public function getReplacement(string $type)
    {
        return 'Fehler! Der angegebene Pfad führt zu keiner gültigen '.$type.' Quelle';
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