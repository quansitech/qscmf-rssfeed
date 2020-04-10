<?php
namespace Qsrssfeed;

use Bootstrap\Provider;
use Bootstrap\RegisterContainer;
use Qsrssfeed\Controller\RssfeedController;

class RssfeedProvider implements Provider{

    public function register(){
        C('URL_MAP_RULES', [
            'rss' => 'extends/rssfeed/index'
        ]);

        RegisterContainer::registerController('Extends', 'Rssfeed', RssfeedController::class);
    }
}