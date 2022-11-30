<?php 

namespace LPACK;

use GuzzleHttp\Client;

class LeadPack {

    private $client;
    
    /// mantiene in memoria il risultato della chiamata getUniversities
    private $universities = [];
    
    /// mantiene in memoria il risultato della chiamata getCourses
    private $courses = [];

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

        if( empty( $this->courses ) ) {
            
            $result = $this->apiGet( 'course/table/list' );

            $this->courses = isset( $result['status'] ) && $result['status'] == 200 ? $result['courses'] : [];
        }

        return $this->courses;
    }

    /**
     * Ritorna un array associativo con i dati del singolo corso
     *
     * @param string $slug
     * @return array
     */
    public function getCourseBySlug( string $slug ) {

        $courses = $this->getCourses();
        
        $slugCourses = array_filter( $courses, fn( $course ) => $course['slug'] == $slug );
        
        return empty( $slugCourses ) ? [] : array_shift( $slugCourses );
    }

    /**
     * Ritorna un array associativo con i dati del singolo corso
     *
     * @param string $slug
     * @return array
     */
    public function getCourseById( int $id ) {

        $courses = $this->getCourses();
        
        $slugCourses = array_filter( $courses, fn( $course ) => $course['id'] == $id );
        
        return empty( $slugCourses ) ? [] : array_shift( $slugCourses );
    }


    /**
     * Ritorna una array associativo con l'elenco di tutte le università
     *
     * @return array
     */
    public function getUniversities( $filter = [] ) {

        if( empty( $this->universities ) ) {

            $result = $this->apiGet( 'university/list' );

            $this->universities = isset( $result['status'] ) && $result['status'] == 200 ? $result['universities'] : [];
        }

        return $this->universities;
    }

    public function getUniversity( string $slug ) {

        $universities = $this->getUniversities();
        
        $slugUniversities = array_filter( $universities, fn( $uni ) => $uni['slug'] == $slug );
        
        return empty( $slugUniversities ) ? [] : array_shift( $slugUniversities );
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
