<?php

namespace Instudies\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin")
 */
class IndexController extends Controller
{
    /**
     * @Route("/", name="admin_index")
     * @Template()
     */
    public function indexAction()
    {

    	return array();

    }

    /**
     * @Template()
     */
    public function menuAction( $menu_active = null ){
        $menu = array(
            1 => array(
                'title' => 'Статистика',
                'url' => 'admin_statistics_counter',
                'active' => 0,
                'sub' => array(
                    1 => array(
                        'title' => 'Активность пользователей',
                        'url' => '',
                        'active' => 0,
                    ),
                    2 => array(
                        'title' => 'Активность групп',
                        'url' => '',
                        'active' => 0,
                    ),
                    3 => array(
                        'title' => 'Активные пользователи',
                        'url' => '',
                        'active' => 0,
                    ),
                    4 => array(
                        'title' => 'Счетчики',
                        'url' => 'admin_statistics_counter',
                        'active' => 0,
                    )
                ),
            ),
            2 => array(
                'title' => 'Управление',
                'url' => 'admin_management_user',
                'active' => 0,
                'sub' => array(
                    1 => array(
                        'title' => 'Управление пользователями',
                        'url' => 'admin_management_user',
                        'active' => 0,
                    ),
                ),
            ),
        );
        /*if ($menu_active){
            $search_sub_active = $menu;
            foreach($menu_active as $active){
                if (isset($search_sub_active[$active])){
                    $search_sub_active[$active]['active'] = 1;
                    if (isset($search_sub_active[$active]['sub'])){
                        $search_sub_active = $search_sub_active[$active]['sub'];
                    }
                }
            }
        }*/
        if ($menu_active){
            foreach($menu_active as $k => $active){
                if (isset($menu[$k])){
                    $menu[$k] = $this->searchActiveItem($active, $menu[$k]);
                }
            }
        }
        //echo '<pre>';var_dump($menu);die();
        return array(
            'menu' => $menu
        );
    }

    protected function searchActiveItem($active, $menu){
        $menu['active'] = 1;
        if (is_array($active)){
            foreach($active as $k => $pre_active){
                if (isset($menu['sub']) && isset($menu['sub'][$k])){
                    $menu['sub'][$k] = $this->searchActiveItem($pre_active, $menu['sub'][$k]);
                }
            }
        }
        return $menu;
    }
}
