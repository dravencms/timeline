# Dravencms timeline module

This is a simple timeline module for dravencms

## Instalation

The best way to install salamek/dravencms-timeline is using  [Composer](http://getcomposer.org/):


```sh
$ composer require salamek/dravencms-timeline:@dev
```

Then you have to register extension in `config.neon`.

```yaml
extensions:
	timeline: App\Timeline\DI\TimelineExtension
```
