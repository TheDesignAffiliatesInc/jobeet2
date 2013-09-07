<?php

namespace Stc\ScraperBundle\Logic;

use Stc\ScraperBundle\Model\StcModelContainer;

class ScrapeContentLogic implements StcLogicInterface
{
    protected $models = array();

    public function __construct(StcModelContainer $container)
    {
        $this->container = $container;
    }

    public function startFeedScraper()
    {
        $reader = $this->container->getModel('eko_feed.feed.reader');
        $feedModel = $this->container->getModel('stc_scraper.model.feeds');
        $feeds = $feedModel->getFeeds();
        $results = array();

        foreach ($feeds as $feed)
        {
            $url = $feed->getUrl();
            $results[$url] = $reader->load($url)->get();

        }
        return $results;
    }

    public function startWebScraper()
    {
        $scrapeModel = $this->container->getModel('stc_scraper.model.');
    }

}
