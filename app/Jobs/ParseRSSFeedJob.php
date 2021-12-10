<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Support\RSSFeedParser;

class ParseRSSFeedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 3600000;
    public $rssLink;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($rssLink)
    {
        $this->rssLink = $rssLink;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $feedParser = new RSSFeedParser($this->rssLink);
        $feedParser->parse();
    }
}
