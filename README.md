## Soupmix APCu Cache Adaptor

[![Build Status](https://travis-ci.org/soupmix/cache-apcu.svg?branch=master)](https://travis-ci.org/soupmix/cache-apcu) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/soupmix/cache-apcu/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/soupmix/cache-apcu/?branch=master) [![Codacy Badge](https://api.codacy.com/project/badge/Grade/f2fd85aaddc44793bfc25020802ee5f2)](https://www.codacy.com/app/mehmet/cache-apcu?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=soupmix/cache-apcu&amp;utm_campaign=Badge_Grade) 
[![Latest Stable Version](https://poser.pugx.org/soupmix/cache-apcu/v/stable)](https://packagist.org/packages/soupmix/cache-apcu) [![Total Downloads](https://poser.pugx.org/soupmix/cache-apcu/downloads)](https://packagist.org/packages/soupmix/cache-apcu) [![Latest Unstable Version](https://poser.pugx.org/soupmix/cache-apcu/v/unstable)](https://packagist.org/packages/soupmix/cache-apcu) [![License](https://poser.pugx.org/soupmix/cache-apcu/license)](https://packagist.org/packages/soupmix/cache-apcu) [![composer.lock](https://poser.pugx.org/soupmix/cache-apcu/composerlock)](https://packagist.org/packages/soupmix/cache-apcu) [![Code Coverage](https://scrutinizer-ci.com/g/soupmix/cache-apcu/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/soupmix/cache-apcu/?branch=master)


### Installation

It's recommended that you use [Composer](https://getcomposer.org/) to install Soupmix Cache APCu Adaptor.

```bash
$ composer require soupmix/cache-apcu "~0.2"
```

### Connection
```php
require_once '/path/to/composer/vendor/autoload.php';


$cache = new Soupmix\Cache\APCUCache();
```


### Soupmix APCu Cache API

[See Soupmix Cache API](https://github.com/soupmix/cache-base/blob/master/README.md)
