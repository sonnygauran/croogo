<?php
/**
 * Routes
 *
 * weatherph_routes.php will be loaded in main app/config/routes.php file.
 */
    Croogo::hookRoutes('Weatherph');
/**
 * Behavior
 *
 * This plugin's Weatherph behavior will be attached whenever Node model is loaded.
 */
    Croogo::hookBehavior('Node', 'Weatherph.Weatherph', array());
/**
 * Component
 *
 * This plugin's Weatherph component will be loaded in ALL controllers.
 */
    Croogo::hookComponent('*', 'Weatherph.Weatherph');
/**
 * Helper
 *
 * This plugin's Weatherph helper will be loaded via NodesController.
 */
    Croogo::hookHelper('Nodes', 'Weatherph.Weatherph');
/**
 * Admin menu (navigation)
 *
 * This plugin's admin_menu element will be rendered in admin panel under Extensions menu.
 */
    Croogo::hookAdminMenu('Weatherph');
/**
 * Admin row action
 *
 * When browsing the content list in admin panel (Content > List),
 * an extra link called 'Weatherph' will be placed under 'Actions' column.
 */
    Croogo::hookAdminRowAction('Nodes/admin_index', 'Weatherph', 'plugin:weatherph/controller:example/action:index/:id');
/**
 * Admin tab
 *
 * When adding/editing Content (Nodes),
 * an extra tab with title 'Example' will be shown with markup generated from the plugin's admin_tab_node element.
 *
 * Useful for adding form extra form fields if necessary.
 */
    Croogo::hookAdminTab('Nodes/admin_add', 'Weatherph', 'weatherph.admin_tab_node');
    Croogo::hookAdminTab('Nodes/admin_edit', 'Weatherph', 'weatherph.admin_tab_node');
    
    App::import('Lib', 'Meteomedia.Curl');

    Curl::setAuth(Configure::read('Abfrage.username'), Configure::read('Abfrage.password'));
    
    $nextYear = strtotime(date('Y', strtotime('+1 year')).'-01-01 00:00:00') - time();
    $nextWeek = strtotime('next week wednesday') - time();
    $nextHour = strtotime('+1 hour') - time();
