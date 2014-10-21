tinysou-php
==============

[![Build Status](https://travis-ci.org/yandy/tinysou-php.svg?branch=master)](https://travis-ci.org/yandy/tinysou-php)

Tinysou PHP Client

## Usage

```php
$client = new TinySou('YOUR_TOKEN');
```

### Engine

List:

```php
$client->engines();
```

Create:

```php
$client->create_engine(array(
  'name' => 'blog', 'display_name' => 'Blog'
  ));
```

Retrieve:

```php
$client->engine('blog');
```

Update:

```php
$client->update_engine('blog', array('display_name' => 'My Blog'));
```

Delete:

```php
$client->delete_engine('blog');
```

### Collection

List:

```php
$client->collections('blog');
```

Create:

```php
$client->create_collection('blog',
  array('name' => 'posts',
       'field_types' => array(
            'title' => 'string',
            'tags' => 'string',
            'author' => 'enum',
            'date' => 'date',
            'body' => 'text'
            )
        )
);
```

Retrieve:

```php
$client->collection('blog', 'posts');
```

Delete:

```php
$client->delete_collection('blog', 'posts');
```

### Document

List:

```php
$client->documents('blog', 'posts', array('page' => 0, 'per_page' => 20));
```

Create:

```php
$client->create_document('blog', 'posts', array(
    'title' => 'My First Post',
    'tags' => ['news'],
    'author' => 'Author',
    'date' => '2014-08-16T00:00:00Z',
    'body' => 'Tinysou start online today!'
    )
);
```

Retrieve:

```php
$client->document('blog', 'posts', '293ddf9205df9b36ba5761d61ca59a29');
```

Update:

```php
$client->update_document('blog', 'posts', '293ddf9205df9b36ba5761d61ca59a29', array(
    'title' => 'First Post',
    'tags' => ['news'],
    'author' => 'Author',
    'date' => '2014-08-16T00:00:00Z',
    'body' => 'Tinysou start online today!'
    )
);
```

Delete:

```php
$client->delete_document('blog', 'posts', '293ddf9205df9b36ba5761d61ca59a29');
```

### Search

```php
$client->search('blog', array(
    'q' => 'tinysou', 'c' => 'posts',
    'page' => 0, 'per_parge' => 10,
    'filter' => array(
            'range' => array(
                'field' => "date",
                'from' => "2014-07-01T00:00:00Z",
                'to' => "2014-08-01T00:00:00Z"
            )
        ),
    'sort' => array(
        'field' => "date",
        'order' => "asc",
        'mode' => "avg"
    )
  )
);
```

### Autocomplete

```php
$client->autocomplete('blog', array('q' => 't', 'c' => 'posts'));
```

## Examples

See [examples](https://github.com/yandy/tinysou-php/tree/master/examples)

## Contributing

1. Fork it ( https://github.com/yandy/tinysou-php/fork )
2. Create your feature branch (`git checkout -b my-new-feature`)
3. Commit your changes (`git commit -am 'Add some feature'`)
4. Push to the branch (`git push origin my-new-feature`)
5. Create a new Pull Request
