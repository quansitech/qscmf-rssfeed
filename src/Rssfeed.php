<?php
namespace Qsrssfeed;

interface Rssfeed{

    public function toFeedItem();

    public function getFeedItems();
}