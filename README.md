<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://cdn.discordapp.com/attachments/710182113395212378/936268835051405343/segura.png" height="140px">
    </a>
    <h1 align="center">Yii 2 Encrypt class util</h1>
    <br>
</p>

Use the [Yii 2](http://www.yiiframework.com/) application best for
rapidly creating small projects.

This class has as business purposes to facilitate and demystify the difficulty of encrypting data using php

This first project works together, a standalone version is being developed

[![Latest Stable Version](https://img.shields.io/packagist/v/yiisoft/yii2-app-basic.svg)](https://packagist.org/packages/yiisoft/yii2-app-basic)
[![Total Downloads](https://img.shields.io/packagist/dt/yiisoft/yii2-app-basic.svg)](https://packagist.org/packages/yiisoft/yii2-app-basic)
[![build](https://github.com/yiisoft/yii2-app-basic/workflows/build/badge.svg)](https://github.com/yiisoft/yii2-app-basic/actions?query=workflow%3Abuild)

DIRECTORY STRUCTURE
-------------------

      yii2-cryptbox/        Main folter of our project
         |
         | - commands/          Folter to easy commands for generate your keys
         | - helpers/           Helpers 



REQUIREMENTS
------------

- The minimum requirement by this project template that your Web server supports PHP 5.6.0.
- Yii2 stable
- Libsodium


INSTALLATION
------------

### Install via Composer

If you do not have [Composer](http://getcomposer.org/), you may install it by following the instructions
at [getcomposer.org](http://getcomposer.org/doc/00-intro.md#installation-nix).

~~~
composer --prefer-dist tiagocomti/yii2-cryptbox
~~~

Now you be able to call our class like:

~~~php
Cryptbox::generateKeyPair("ANY_SALT_YOU_WANT")
~~~

CONFIGURATION
-------------

### Database

Edit the file `config/web.php or console.php` and add this:

```php
//Keep this name
'cryptbox' => [
   'class' => 'tiagocomti\cryptbox\Cryptbox',
   //A path to save our keys.
   'keysPath' =>  __DIR__."/../../keys/",
   // Execute: php yii cryptBox/crypt/encode '{Your key}'
   // This key will be used for 
   'secret' => '{"1":95,"2":86,"3":65,"4":77,"5":79,"6":83,"7":66,"8":82,"9":73,"10":78,"11":68,"12":65,"13":82,"14":79,"15":72,"16":79,"17":74,"18":69,"19":65,"20":77,"21":65,"22":78,"23":72,"24":65,"25":83,"26":79,"27":65,"28":68,"29":69,"30":85,"31":83,"32":95}',
   // Less security but faster
   'enableCache' => true,
   //In seconds
   'timeCache' => 800
],
```

Edit the file ` console.php` and add this:

```php
'modules' => [
     // ... other modules ...
     'cryptBox' => [
         'class' => 'tiagocomti\cryptbox\commands\Module',
     ],
 ],
```

**NOTES:**
- Check and edit the other files in the `config/` directory to customize your application as required.


Samples
-------------

###Keep your db password safe in php file - PERSONAL TIP

we need to set encrypt our password, use this command

This command will request for you a pass, NEVER pass your pass in command line, wait for shell input.
```sh
php yii cryptBox/crypt/db-password
```

```sh
php yii cryptBox/crypt/db-password
password: very_nice_pass_for_my_db
Paste it in your db conf:
{"1":67,"2":87,"3":55,"4":108,"5":86,"6":103,"7":122,"8":75,"9":79,"10":80,"11":87,"12":118,"13":106,"14":56,"15":72,"16":104,"17":47,"18":86,"19":98,"20":74,"21":116,"22":70,"23":52,"24":107,"25":50,"26":66,"27":51,"28":114,"29":65,"30":90,"31":87,"32":100,"33":53,"34":71,"35":89,"36":86,"37":106,"38":122,"39":54,"40":89,"41":109,"42":98,"43":81,"44":55,"45":48,"46":81,"47":118,"48":86,"49":111,"50":109,"51":71,"52":52,"53":73,"54":72,"55":112,"56":75,"57":50,"58":51,"59":74,"60":57,"61":77,"62":80,"63":67,"64":52,"65":50,"66":119,"67":80,"68":98,"69":113,"70":117,"71":72,"72":79,"73":113,"74":54,"75":83,"76":52,"77":74,"78":90,"79":71,"80":118,"81":66,"82":74,"83":117,"84":114,"85":56,"86":65,"87":61,"88":61}
```

in your `config/db.php`, we need to create our own Connection class and set our new encrypt password
```php
return [
    //Set your own db/connection class.
    'class' => 'app\db\Connection',
    'dsn' => 'mysql:host=127.0.0.1;dbname=database_name',
    'username' => 'user_db',
    'password' => '{"1":67,"2":87,"3":55,"4":108,"5":86,"6":103,"7":122,"8":75,"9":79,"10":80,"11":87,"12":118,"13":106,"14":56,"15":72,"16":104,"17":47,"18":86,"19":98,"20":74,"21":116,"22":70,"23":52,"24":107,"25":50,"26":66,"27":51,"28":114,"29":65,"30":90,"31":87,"32":100,"33":53,"34":71,"35":89,"36":86,"37":106,"38":122,"39":54,"40":89,"41":109,"42":98,"43":81,"44":55,"45":48,"46":81,"47":118,"48":86,"49":111,"50":109,"51":71,"52":52,"53":73,"54":72,"55":112,"56":75,"57":50,"58":51,"59":74,"60":57,"61":77,"62":80,"63":67,"64":52,"65":50,"66":119,"67":80,"68":98,"69":113,"70":117,"71":72,"72":79,"73":113,"74":54,"75":83,"76":52,"77":74,"78":90,"79":71,"80":118,"81":66,"82":74,"83":117,"84":114,"85":56,"86":65,"87":61,"88":61}',
    'charset' => 'utf8',
];
```

Look that example of our new Connection class

```php
<?php
namespace app\db;

use app\helpers\Strings;
use yii\db\Exception;

class Connection extends \yii\db\Connection
{
    /**
     * @throws Exception
     */
    public function __construct($config = [])
    {
        \Yii::info("Iniciando base de dados", "api");
        return parent::__construct([
            'dsn' => $config["dsn"],
            'username' => $config["username"],
            'password' => Cryptbox::decryptDBPass($config["password"]),
            'charset' => $config["charset"],
        ]);
    }
}
```

Lets check our configuration 
```sh
php yii cryptBox/crypt/check-db
```

**TIP:**

If you need to change the password and you have cache enable, please run: 
```sh
php yii cache/flush-all
```


###Save encrypted content to file with asymmetric encrypt

If you want to use the system secret for encrypt these file use like this:
```php
return Cryptbox::safeWriteInFile("/tmp/teste", "teste", Cryptbox::getOurSecret());
```

If you want to use the system secret for encrypt these file use like this:
```php
return Cryptbox::safeWriteInFile("/tmp/teste", "teste", "Very Nice Secret");
```
