<?php 

namespace Tests;

use LPACK\LeadPack;
use PHPUnit\Framework\TestCase;

final class LeadPackTest extends TestCase {

    protected LeadPack $lp;

    const API     =  'https://leadpack.atlanticmoon.com/partner/api/';
    const API_KEY = 'c32baa8a6a5f906a8e8b380fd8ceb2b119bcf47c8fa55c9b1b7ae89bf739ae77';

    protected function setUp(): void
    {
        parent::setUp();

        $this->lp = new LeadPack( LeadPackTest::API, LeadPackTest::API_KEY );
    }


    /** @test */
    public function it_is_test_working() {
    
        $this->assertTrue( true );
    }


    /** @test */
    public function can_download_courses() {
    
        $courses = $this->lp->getCourses();

        //  print_r( array_map( fn( $course ) => [ $course['slug'], $course['id'] ],  $courses ));

        $this->assertTrue( is_array ($courses ), 'courses is array' );
    }

    /** @test */
    public function can_download_single_course_by_Slug() {
    
        $course = $this->lp->getCourseBySlug('ingegneria-informatica');

        var_dump($course);

        $this->assertTrue( is_array ( $course ), 'courses is array' );
    }

    /** @test */
    public function can_get_universities_list() {
    
        $universities = $this->lp->getUniversity( 'mercatorum' );

        $this->assertTrue( is_array ( $universities ), 'universities is array' );
    }

    

}