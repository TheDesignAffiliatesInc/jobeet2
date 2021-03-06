<?php
/**
 * User: Jesse
 * Date: 8/25/13
 * Time: 3:20 PM
 */

namespace Stc\ScraperBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class HtmlScrapeCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('scraper:html')
            ->setDescription('Run the html scraper against all current active websites');
        //->addArgument('name', InputArgument::OPTIONAL, 'Who do you want to greet?')
        //->addOption('yell', null, InputOption::VALUE_NONE, 'If set, the task will yell in uppercase letters')
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $contentLogic = $this->getContainer()->get('stc_scraper.logic.content');
        $contentModel = $this->getContainer()->get('stc_scraper.model.content');
        $parser = $this->getContainer()->get('stc_scraper.parser');
        $results = $contentLogic->startFeedScraper();


        foreach ($results as $res) {
            //$headers = $res->getDateCreated();
            $headers = $res->getLink();
            $headers .= $res->getTitle();
            $data = $res->saveXml();
            $contentModel->save(array(
                    'headers' => $headers,
                    'data' => serialize($data)
                ));

            //print_r($links);
        }

    }
}