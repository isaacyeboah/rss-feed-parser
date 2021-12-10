<?php

namespace App\Support;

use App\Models\Podcast;
use Illuminate\Console\Command;

class RSSFeedParser {
    private $rssFeed;

    public function __construct($rssFeed){
        $this->rssFeed = $rssFeed;
    }
    
    public function parse() {
        $rss = $this->validateRssLink();

        foreach ($rss->channel as $channel){
            $podcast = $this->savePodcast($channel);

            $this->saveEpisods($podcast, $channel);
        }
    }

    private function savePodcast($channel) {
        $podcast = Podcast::create([
            'title' => (string) $channel->title,
            'artwork_url' => $this->getXmlAttribute($channel->xpath('//itunes:image')[0], 'href'),
            'rss_feed_url' => $this->rssFeed,
            'description' => (string) $channel->description,
            'language' => (string) $channel->language,
            'website_url' => (string) $channel->link
        ]);

        error_log("Saved podcast with id $podcast->id");

        return $podcast;
    }

    private function saveEpisods($podcast, $channel) {
        foreach ($channel->item as $item) {
            $episod = $podcast->episods()->create([
                'title' => (string) $item->title,
                'description' => (string) $item->description,
                'audio_url' => $this->getXmlAttribute($item->enclosure, 'url'),
                'episode_url' => (string) $item->link
            ]);

            error_log("Saved episode with id $episod->id");
        }
    }

    private function getXmlAttribute($object, $attribute)
    {
        if (isset($object[$attribute]))
            return (string) $object[$attribute];
    }

    private function validateRssLink(){
        try {
            $rss = simplexml_load_file($this->rssFeed);
            if ($rss === false) {
                throw new \Exception("Failed loading XML");
            }
            return $rss;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}