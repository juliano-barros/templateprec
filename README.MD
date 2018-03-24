# Template engine based on PCRE Regular expression.

This project will be an test/example how to make a template engine based on regular expression and html.

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. 

### Prerequisites

```
PHP 7.0+
Composer
```

### Instalation

download repository and install it <br>

```
git clone https://github.com/juliano-barros/templateprec.git yourdirectory
cd yourdirectory
composer install
```

Run in your server.


### Examples

In your php file

```
<?php

use Template\Template;

require_once 'vendor/autoload.php';

$name  = "Your name goes here";
$stuff = [ [ "Thing" => "roses", "Desc"  => "red" ],
           [ "Thing" => "violets", "Desc"  => "blue"  ],
           [ "Thing" => "you",  "Desc"  => "able to solve this" ],
           [ "Thing" => "we", "Desc"  => "interested in you" ] ];

$template = new Template(['Name' => $name, 'Stuff'=> $stuff]);
$template->render('extra');
```

In your extra.tmpl you should put something like code below

```
Hey {{Name}}, here's a slightly better formatted poem for you: <br>
<br>
{{#each Stuff}}
  {{Thing}} are {{Desc}}{{#unless @last }},{{else}}!{{/unless}}<br>
{{/each}}

```

#### Obs. You must put \<br\> if you want break a line, this is a template engine that will return based on HTML.

## Test.

You can see how it works on [Heroku (example)](https://templatepcre.herokuapp.com/index.html)


