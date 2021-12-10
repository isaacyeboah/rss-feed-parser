<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Validator;
use App\Jobs\ParseRSSFeedJob;
use Illuminate\Console\Command;

use App\Support\RSSFeedParser;

class ParseRSSFeed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parser:rss-feed {--queue}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse a podcast RSS feed and store podcast metadata and its episodes into a database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $rssFeed = $this->askValid('Please, specify your RSS feed', 'rssFeed');

        try {
            if ($this->option('queue') == true) {
                ParseRSSFeedJob::dispatch($rssFeed);

                $this->info('RSS queued for processing');

            } else {
                $this->info('Begin RSS feed processing');
    
                $feedParser = new RSSFeedParser($rssFeed);
                $feedParser->parse();

                $this->info('RSS feed processed successfully');
            }
        } catch (\Exception $e) {
            $this->error($e->getMessage());

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    protected function askValid($question, $rssFeed)
    {
        $rssFeed = $this->ask($question);

        if ($message = $this->validateInput($rssFeed)) {
            $this->error($message);

            return $this->askValid($question, $rssFeed);
        }

        return $rssFeed;
    }

    public function validateInput($rssFeed)
    {
        return (! filter_var($rssFeed, FILTER_VALIDATE_URL)) 
            ? "Invalid URL '$rssFeed'" 
            : null; 
    }
}
