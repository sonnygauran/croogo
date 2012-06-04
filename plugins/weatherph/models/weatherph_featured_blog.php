<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class WeatherphFeaturedBlog {
    
    
    public function featuredBlog(){
        
        
        $featured = $this->Node->find('all', array(
            'order' => 'Node.created DESC',
            'conditions' => array('Node.type' => 'blog'),
            'limit' => 5,
        ));
        
        //debug($featured);
        
        
    }
    
    
}

?>
