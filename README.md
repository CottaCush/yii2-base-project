#  Yii2 Base Project
> A Yii 2 Base Project Template

[![Latest Stable Version](https://poser.pugx.org/cottacush/yii2-base-project/v/stable)](https://packagist.org/packages/cottacush/yii2-base-project)
[![Total Downloads](https://poser.pugx.org/cottacush/yii2-base-project/downloads)](https://packagist.org/packages/cottacush/yii2-base-project)
[![License](https://poser.pugx.org/cottacush/yii2-base-project/license)](https://packagist.org/packages/cottacush/yii2-base-project)

**Features**

- [Yii framework](http://www.yiiframework.com/) as the PHP MVC framework.
 
- Security - It sets some headers that projects applications against click-jacking and XSS.

- Assets version - This fixes issue with updates to js and css files and cached browser files.

- New Relic - Ensures that the proper routes show up in the new relic monitoring dashboard.

- Continuous Integration - Sample ant build.xml file that can be easily modified.

## Requirements

The minimum requirement by this project template that your Web server supports PHP 5.5.0.

### Install via Composer

If you do not have [Composer](http://getcomposer.org/), you may install it by following the instructions at [getcomposer.org](http://getcomposer.org/doc/00-intro.md#installation-nix).

You can then install this project template using the following command:

~~~
composer global require "fxp/composer-asset-plugin:~1.1.1"
composer create-project --prefer-dist cottacush/yii2-base-project new_project
~~~

## Virtual Host Setup

*Windows*
[Link 1](http://foundationphp.com/tutorials/apache_vhosts.php)
[Link 2](https://www.kristengrote.com/blog/articles/how-to-set-up-virtual-hosts-using-wamp)

*Mac*
[Link 1](http://coolestguidesontheplanet.com/set-virtual-hosts-apache-mac-osx-10-9-mavericks-osx-10-8-mountain-lion/)
[Link 2](http://coolestguidesontheplanet.com/set-virtual-hosts-apache-mac-osx-10-10-yosemite/)

*Debian Linux*
[Link 1](https://www.digitalocean.com/community/tutorials/how-to-set-up-apache-virtual-hosts-on-ubuntu-14-04-lts)
[Link 2](http://www.unixmen.com/setup-apache-virtual-hosts-on-ubuntu-15-04/)

Sample Virtual Host Config for Apache
```apache
<VirtualHost *:80>
    ServerAdmin admin@example.com
    DocumentRoot "<WebServer Root Dir>/yii2-base-project/app/web"
    ServerName local.yii2-base-template.com
    <Directory <WebServer Root Dir>/yii2-base-project/app/web>
       AllowOverride all
       Options -MultiViews
      Require all granted
    </Directory>
</VirtualHost>
```

## Build

Dependencies 

- [Ant](http://ant.apache.org/) 

Run build
```
ant
```

## Environment Variables
Make a copy of `.env.sample` to `.env` in the env directory.


## Docker
Inspired by [yii2-dockerized](https://github.com/codemix/yii2-dockerized/blob/master/Dockerfile)

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Security

If you discover any security related issues, please email <developers@cottacush.com> instead of using the issue tracker.

## Credits

- Adegoke Obasa <goke@cottacush.com>
- [All Contributors](https://github.com/CottaCush/yii2-base-template/graphs/contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.