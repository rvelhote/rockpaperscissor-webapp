<?php
/**
 * MIT License
 *
 * Copyright (c) 2016 Ricardo Velhote
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */
namespace AppBundle\Controller;

use AppBundle\Entity\Player;
use AppBundle\Entity\User;
use AppBundle\Form\AuthenticationForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Class AuthenticationController
 * @package AppBundle\Controller
 *
 */
class AuthenticationController extends Controller
{
//    /**
//     * @Method({"POST"})
//     * @Route("/api/v1/login_check", name="login")
//     */
//    public function loginAction()
//    {
//        $authenticationForm = new AuthenticationForm();
//        $options = ['csrf_protection' => false];
//        $authenticationSubmissionForm = $this->createFormBuilder($authenticationForm, $options)
//            ->add('_username', TextType::class)
//            ->add('_password', TextType::class)
//            ->add('_remember_me', TextType::class)
//            ->getForm();
//
//        //if($authenticationSubmissionForm->isValid()) {
//            return new JsonResponse(true);
//        //}
//
//        //return new JsonResponse(false, 403);
//
//
//        /** @var Session $session */
////        $session = $this->get('session');
////        $session->set('player', 1);
////        return new JsonResponse(true);
//    }

//    /**
//     * @Method({"GET"})
//     * @Route("/api/v1/register", name="register")
//     */
//    public function registerAction(Request $request)
//    {
//        $em = $this->get('doctrine')->getManager();
//        $encoder = $this->container->get('security.password_encoder');
//
////        $username = $request->request->get('_username');
////        $password = $request->request->get('_password');
//
//        $user = new Player('rvelhote');
//        $user->setPassword($encoder->encodePassword($user, 'x'));
//        $em->persist($user);
//        $em->flush($user);
//
//        return new Response(sprintf('User %s successfully created', $user->getUsername()));
//    }

    /**
     * @Method({"POST"})
     * @Route("/api/v1/logout", name="logout")
     */
    public function logoutAction()
    {
        /** @var Session $session */
        $session = $this->get('session');
        $session->invalidate();
        return new JsonResponse(true);
    }
}
