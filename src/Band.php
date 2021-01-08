<?php

namespace Aivo;

class Band
{
    private $api;

    const CLIENT_ID = '17488f08b3b849daacf0559a690421b8';
    const CLIENT_SECRET = '08323204e1b44447b8c93705e274d61e';

    /**
     * New Band instance.
     * @param $guzzle 
     */
    public function __construct()
    {
        $this->api = new \SpotifyWebAPI\SpotifyWebAPI();
    }

    /**
     * Get token from Spotify API
     * @return String 
     */
    public function authorize()
    {
        $session = new \SpotifyWebAPI\Session(
            self::CLIENT_ID,
            self::CLIENT_SECRET
        );        
        $session->requestCredentialsToken();
        return $session->getAccessToken();
    }

    /**
     * Get band ID searching by name
     * @param String $bandName
     * @return String|Array 
     */
    public function getBandID($bandName)
    {
        $this->api->setAccessToken($this->authorize());
        $info = $this->convertToArray($this->api->search($bandName,'artist'));
        if(count($info['artists']['items'])==0){
            return [
                'status' => 'Error',
                'mensaje' => 'No se encontraron artistas con ese nombre'
            ];
        }
        return $info['artists']['items'][0]['id'];
    }

    /**
     * Get information of the artist's albums
     * @param String $bandName
     * @return Array 
     */
    public function getInformation($bandName)
    {
        $bandIDResponse = $this->getBandID($bandName);
        if(gettype($bandIDResponse) != 'string'){
            return $bandIDResponse;
        }
        $albumsInformation = $this->convertToArray($this->api->getArtistAlbums($bandIDResponse));
        return $this->formatResponse($albumsInformation);
    }

    /**
     * Convert std object to array
     * @return Array 
     */
    private function convertToArray($object)
    {
        return json_decode(json_encode($object), true);
    }

    /**
     * Mapping response
     * @return Array 
     */
    private function formatResponse($albumsInformation)
    {
        $albums = [];
        foreach($albumsInformation['items'] as $a){
            array_push($albums,[
                "name" => $a['name'],
                "released" => $a['release_date'],
                "tracks" => $a['total_tracks'],
                "cover" => [
                    "height" => $a['images'][0]['height'],
                    "width" => $a['images'][0]['width'],
                    "url" => $a['images'][0]['url']
                ]
            ]);
        }
        return $albums;
    }
}
