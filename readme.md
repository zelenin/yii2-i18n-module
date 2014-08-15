# Yii2 I18N module

[Yii2](http://www.yiiframework.com) i18n (internalization) module makes the translation of your application so simple

## Installation

### Composer

The preferred way to install this extension is through [Composer](http://getcomposer.org/).

Either run

```
php composer.phar require zelenin/yii2-i18n-module "dev-master"
```

or add

```
"zelenin/yii2-i18n-module": "dev-master"
```

to the require section of your ```composer.json```

## Usage

Configure I18N component in common config:

```php
'i18n' => [
	'class' => Zelenin\yii\modules\I18n\components\I18N::className(),
	'languages' => ['ru-RU', 'de-DE', 'it-IT']
],
```

Configure I18N component in backend config:

```php
'modules' => [
	'i18n' => Zelenin\yii\modules\I18n\Module::className()
],
```

Run:

```
php yii migrate --migrationPath=@Zelenin/yii/modules/I18n/migrations
```

Go to ```http://backend.yourdomain.com/translations``` for translating your messages

### PHP to DB import

If you have an old project with PHP-based i18n you may migrate to DbSource via console.

Run:

```
php yii i18n/import @common/messages
```

where ```@common/messages``` is path for app translations

### DB to PHP export

Run:

```
php yii i18n/export @Zelenin/yii/modules/I18n/messages zelenin/modules/i18n
```

where ```@Zelenin/yii/modules/I18n/messages``` is path for app translations and ```zelenin/modules/i18n``` is translations category in DB

### Using ```yii``` category with DB

Import translations from PHP files

```
php yii i18n/import @yii/messages
```

Configure I18N component:

```php
'i18n' => [
    'class'=> Zelenin\yii\modules\I18n\components\I18N::className(),
    'languages' => ['ru-RU', 'de-DE', 'it-IT'],
    'translations' => [
        'yii' => [
            'class' => yii\i18n\DbMessageSource::className()
        ]
    ]
],
```

## Info

Component uses yii\i18n\MissingTranslationEvent for auto-add of missing translations to database

See [Yii2 i18n guide](https://github.com/yiisoft/yii2/blob/master/docs/guide/tutorial-i18n.md)

## Author

[Aleksandr Zelenin](https://github.com/zelenin/), e-mail: [aleksandr@zelenin.me](mailto:aleksandr@zelenin.me)
