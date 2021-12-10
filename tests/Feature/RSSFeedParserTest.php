<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RSSFeedParserTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_rss_parser_with_valid_rss_link()
    {
        $link = "https://ca-ns-1.bulkstorage.ca/v1/AUTH_0d09c87549084f4ba4ad4a9e807d0d76/11278/feeds/2Z1NJ.xml?temp_url_sig=2e376730d701ec438e41860eafba6627c105b0ff&temp_url_expires=1923141286&inline";
        $this->artisan('parser:rss-feed')
            ->expectsQuestion('Please, specify your rss link', $link)
            ->assertSuccessful()
            ->expectsOutput('RSS processed successfully');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_rss_job_parser_with_valid_rss_link()
    {
        $link = "https://ca-ns-1.bulkstorage.ca/v1/AUTH_0d09c87549084f4ba4ad4a9e807d0d76/11278/feeds/2Z1NJ.xml?temp_url_sig=2e376730d701ec438e41860eafba6627c105b0ff&temp_url_expires=1923141286&inline";
        $this->artisan('parser:rss-feed --queue')
            ->expectsQuestion('Please, specify your rss link', $link)
            ->expectsOutput('RSS queued for processing')
            ->assertSuccessful();
    }


    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_rss_parser_with_invalid_rss_link()
    {
        $link = $this->faker->url();
        $this->artisan('parser:rss-feed')
            ->expectsQuestion('Please, specify your rss link', $link)
            ->assertFailed();
    }
}


