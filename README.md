# rssfeed

## 安装
```php
composer require quansitech/qscmf-rssfeed
```

## 用法
配置PackageConfig.php(位于qscmf根目录，没有可以自行创建)
```php
//添加
'quansitech/qscmf-rssfeed' => [
    'feeds' => [
        'title' => '这是我的rss',  //订阅标题
        'items' => [  //被定于的数据库源
            '新闻' => [
                'model' => \Common\Model\NewsModel::class
            ],
            '活动' => [
                'model' => \Common\Model\TribuneModel::class
            ]
        ]
    ]
]
```

model实现 Qsrssfeed\Rssfeed 接口
```php
//以NewsModel为例
public function toFeedItem()
{
    //定义rss字段与数据源的字段映射
    return [
        'id' => 'id',  //必填
        'title' => 'title', //必填
        'summary' => 'content', 
        'updated' => 'publish_date', //必填
        'link' => 'link',
        'author' => 'author'
    ];
}

//这里定义取得数据条目，这里只取最新的50条
public function getFeedItems()
{
    $ents = $this->where(['status' => DBCont::NORMAL_STATUS])->order('publish_date desc')->page(1, 50)->select();
    foreach($ents as &$ent){
        $ent['link'] = U('/home/activities/detail', ['id' => $ent['id']], false, true);
    }
    return $ents;
}
```

最后可以通过 http://域名/rss 就可以访问到生成的rss脚本
