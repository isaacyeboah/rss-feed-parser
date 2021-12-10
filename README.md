## About 

This is a simple but fun and interactive console command app build with Laravel that accepts an RSS feed, parses it, and stores the relevant data into the database.

## Requirements

This app runs Laravel Sail, is a light-weight command-line interface for interacting with Laravel's default Docker development environment which comes with some toolings out of the box used in this prject. Example

- Redis for running oue queues
- Mysql as database

You will want to use the Laravel Sail instructions to quickly set up a local project [Laravel Sail instructions](https://laravel.com/docs/8.x/installation#getting-started-on-macos)

## Development
clone this repository and cd into it

Configure your .env file by making a copy from .env.example

To run migration, use the command `./vendor/bin/sail migrate`.

Now run `./vendor/bin/sail up` to start app server 

## Usage

Run the artisan console command which will prompt you for an RSS feed

`./vendor/bin/sail artisan parser:rss-feed` <br />
`> Please, specify your rss feed:`

In scenarios when you have larger RSS feeds to process, its recommended to run it as a job by specifying the `--queue` flag an option to run the parser like so

`./vendor/bin/sail artisan parser:rss-feed --queue` <br />
`> Please, specify your rss feed:`

Make sure to start the queue worker in a separate terminal like so
`./vendor/bin/sail artisan queue:work` <br />

## Testing
Configure your .env.testing file by making a copy from .env.example

To run tests, use the command `./vendor/bin/sail test`. 
For coverage test with xdebug use `SAIL_XDEBUG_MODE=coverage ./vendor/bin/sail test --coverage-html=coverage`
