<?php

include_once('sphinxapi.php');
$conf = config::getModuleIni('sphinx_index');
print_r($conf);
$blog = config::getModuleIni('sphinx_weight_blog');
print_r( $blog);
class sphinx {
    
    public $c = null;
    function connect () {
        
        $this->c = new SphinxClient();
        $this->c->SetServer( 
                config::getModuleIni('sphinx_host'), 
                config::getModuleIni('sphinx_port') 
                );  
        $this->c->SetConnectTimeout( config::getModuleIni('sphinx_timeout') );
        $this->c->SetMatchMode(SPH_MATCH_ALL);
    }  
}

$s = new sphinx();
$s->connect();

