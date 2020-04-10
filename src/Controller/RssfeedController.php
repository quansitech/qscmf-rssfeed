<?php
namespace Qsrssfeed\Controller;

use Qsrssfeed\Rssfeed;
use Think\Controller;

class RssfeedController extends Controller{

    public function index(){
        $feeds = packageConfig('quansitech/qscmf-rssfeed', 'feeds');

        $items = [];
        foreach($feeds['items'] as $key => $item){
            $model = new $item['model']();
            if(!($model instanceof Rssfeed)){
                E('model is not implements Rssfeed');
            }

            $feed_items = $model->getFeedItems();
            $column_map = $model->toFeedItem();

            foreach($feed_items as $feed){
                $tmp = [];
                $tmp['id'] = $feed[$column_map['id']];
                $tmp['title'] = $feed[$column_map['title']];
                $tmp['summary'] = $feed[$column_map['summary']];
                $tmp['updated'] = self::formatRssDate($feed[$column_map['updated']]);
                $tmp['link'] = $feed[$column_map['link']];
                $tmp['author'] = $feed[$column_map['author']];
                $tmp['category'] = $key;

                self::checkColumn($tmp);

                $items[] = $tmp;
            }
        }

        $meta = [];
        $meta['title'] = $feeds['title'];
        $meta['link'] = U('/rss', '', false, true);
        $meta['description'] = '';
        $meta['language'] = '';
        $meta['updated'] = self::lastUpdated();
        $this->assign('items', $items);
        $this->assign('meta', $meta);

        $this->display(__DIR__ . '/../View/rss.html', 'UTF-8', 'application/xml');
    }

    protected function formatRssDate($timestamp){
        return date('D, d M Y H:i:s O', $timestamp);
    }

    protected function checkColumn($feed_item){
        if(!$feed_item['id']){
            E('id is empty');
        }

        if(!$feed_item['title']){
            E('title is empty');
        }

        if(!$feed_item['updated']){
            E('updated is empty');
        }
    }

    protected function lastUpdated()
    {
        if (!$this->items) {
            return '';
        }

        $latest = collect($this->items)->sortBy(function ($item) {
            return $item['updated'];
        })->last();



        return $latest['updated'];
    }
}