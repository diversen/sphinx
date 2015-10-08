<?php

namespace modules\sphinx;
include_once('vendor/neutron/sphinxsearch-api/sphinxapi.php');

use diversen\html;
use diversen\lang;
use diversen\moduleloader;
use diversen\pagination as pearPager;
use diversen\conf as config;

moduleloader::includeModule ('content/article');


class module {
    
    var $p = null;
    
    

    public function indexAction() {

        $s = new self();
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
            echo lang::translate('Enter search term:');
        }
    }

    function form () {
        
        $f = new html();
        $f->init(null, 'submit');
        $f->formStart('sphinx_search', 'get');
        
        $options = config::getModuleIni('sphinx_index');
        
        $select = array ();
        $select[] = array ('id' => 'all', 'title' => lang::translate('sphinx_search_all_index'));
        foreach ($options as $key => $option ) {
            $select[] = array ('id' => $option, 'title' => lang::translate($option));
        }
        
        $f->label('index', lang::translate('sphinx_site_selection'));
        $f->select('index', $select);
        
        
        $f->label('search', lang::translate('sphinx_search_website'));
        $f->text('search');
        $f->submit('submit', lang::translate('submit'));
        $f->formEnd();
        echo $f->getStr();
    }
    
    
    function search ($term, $index, $from = 0, $limit = 10) {
        $cl = new \SphinxClient();
        $cl->SetServer( "localhost", 9312 );
        
        $cl->SetMatchMode( SPH_MATCH_ANY );
        
        $limit = config::getModuleIni('sphinx_per_page');
        $cl->SetLimits((int)$from, (int)$limit);
        //$cl->SetFilter( 'model', array( 3 ) );
        
        $result = $cl->Query( $term, $index );
        if ( $result === false ) {
            echo "Query failed: " . $cl->GetLastError() . "\n";
        } else {
            if ( $cl->GetLastWarning() ) {
                echo "WARNING: " . $cl->GetLastWarning() . "";
            }
            return $result;
        }
    }
    
    function displayResults ($term, $index = "*", $from = 0, $limit = 10) {
        
        // get total number
        $num = $this->search($term, $index, $from, $limit);
        $p = new pearPager($num['total_found'], config::getModuleIni('sphinx_per_page'));
        
        // get results with correct from and  limit
        $result = $this->search($term, $index, $p->from, $limit);
        $this->showSearch($result);
        echo $p->getPagerHTML();
        
    }
    
    function showSearch ($result) {
        
        $this->generateSearchHeader($result);
        if ( ! empty($result["matches"]) ) {
            foreach ( $result["matches"] as $docinfo ) {
                $this->generateHit($docinfo);
            }
        }

    }
    
    
    function generateSearchHeader($result) {
        $str = $result['total_found'];
        $str.= ' ' . lang::translate('sphinx_results_found');
        html::headline($str);
        
    }
    
    function generateHit ($result) {
        $index = $this->getIndexFromCategory($result['attrs']['category']);
        $class_path = moduleloader::moduleReferenceToModulePath($index);
        moduleloader::includeModule($class_path);
        $class = moduleloader::modulePathToClassName($index);
        $class::sphinxHit ($result['attrs']['real_id']);
    }
    
    function getIndexFromCategory ($category) {
        $ary = config::getModuleIni('sphinx_index');
        $index = $ary[$category];
        return $index;
    }
}
