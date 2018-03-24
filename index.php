<?php

use Template\Template;

require_once 'vendor/autoload.php';

$name  = "Your name goes here";
$stuff = [ [ "Thing" => "roses", "Desc"  => "red" ],
    [ "Thing" => "violets", "Desc"  => "blue"  ],
    [ "Thing" => "you",  "Desc"  => "able to solve this" ],
    [ "Thing" => "we", "Desc"  => "interested in you" ] ];

$template = new Template(['Name' => $name, 'Stuff'=> $stuff]);
// You can add variable like this
//$template->addVariable('Name', $Name);
//$template->addVariable('Stuff', $Stuff);
// If your views are in a different path you can set your base path views
//$template->setPath( 'src/views/');
$template->render('extra');
