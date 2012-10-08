<?php

namespace Instudies\AdminBundle\Controller\Management;

use
	Symfony\Bundle\FrameworkBundle\Controller\Controller,
	Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
	Sensio\Bundle\FrameworkExtraBundle\Configuration\Template,
    Symfony\Component\HttpFoundation\Request
;

use
	Instudies\AdminBundle\Form\Management\User\Find\ManagementUserFindType,
    Instudies\AdminBundle\Form\Management\User\Edit\ManagementUserEditType
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
    			'findForm' => $findForm->createView(),
                'menu_active' => array(2 => array(1 => 1)),
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
        /**
         * @var \Instudies\SiteBundle\Entity\UserRepository $userRepository
         */

        if ($id) {
            $user = $userRepository->findOneById(intval($id));
        } elseif ($email) {
            $user = $userRepository->findOneByEmail($email);
        }

        if (!$user instanceof \Instudies\SiteBundle\Entity\User) {
            throw $this->createNotFoundException('Такого пользователя не существует');
        }
        if ($id){
            $form_action = 'id';
        }
        else{
            $form_action = 'email';
        }
        $editForm = $this->createForm(new ManagementUserEditType(), $user);
        if ($request->getMethod() == 'POST'){
            if ($request->get('delete_button')){
                $userRepository->delete($user);
                $this->get('session')->setFlash('notice_flash_success', 'User '.$user->getEmail().' has been deleted.');
                return $this->redirect($this->generateUrl('admin_management_user'));
            }
            else{
                $editForm->bindRequest($request);
                if ($editForm->isValid()) {
                    $user = $editForm->getData();
                    /**
                     * @var \Instudies\SiteBundle\Entity\User $user
                     */
                    if ($user->getPlainPassword()){
                        $userRepository->updatePassword($user, $this->get('security.encoder_factory'));
                    }
                    $userRepository->save($user);
                    $this->get('session')->setFlash('notice_flash_success', 1);
                }
                else{
                    $this->get('session')->setFlash('notice_flash_error', 1);
                }
            }
        }
        return array(
                'user' => $user,
                'form_action' => $form_action,
                'edit_form' => $editForm->createView(),
                'menu_active' => array(2 => array(1 => 1)),
            );

    }

}
