<?php
defined('BASEPATH') or exit('No direct script access allowed');

function get_menus()
{
    $CI = get_instance();
    $CI->load->model('Md_menu');

    $menus = $CI->Md_menu->getAllActiveParentMenu();

    foreach ($menus as $menu) {
        $menu->children = $CI->Md_menu->getAllActiveChildMenu($menu->menu_id);
         foreach ($menu->children as $menu2) {
             $menu2->child = $CI->Md_menu->getAllActiveChildMenu($menu2->menu_id);
         }
    }

    return $menus;
}

function get_logo()
{
    $CI = get_instance();
    $CI->load->model('Md_logo');

    $logo = $CI->Md_logo->getLogoHome();

    return $logo;
}
