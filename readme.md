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
	'class'=> Zelenin\yii\modules\I18n\components\I18N::className(),
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
php yii migrate --migrationPath=@vendor/zelenin/yii2-i18n-module/migrations
```

Go to http://backend.yourdomain.com/web/i18n/default/index for translating your messages

## Info

See [Yii2 i18n guide](https://github.com/yiisoft/yii2/blob/master/docs/guide/tutorial-i18n.md)

## Author

[Aleksandr Zelenin](https://github.com/zelenin/), e-mail: [aleksandr@zelenin.me](mailto:aleksandr@zelenin.me)
