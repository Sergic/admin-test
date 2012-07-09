<?php

namespace Instudies\AdminBundle\Controller\Management;

use
	Symfony\Bundle\FrameworkBundle\Controller\Controller,
	Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
	Sensio\Bundle\FrameworkExtraBundle\Configuration\Template,
    Symfony\Component\HttpFoundation\Request
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
    public function indexAction(Request $request)
    {

        $findForm   = $this->createForm(new ManagementUserFindType());

        if ($request->getMethod() == 'POST') {
            $data = $findForm->bindRequest($request)->getData();
            if ($data['id']) {
                return $this->redirect($this->generateUrl('admin_management_user_edit_id', array('id' => intval($data['id']))));
            }
            if (is_string($data['email'])) {
                return $this->redirect($this->generateUrl('admin_management_user_edit_email', array('email' => $data['email'])));
            }
        }

    	return array(
    			'findForm' => $findForm->createView()
    		);

    }

    /**
     * @Route("/id/{id}/edit", name="admin_management_user_edit_id")
     * @Route("/email/{email}/edit", name="admin_management_user_edit_email")
     * @Template()
     */
    public function editAction(Request $request, $id = null, $email = null)
    {

        $userRepository = $this->getDoctrine()->getEntityManager()->getRepository('InstudiesSiteBundle:User');

        if ($id) {
            $user = $userRepository->findOneById(intval($id));
        } elseif ($email) {
            $user = $userRepository->findOneByEmail(intval($email));
        }

        if (!$user instanceof \Instudies\SiteBundle\Entity\User) {
            throw $this->createNotFoundException('Такого пользователя не существует');
        }

        return array(
                'user' => $user
            );

    }

}
