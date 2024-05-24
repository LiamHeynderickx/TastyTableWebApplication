<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AboutUsTest extends WebTestCase
{
    public function testAboutUsPageIsSuccessful()
    {
        // Create a client to make requests
        $client = static::createClient();

        // Request the About Us page
        $crawler = $client->request('GET', '/aboutUs');

        // Output the HTML content for debugging (unnecessary once test is working)
        //echo $client->getResponse()->getContent();

        // Assert that the response status code is 200; 200 = successful request
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Fetch the text content of the h1 tag within the about-section div
        $h1Text = trim($crawler->filter('.about-section h1')->text());
        echo "H1 text content: " . $h1Text . "\n";

        // Assert that the h1 tag contains the text "About Us"
        $this->assertEquals('About Us', $h1Text);

        //we use symfony domcrawler component to search for elements in the html
        // Assert that a specific image is present in slideshow
        $this->assertGreaterThan(
            0,
            //crawler finds target img[src*="tastyTableTeam_1.jpg, and if found, count++
            $crawler->filter('img[src*="tastyTableTeam_1.jpg"]')->count(),
            //if count <= 0, error message sent
            'Missing image: tastyTableTeam_1.jpg'
        );

        $this->assertGreaterThan(
            0,
            //crawler finds target img[src*="tastyTableTeam_1.jpg, and if found, count++
            $crawler->filter('img[src*="tastyTableTeam_2.jpg"]')->count(),
            //if count <= 0, error message sent
            'Missing image: tastyTableTeam_2.jpg'
        );

        $this->assertGreaterThan(
            0,
            //crawler finds target img[src*="tastyTableTeam_1.jpg, and if found, count++
            $crawler->filter('img[src*="tastyTableTeam_3.jpg"]')->count(),
            //if count <= 0, error message sent
            'Missing image: tastyTableTeam_3.jpg'
        );

        $this->assertGreaterThan(
            0,
            //crawler finds target img[src*="tastyTableTeam_1.jpg, and if found, count++
            $crawler->filter('img[src*="tastyTableTeam_4.jpg"]')->count(),
            //if count <= 0, error message sent
            'Missing image: tastyTableTeam_4.jpg'
        );

        // Assert that the team member "Roman Laurent Benfeghoul" is present
        $this->assertGreaterThan(
            0,
            $crawler->filter('.card:contains("Roman Laurent Benfeghoul")')->count(),
            'Missing team member: Roman Laurent Benfeghoul'
        );
        $this->assertGreaterThan(
            0,
            //crawler finds target img[src*="tastyTableTeam_1.jpg, and if found, count++
            $crawler->filter('img[src*="roman_webtech.jpg"]')->count(),
            //if count <= 0, error message sent
            'Missing image: roman_webtech.jpg'
        );

        // Assert that the team member "Esat Caglayan" is present
        $this->assertGreaterThan(
            0,
            $crawler->filter('.card:contains("Esat Caglayan")')->count(),
            'Missing team member: Esat Caglayan'
        );
        $this->assertGreaterThan(
            0,
            //crawler finds target img[src*="tastyTableTeam_1.jpg, and if found, count++
            $crawler->filter('img[src*="esat_webtech.jpg"]')->count(),
            //if count <= 0, error message sent
            'Missing image: esat_webtech.jpg'
        );

        // Assert that the team member "Mithil Pidigacandy" is present
        $this->assertGreaterThan(
            0,
            $crawler->filter('.card:contains("Mithil Pidigacandy")')->count(),
            'Missing team member: Mithil Pidigacandy'
        );
        $this->assertGreaterThan(
            0,
            //crawler finds target img[src*="tastyTableTeam_1.jpg, and if found, count++
            $crawler->filter('img[src*="mithil_webtech.jpg"]')->count(),
            //if count <= 0, error message sent
            'Missing image: mithil_webtech.jpg'
        );


        // Assert that the team member "Ramazan Yetismis" is present
        $this->assertGreaterThan(
            0,
            $crawler->filter('.card:contains("Ramazan Yetismis")')->count(),
            'Missing team member: Ramazan Yetismis'
        );
        $this->assertGreaterThan(
            0,
            //crawler finds target img[src*="tastyTableTeam_1.jpg, and if found, count++
            $crawler->filter('img[src*="ramazan_webtech.jpg"]')->count(),
            //if count <= 0, error message sent
            'Missing image: ramazan_webtech.jpg'
        );


        // Assert that the team member "Liam Heynderickx" is present
        $this->assertGreaterThan(
            0,
            $crawler->filter('.card:contains("Liam Heynderickx")')->count(),
            'Missing team member: Liam Heynderickx'
        );

        // Assert that the dropdown menu has the correct links
        $this->assertGreaterThan(
            0,
            $crawler->filter('.dropdown-content a[href*="/homePage"]')->count(),
            'Missing link: Homepage'
        );

        // Assert that the slideshow container is present
        $this->assertGreaterThan(
            0,
            $crawler->filter('.slideshow-container')->count(),
            'Missing slideshow container'
        );

        // Assert that the footer contains the About Us link
        $this->assertGreaterThan(
            0,
            $crawler->filter('footer a[href*="/aboutUs"]')->count(),
            'Missing link: About Us in footer'
        );
    }
}
