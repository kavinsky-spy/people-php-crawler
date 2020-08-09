<?php

declare(strict_types=1);

use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

require_once __DIR__ . '/../vendor/autoload.php';

\Spatie\Crawler\Crawler::create()
    ->setCrawlProfile(new class extends \Spatie\Crawler\CrawlProfile {
        public function shouldCrawl(UriInterface $url): bool {
            return $url->getHost() === 'people.php.net' &&
                (str_starts_with($url->getPath(), '/z') || $url->getPath() === '/');
        }
    })
    ->setCrawlObserver(new class extends \Spatie\Crawler\CrawlObserver {
        public function crawled(UriInterface $url, ResponseInterface $response, ?UriInterface $foundOnUrl = null) {
            // ?param
            if ($url->getQuery() !== '') {
                return;
            }
            echo (string) $url . PHP_EOL;
        }

        public function crawlFailed(UriInterface $url, RequestException $requestException, ?UriInterface $foundOnUrl = null) {
            echo $requestException->getMessage(), PHP_EOL;
        }


    })
    ->setDelayBetweenRequests(500)
    ->startCrawling("http://people.php.net/");