<?php

namespace Instudies\AdminBundle\Controller\Management;

use
	Symfony\Bundle\FrameworkBundle\Controller\Controller,
	Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
	Sensio\Bundle\FrameworkExtraBundle\Configuration\Template
;

use
	Instudies\AdminBundle\Form\Management\User\Find\ManagementUserFindType
;

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

        $findForm   = $this->createForm(new ManagementUserFindType());

    	return array(
    			'findForm' => $findForm->createView()
    		);

    }

    /**
     * @Route("/edit", name="admin_management_user_edit")
     * @Template()
     */
    public function editAction($id = null, $email = null)
    {

        return array();

    }

}
