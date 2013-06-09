## API Documentation Generator


Given API method schema, the following class will generate a mardown documentation of the method.

This is a proof of concept.


- [Input](schema.json)
- [Output](method.md)


### Usage

```php
<?php

require 'generator.php';

$api_schema = 'schema.json';
$generator  = new DocGenerator($api_schema);

echo $generator->compile();

?>
```
