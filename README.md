# Css Compress

## Installation

```
composer require herokeyboard/csscompress:dev-main
```

## Usage

```php
require_once ('vendor/autoload.php');
use \Herokeyboard\CssCompress;

$array = ["style.css","style2.css"]; 
$css = new CssCompress();
$result = $css->compress("true", $array);
echo $result;

```
