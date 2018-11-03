# Pattern

Useful to extract variables from a filename following a naming convention or parsing values from a SQL string. 

Adapted from [botmans](https://github.com/botman/botman)'s way of hearing commands. 

## Installation

Using Composer:

```
composer require peterviergutz/pattern
```


## Example usages

```php
<?php
 use Pattern\Pattern;
 
 $pattern = new Pattern('{path}/{name}_{startDate}-{endDate}.{extension}');
 $variables = $pattern->parse('/path/to/file/update_XY_example.com_20180917-20180923.csv');
```

```
Array
(
    [path] => /path/to/file
    [name] => update_XY_example.com
    [startDate] => 20180917
    [endDate] => 20180923
    [extension] => csv
)
```


```php
<?php
 use Pattern\Pattern;
 
 $pattern = new Pattern('SELECT {columns} FROM {table} LIMIT {limit}');
 $variables = $pattern->parse('select foo from bar limit 42');
```

```
Array
(
    [columns] => foo
    [table] => bar
    [limit] => 42
)
```

