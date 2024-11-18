<?php

// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}

?><!DOCTYPE HTML>
<html <?php language_attributes(); ?>>

<head>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta charset="<?php bloginfo('charset') ?>">
    <meta name="viewport" content="width=device-width, minimum-scale=1, initial-scale=1, shrink-to-fit=no">

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<header>

</header>

<main class="main-wrapper">



    project/
    ├── assets/
    │   ├──js/
    │   │  └── script.js
    │   └──css/
    │       ├── style.css
    │       └── style-editor.css
    ├── blocks/
    │   ├── hero/
    │   │   ├── block.json
    │   │   ├── render.php
    │   │   ├── script.js
    │   │   └── style.css
    │   └── spacer/
    │       ├── block.json
    │       ├── render.php
    │       ├── script.js
    │       └── style.css
    │
    ├── sources/
    │   ├── blocks/
    │   │   ├── hero/
    │   │   │   ├── block.json
    │   │   │   ├── render.php
    │   │   │   ├── script.js
    │   │   │   └── style.scss
    │   │   └── spacer/
    │   │       ├── block.json
    │   │       ├── render.php
    │   │       ├── script.js
    │   │       └── style.scss
    │   ├── js/
    │   │   ├── app/
    │   │   │   └── functions.js
    │   │   └── script.js
    │   └── scss/
    │       ├── _variables.scss
    │       ├── style.scss
    │       └── style-editor.scss
    │
    └── index.php


    let entries = {
        '../assets/js/script': './js/script.js',
        '../blocks/spacer/script': './blocks/spacer/script.js',
        '../assets/css/style': './scss/style.scss',
        '../assets/css/style-editor': './scss/style-editor.scss',
        '../blocks/spacer/style': './blocks/spacer/style.scss'
    }


    blockPath:  ../assets/js
    blockName:  script
    join:  ..\..\assets\js\script

    blockPath:  ../blocks/spacer
    blockName:  script
    join:  ..\..\blocks\spacer\script

    blockPath:  ../assets/css
    blockName:  style
    join:  ..\..\assets\css\style

    blockPath:  ../assets/css
    blockName:  style-editor
    join:  ..\..\assets\css\style-editor

    blockPath:  ../blocks/spacer
    blockName:  style
    join:  ..\..\blocks\spacer\style
