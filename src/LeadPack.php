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
     * Trasforma i caratteri '-' in '_' in modo ricorsivo 
     * su tutte le chiavi dell'array associativo in ingresso
     *
     * @param array $array
     * @return array
     */
    private function arrayKeysMinus2Underscore( $array ) {

        $keys = array_keys( $array );

        return array_reduce( $keys, function ( $newArray, $key ) use ( $array ) {

            $newKey = is_string( $key ) ? str_replace( '-', '_', $key ) : $key;

            $newArray[ $newKey ] = is_array( $array[ $key ] ) ? $this->arrayKeysMinus2Underscore( $array[ $key ] ) : $array[ $key ];

            return $newArray;

        }, []);
    }
    

    /**
     * Ritorna una array associativo con l'elenco di tutti i corsi
     *
     * @return array
     */
    public function getCourses() {

        if( empty( $this->courses ) ) {
            
            $result = $this->apiGet( 'course/table/list' );

            $courses = isset( $result['status'] ) && $result['status'] == 200 ? $result['courses'] : [];

            $this->courses = $this->arrayKeysMinus2Underscore( $courses );

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

            $universities = isset( $result['status'] ) && $result['status'] == 200 ? $result['universities'] : [];

            $this->universities = $this->arrayKeysMinus2Underscore( $universities );
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

        $result = $this->apiGet( 'degree-class/list', $filter );
        $class = isset( $result['status'] ) && $result['status'] == 200 ? $result['degreeClasses'] : [];
        return $class;
        
    }

    public function getSede( $filter = [] ) {

        $result = $this->apiGet( 'university-location/list', $filter );
        $sede = isset( $result['status'] ) && $result['status'] == 200 ? $result['universityLocations'] : [];
        return $sede;

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

        $transient_name = $api_call . implode('',$filter);

        // do_action( 'qm/start', "apiGet:{$api_call}" );

        $data = get_transient( $transient_name );

        if( $data === false ) {

            $response = $this->client->get( $api_call, $query );     

            if( empty($response) ) return [];


            /// prende la stringa in ingresso
            $string_data = $response->getBody()->getContents();

            if( empty( $string_data ) ) return [];

            $data = json_decode( $string_data, true );

            set_transient( $transient_name, $data, DAY_IN_SECONDS * 10 );
        }

        // do_action( 'qm/stop', "apiGet:{$api_call}" );

        return $data;
    }
}
