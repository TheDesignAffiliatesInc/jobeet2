<?php

namespace Stc\ScraperBundle\Model;

use Stc\ScraperBundle\Component\LibParser;
use Stc\ScraperBundle\Entity\LinksFeedsHarvested;
use Stc\ScraperBundle\Entity\ScrapeContent;
use Stc\ScraperBundle\Model\StcModelInterface;
use Doctrine\ORM\EntityManager;
use Stc\ScraperBundle\Entity\Feeds;

class LinksFeedsHarvestedModel extends Feeds implements StcModelInterface
{
    protected $em;

    public function __construct(EntityManager $entityManager, LibParser $parser)
    {
        $this->em = $entityManager;
        $this->parser = $parser;
        $this->repository = $this->em->getRepository('StcScraperBundle:Feeds');
    }

    public function getEntityManager()
    {
        return $this->em;
    }

    public function getFeeds()
    {
        $feeds = $this->repository->findAll();
        return $feeds;
    }

    private function formatUrl($dirty)
    {
        $clean = $this->parser->return_between($dirty, 'href=\"', '"', INCL);
        return $clean;
    }

    public function save($urlz)
    {
        //print_r($url);exit;
        if (is_array($urlz)) {
            foreach ($urlz as $url)
            {
                if (false === $this->linkExists($this->formatUrl($url))) {
                    $url = $this->parser->return_between($url,"href=","</a>",EXCL);
                    $linkList = new LinksFeedsHarvested();
                    $linkList->setUrl($url)
                        ->setDiscoveredAt(new \DateTime('now'))
                        ->setScrapedAt(null)
                        ->setStatus(1);

                    $this->em->persist($linkList);
                    $this->em->flush();
                }
            }
        } else {
            if (false === $this->linkExists($urlz)) {
                $linkList = new LinksFeedsHarvested();
                $urlz = $this->parser->return_between($urlz,"href=","</a>",EXCL);
                $linkList->setUrl($urlz)
                         ->setDiscoveredAt(new \DateTime('now'))
                         ->setScrapedAt(null)
                         ->setStatus(1);

                $this->em->persist($linkList);
                $this->em->flush();
            }
        }
    }

    public function linkExists($url)
    {
        /*$builder = $this->em->createQueryBuilder();
        $builder->select('l')
                ->from('links_feeds_harvested', 'l')
                ->where($builder->expr()->in('url', $url));
        $query = $builder->getQuery();
        $results = $query->getResult();*/

        $link = $this->repository->findByUrl($url);
        //print_r($link);exit;
        if (count($link) > 0) {
            return true;
        }
        return false;
    }
}