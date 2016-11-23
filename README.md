# Dravencms timeline module

This is a simple timeline module for dravencms

## Instalation

The best way to install dravencms/timeline is using  [Composer](http://getcomposer.org/):


```sh
$ composer require dravencms/timeline:@dev
```

Then you have to register extension in `config.neon`.

```yaml
extensions:
	timeline: Dravencms\Timeline\DI\TimelineExtension
```
