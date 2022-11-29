<?php 

namespace LPACK;

use GuzzleHttp\Client;

class LeadPack {

    private $client;
    

    function __construct( private string $api, private string $apiKey ) {

        $this->client = new Client([
            
            'base_uri' => $this->api,
            'verify'   => false, // Disable validation entirely (don't do this!).            
        ]);

    }


    /**
     * Ritorna una array associativo con l'elenco di tutti i corsi
     *
     * @return array
     */
    public function getCourses() {

        return $this->apiGet( 'course/table/list' );
    }


    /**
     * Ritorna una array associativo con l'elenco di tutte le università
     *
     * @return array
     */
    public function getUniversities( $filter = [] ) {
        
        return $this->apiGet( 'university/list', $filter );
    }


    public function getForms( $filter = [] ) {

        return $this->apiGet( 'form/list', $filter );
    }


    public function getTypology( $filter = [] ) {

        return $this->apiGet( 'typology/list', $filter );
    }

    public function getArea( $filter = [] ) {

        return $this->apiGet( 'area/list', $filter );
    }

    public function getClass( $filter = [] ) {

        return $this->apiGet( 'degree-class/list', $filter );
    }



   


    /**
     * ritorna un array associativo con i dati richiesti 
     * effettuando la chiamata api_call
     *
     * @param string $api_call
     * @return array
     */
    private function apiGet( string $api_call , array $filter = [] ) {

        $query = [ 'query' => array_merge( [ 'api_key' => $this->apiKey ], $filter ) ];

        $response = $this->client->get( $api_call, $query );

        if( empty($response) ) return [];

        /// prende la stringa in ingresso
        $string_data = $response->getBody()->getContents();

        if( empty( $string_data ) ) return [];

        $data = json_decode( $string_data, true );

        return $data;
    }


    
}
