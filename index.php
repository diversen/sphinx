<?php

include('sphinxapi.php');



$s = new sphinx();
$s->form();

if (isset($_GET['search'])) {
    $term = $_GET['search'];
}

if (isset($_GET['search'])) {
    if (!isset($_GET['index']) || $_GET['index'] == 'all') { 
        $index = '*';
    } else {
        $index = $_GET['index'];
    }
    
    $s->displayResults($term, $index);
} else {
    echo lang::translate('sphinx_enter_term');
}