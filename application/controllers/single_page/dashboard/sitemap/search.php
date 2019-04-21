<?php
namespace Application\Controller\SinglePage\Dashboard\Sitemap;

use Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Core\Page\Controller\DashboardSitePageController;
use Concrete\Controller\Element\Search\Pages\Header;

class Search extends \Concrete\Controller\SinglePage\Dashboard\Sitemap\Search
{
    public $helpers = array('form');

    public function csv()
    {
        $token = \Core::make('token');
        if (!$token->validate('sitemap-csv')) {
            throw new \Exception($token->getErrorMessage());
        }

        header('Content-Type: text/csv');
        header('Cache-control: private');
        header('Pragma: public');
        $fileName = 'site_map';
        $date = date('Ymd');
        header('Content-Disposition: attachment; filename=' . $fileName . "_page_list{$date}.csv");

        $fp = fopen('php://output', 'w');

        // write the columns
        $row = [
            t('Id'),
            t('Name'),
            t('Path'),
            t('Page Type'),
            t('Page Type Name'),
            t('最新バージョンが承認')
        ];

        fputcsv($fp, $row);
        $search = $this->app->make('Concrete\Controller\Search\Pages');
        $pages = $search->getCurrentSearchObject()->getItemListObject()->getResults();

        foreach ($pages as $page) {
            if (is_object($page)) {
                $pageVersionList = new \Concrete\Core\Page\Collection\Version\VersionList($page);
                $row = [
                    $page->getCollectionID(),
                    t($page->getCollectionName()),
                    $page->getCollectionLink(),
                    $page->getCollectionTypeHandle(),
                    $page->getCollectionTypeName(),
                    $pageVersionList->getPage(-1)[0]->cvIsApproved ==1 ? 'true' : 'false',
                ];
                fputcsv($fp, $row);
            }
        }
        fclose($fp);
        die;
    }
}
