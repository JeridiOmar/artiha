<?php

namespace App\Controller;

use App\Entity\ChangePwd;
use App\Entity\User;
use App\Form\ResetType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
use Swift_Mailer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class ForgotPwdController extends AbstractController
{
    /**
     * @Route("/forgot/pwd", name="forgot_pwd")
     * @param Request $request
     * @param UserRepository $userrepo
     * @param Swift_Mailer $mailer
     * @param SessionInterface $session
     * @return Response
     */
    public function index(Request $request,UserRepository $userrepo,Swift_Mailer $mailer,SessionInterface $session)
    {
        $email=$request->get('email');
        if($email){
            $session->set('email',$email);
            $code = rand(100000,999999);
            $session->set('code',$code);
            $personne = $userrepo->findOneBy(array('Email' => "$email"));
            if ($personne) {
                //send code to verification mail
                $message = (new \Swift_Message('Verification Code'))
                    ->setFrom('artzy.proj@gmail.com')
                    ->setTo("$email")
                    ->setBody("verification code : $code");
                $mailer->send($message);
                //$this->addFlash('success', 'code envoyé !');
                return $this->render('forgot_pwd/index.html.twig', ['code' => $code]);
            } else {
                $this->addFlash('erreur', 'email non existant !');
            }
            return $this->redirectToRoute('forgot_pwd');

        }
        elseif ($_POST==["email" => ""]){
            $this->addFlash('erreur', 'champs vides !');
        }
        return $this->render('forgot_pwd/index.html.twig', [
            'controller_name' => 'ForgotPwdController',
        ]);
    }

    /**
     * @Route("/resend/pwd", name="pwd_treatment")
     * @param Swift_Mailer $mailer
     * @param SessionInterface $session
     * @return Response
     */
    public function index2( Swift_Mailer $mailer,SessionInterface $session)
    {
        $code = $session->get('code');
        $email=$session->get('email');
        //send code to verification mail
        $message = (new \Swift_Message('Verification Code'))
            ->setFrom('artzy.proj@gmail.com')
            ->setTo("$email")
            ->setBody("verification code : $code");
        $mailer->send($message);
        //$this->addFlash('success', 'code envoyé !');
        return $this->render('forgot_pwd/resend.html.twig', ['code' => $code]);
    }

    /**
     * @Route("/reset", name="reset")
     * @param UserPasswordEncoderInterface $encoder
     * @param SessionInterface $session
     * @param UserRepository $repository
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function reset(UserPasswordEncoderInterface $encoder,SessionInterface $session,UserRepository $repository,Request $request,EntityManagerInterface $entityManager){
        $user=new User();
        $user=$repository->findOneBy(['Email'=>$session->get('email')]);
        $change=new ChangePwd();
        $change->setUser($user);
        $change->setUseroldpwd($user->getPassword());
        $form=$this->createForm(ResetType::class,$change);
        //dd($request);
        $form->handleRequest($request);
        //dd($user,"not if");
        if($form->isSubmitted() && $form->isValid()){
            $change->setDateDeChangement(new \DateTime());
            $user->addChangePwd($change);
            $user->setPassword($encoder->encodePassword($user, $change->getNewpwd()));
            //dd($user);
            $entityManager->persist($change);
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->render("forgot_pwd/index.html.twig");
        }
        return $this->render('forgot_pwd/reset.html.twig', ['form' => $form->createView(),]);
    }

    /**
     * @Route("/verification", name="verification")
     * @param Request $request
     * @param SessionInterface $session
     * @param \Swift_Mailer $mailer
     * @param UserRepository $repository
     * @return Response
     */
    public function verify(Request $request, SessionInterface $session, \Swift_Mailer $mailer, UserRepository $repository)
    {
        $code_client = $request->get('code');
        $code_server = $session->get('code');
        if ($code_client == $code_server) {
            //send mail containing old password
            $message = (new \Swift_Message('change your password for security mesurments!'))
                ->setFrom('artzy.proj@gmail.com')
                ->setTo($session->get('email'))
                ->setBody("this is body")
                ->addPart('
                    <h3>your request to change your forgotten password was received here is your old pwssword</h3>
                    <h4>' . $repository->findOneBy(['Email' => $session->get('email')])->getPassword() . '</h4>
                    <h4>if it wasn\'t you please verify your account information!</h4>',
                    'text/html');
            $mailer->send($message);
            return $this->redirectToRoute('reset');
        } else {
            //send email to zk6 there has been an attempt to reset your password was it you ?
            $message = (new \Swift_Message('Anonymous attempt to connect'))
                ->setFrom('artzy.proj@gmail.com')
                ->setTo($session->get('email'))
                ->setBody("this is body")
                ->addPart('
                    <h3>There has been an attempt to reset your password was it you ?</h3>
                    <h4>if it wasn\'t you please verify your account information!</h4>',
                    'text/html');
            $mailer->send($message);
            return $this->render('forgot_pwd/resend.html.twig', ['false_attempt' => true]);
        }
    }
}
