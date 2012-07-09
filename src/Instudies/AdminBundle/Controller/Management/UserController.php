<?php

namespace Instudies\AdminBundle\Controller\Management;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin/management/users")
 */
class UserController extends Controller
{
    /**
     * @Route("/", name="admin_management_user")
     * @Template()
     */
    public function indexAction()
    {

    	return array();

    }
}
