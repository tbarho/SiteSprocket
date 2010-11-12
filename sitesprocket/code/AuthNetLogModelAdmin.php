<?php

/**
 * AuthNetLogModelAdmin class.
 * 
 * @extends ModelAdmin
 */
class AuthNetLogModelAdmin extends ModelAdmin
{
    static $managed_models = array(
        'AuthNetLog'
    );
    
    static $menu_title = 'AuthNet';
    static $url_segment = 'authnetadmin';

    public static $collection_controller_class = 'AuthNetLogModelAdmin_CollectionController';
    
    
    
}

class AuthNetLogModelAdmin_CollectionController extends ModelAdmin_CollectionController
{
       
}