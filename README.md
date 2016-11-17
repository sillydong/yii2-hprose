Hprose component for Yii2.*
===========================
simple way to use hprose

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist sillydong/yii2-hprose "*"
```

or add

```
"sillydong/yii2-hprose": "*"
```

to the require section of your `composer.json` file.


Usage
-----

create a component in yii config
```
return [
    'components'=>[
        'someservice'=>[
            'class'=>'sillydong\hprose\Service',
            'urls'=>['127.0.0.1:11111','127.0.0.1:22222']
        ]
    ]
]
```
