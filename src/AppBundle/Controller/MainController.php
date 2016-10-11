<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

use AppBundle\Entity\User;
use AppBundle\Entity\Info;
use AppBundle\Entity\Teacher;
use AppBundle\Entity\Course;
use AppBundle\Entity\Chapter;
use AppBundle\Entity\Lesson;
use AppBundle\Entity\Attachment;
use AppBundle\Entity\Student;

class MainController extends Controller
{
    /**
     * @Route("/", name="indexpage")
     */
    public function indexAction(Request $request)
    {
        $auth_checker = $this->get('security.authorization_checker');
        $token = $this->get('security.token_storage')->getToken();
        $u = $token->getUser();
        $isUser = $auth_checker->isGranted('ROLE_USER');

        if($isUser){
            return $this->redirectToRoute('homepage');
        }else{
            return $this->render('site/index.html.twig', [
                'info' => $this->getInfo(),
                'actualities' => $this->getActuality()
                ]);
        }
    }
    /**
     * @Route("/profile", name="profilepage")
     */
    public function profileAction(Request $request)
    {
        $auth_checker = $this->get('security.authorization_checker');
        $token = $this->get('security.token_storage')->getToken();
        $u = $token->getUser();
        $isUser = $auth_checker->isGranted('ROLE_USER');
        $entity = null;

        
        if($isUser){
            if($u->getType() == 2){
                $em = $this->getDoctrine()->getManager();
                $entity = $em->getRepository('AppBundle:Teacher')->findOneByUser($u);
            }


            return $this->render('site/profile.html.twig', [
            'info' => $this->getInfo(),
            'actualities' => $this->getActuality(),
            'entity' => $entity
            ]);

        }else{
            return $this->redirectToRoute('indexpage');
        }
    }

    /**
     * @Route("/login", name="security_login")
     * @Method({"GET", "POST"})
     */
    public function connectAction(Request $request)
    {

        $auth_checker = $this->get('security.authorization_checker');
        $token = $this->get('security.token_storage')->getToken();
        $u = $token->getUser();
        $isUser = $auth_checker->isGranted('ROLE_USER');


        $session = $request->getSession();

        if (class_exists('\Symfony\Component\Security\Core\Security')) {
            $authErrorKey = Security::AUTHENTICATION_ERROR;
            $lastUsernameKey = Security::LAST_USERNAME;
        } else {
            $authErrorKey = SecurityContextInterface::AUTHENTICATION_ERROR;
            $lastUsernameKey = SecurityContextInterface::LAST_USERNAME;
        }

        if ($request->attributes->has($authErrorKey)) {
            $error = $request->attributes->get($authErrorKey);
        } elseif (null !== $session && $session->has($authErrorKey)) {
            $error = $session->get($authErrorKey);
            $session->remove($authErrorKey);
        } else {
            $error = null;
        }

        if (!$error instanceof AuthenticationException) {
            $error = null;
        }

        $lastUsername = (null === $session) ? '' : $session->get($lastUsernameKey);

        if ($this->has('security.csrf.token_manager')) {
            $csrfToken = $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue();
        } else {
            $csrfToken = $this->has('form.csrf_provider')
                ? $this->get('form.csrf_provider')->generateCsrfToken('authenticate')
                : null;
        }

        if($isUser){
            return $this->redirectToRoute('homepage');
        }else{
            return $this->render('site/login.html.twig', [            
                'last_username' => $lastUsername,
                'error' => $error,
                'info' => $this->getInfo(),
                'csrf_token' => $csrfToken,
            ]);
        }
    }

    /**
     * @Route("/login_check", name="security_check")
     * @Method({"POST"})
     */
    public function checkAction()
    {
        throw new \RuntimeException('You must configure the check path to be handled by the firewall using form_login in your security firewall configuration.');
    }

    /**
     * @Route("/logout", name="security_logout")
     * @Method({"GET"})
     */
    public function logoutAction()
    {
        throw new \RuntimeException('You must activate the logout in your security firewall configuration.');
    }


    /**
     * @Route("/home", name="homepage")
     */
    public function homeAction(Request $request)
    {
        $auth_checker = $this->get('security.authorization_checker');
        $token = $this->get('security.token_storage')->getToken();
        $u = $token->getUser();
        $isUser = $auth_checker->isGranted('ROLE_USER');
        $entity = null;

        if(!$isUser){
            return $this->redirectToRoute('indexpage');
        }else{

            if($u->getType() == 2){
                $em = $this->getDoctrine()->getManager();
                $entity = $em->getRepository('AppBundle:Teacher')->findOneByUser($u);
            }else if($u->getType() == 0){
                $em = $this->getDoctrine()->getManager();
                $entity = $em->getRepository('AppBundle:Student')->findOneByUser($u);
            }

            return $this->render('site/home.html.twig', [
                'info' => $this->getInfo(),
                'actualities' => $this->getAllActuality(),
                'entity' => $entity
                ]);
        }

    }

    /**
     * @Route("/student/courses", name="StudentSubjectsPage")
     */
    public function studentsubjectsAction(Request $request)
    {
        $auth_checker = $this->get('security.authorization_checker');
        $token = $this->get('security.token_storage')->getToken();
        $u = $token->getUser();
        $isUser = $auth_checker->isGranted('ROLE_USER');

        $em = $this->getDoctrine()->getManager();
        $student = $em->getRepository('AppBundle:Student')->findOneByUser($u);

        if(!$isUser || $u->getType() != 0 ){
            return $this->redirectToRoute('indexpage');
        }else{
            return $this->render('site/student.courses.html.twig', [
                'info' => $this->getInfo(),
                'actualities' => $this->getAllActuality(),
                'student' => $student
                ]);
        }

    }
     /**
     * @Route("/course/{id}", name="StudentSubjectPage")
     */
    public function studentsubjectAction(Request $request, $id)
    {
        $auth_checker = $this->get('security.authorization_checker');
        $token = $this->get('security.token_storage')->getToken();
        $u = $token->getUser();
        $isUser = $auth_checker->isGranted('ROLE_USER');

        $em = $this->getDoctrine()->getManager();
        $student = $em->getRepository('AppBundle:Student')->findOneByUser($u);
        $course = $em->getRepository('AppBundle:Course')->findOneById($id);

        if(!$isUser || $u->getType() != 0 ){
            return $this->redirectToRoute('indexpage');
        }else{
            return $this->render('site/student.course.html.twig', [
                'info' => $this->getInfo(),
                'actualities' => $this->getAllActuality(),
                'student' => $student,
                'course' => $course
                ]);
        }

    }
    
    /**
     * @Route("/subjects", name="coursepage")
     */
    public function courseAction(Request $request)
    {
        $auth_checker = $this->get('security.authorization_checker');
        $token = $this->get('security.token_storage')->getToken();
        $u = $token->getUser();
        $isUser = $auth_checker->isGranted('ROLE_USER');
        $courses = null;
        $teacher = null;

        if($u->getType() == 2){
            $em = $this->getDoctrine()->getManager();
            $teacher = $em->getRepository('AppBundle:Teacher')->findOneByUser($u);
            $courses = $em->getRepository('AppBundle:Course')->findByTeacher($teacher);
        }



        if($isUser && $u->getType() == 2){
            return $this->render('site/teacher.course.html.twig', [
                'info' => $this->getInfo(),
                'teacher' => $teacher,
                'courses' => $courses
                ]);
        }else{
            return $this->redirectToRoute('indexpage');
        }

    }

    /**
     * @Route("/subject/{id}", name="Teacher_course")
     */
    public function courseAddAction(Request $request, $id)
    {
     $auth_checker = $this->get('security.authorization_checker');
        $token = $this->get('security.token_storage')->getToken();
        $u = $token->getUser();
        $isUser = $auth_checker->isGranted('ROLE_USER');

        $em = $this->getDoctrine()->getManager();
        $teacher = $em->getRepository('AppBundle:Teacher')->findOneByUser($u);
        $course = $em->getRepository('AppBundle:Course')->findOneById($id);

        if($isUser && $u->getType() == 2 && $course->getTeacher() == $teacher){
            return $this->render('site/teacher.course.chapter.html.twig', [
                'info' => $this->getInfo(),
                'teacher' => $teacher,
                'course' => $course
                ]);
        }else{
            return $this->redirectToRoute('indexpage');
        }
    }

    /**
     * @Route("/subject/{id}/chapter", name="Teacher_chapter")
     */
    public function chapterAddAction(Request $request, $id)
    {
        $auth_checker = $this->get('security.authorization_checker');
        $token = $this->get('security.token_storage')->getToken();
        $u = $token->getUser();
        $isUser = $auth_checker->isGranted('ROLE_USER');

        $em = $this->getDoctrine()->getManager();
        $teacher = $em->getRepository('AppBundle:Teacher')->findOneByUser($u);
        $course = $em->getRepository('AppBundle:Course')->findOneById($id);


        if ($request->isMethod('POST')) {
            $data = $request->request->all();

            $chapter = new Chapter();
            $chapter->setNum($data['num']);
            $chapter->setTitle($data['title']);
            $chapter->setSlug($data['slug']);
            $chapter->setDescription($data['description']);
            $chapter->setCourse($course);

           $em->persist($chapter);
           $em->flush();

           return $this->redirectToRoute('Teacher_course', ['id' => $id]);


        }

        if($isUser && $u->getType() == 2 && $course->getTeacher() == $teacher){
            return $this->render('site/teacher.course.chapter.add.html.twig', [
                'info' => $this->getInfo(),
                'teacher' => $teacher,
                'course' => $course
                ]);
        }else{
            return $this->redirectToRoute('indexpage');
        }

    }
    
    /**
     * @Route("/subject/{id}/lesson", name="Teacher_lesson")
     */
    public function lessonAddAction(Request $request, $id)
    {
        $auth_checker = $this->get('security.authorization_checker');
        $token = $this->get('security.token_storage')->getToken();
        $u = $token->getUser();
        $isUser = $auth_checker->isGranted('ROLE_USER');

        $em = $this->getDoctrine()->getManager();
        $teacher = $em->getRepository('AppBundle:Teacher')->findOneByUser($u);
        $chapter = $em->getRepository('AppBundle:Chapter')->findOneById($id);


        if ($request->isMethod('POST')) {
            $data = $request->request->all();
            $file = $request->files->get('attachment');

            $lesson = new Lesson();
            $lesson->setTitle($data['title']);
            $lesson->setSlug($data['slug']);
            $lesson->setDescription($data['description']);
            $lesson->setType($data['type']);
            $lesson->setContent($data['content']);
            $lesson->setChapter($chapter);
            $em->persist($lesson);
            $em->flush();


            if($file != null){
                $attachment = new Attachment();

                $attachment->setName($file);
                $file = $attachment->getName();

                 $fileName = md5(uniqid()).'.'.$file->guessExtension();
                 $type = $file->guessExtension();
                $file->move(
                    $this->container->getParameter('documents_directory'),
                    $fileName
                );

                $attachment->setName($fileName); 
                $attachment->setType($type);
                $attachment->setLesson($lesson);

                $em->persist($attachment);
                $em->flush();
            }
           return $this->redirectToRoute('Teacher_course', ['id' => $chapter->getCourse()->getId()]);


        }

        if($isUser && $u->getType() == 2 && $chapter->getCourse()->getTeacher() == $teacher){
            return $this->render('site/teacher.course.lesson.add.html.twig', [
                'info' => $this->getInfo(),
                'teacher' => $teacher,
                'chapter' => $chapter
                ]);
        }else{
            return $this->redirectToRoute('indexpage');
        }

    }
    
    /**
     * @Route("/subject/{id}/attachment", name="Teacher_attachment")
     */
    public function attachmentAddAction(Request $request, $id)
    {
        $auth_checker = $this->get('security.authorization_checker');
        $token = $this->get('security.token_storage')->getToken();
        $u = $token->getUser();
        $isUser = $auth_checker->isGranted('ROLE_USER');

        $em = $this->getDoctrine()->getManager();
        $teacher = $em->getRepository('AppBundle:Teacher')->findOneByUser($u);
        $lesson = $em->getRepository('AppBundle:Lesson')->findOneById($id);


        if ($request->isMethod('POST')) {
            $file = $request->files->get('attachment');

            if($file != null){
                    $attachment = new Attachment();

                    $attachment->setName($file);
                    $file = $attachment->getName();

                     $fileName = md5(uniqid()).'.'.$file->guessExtension();
                     $type = $file->guessExtension();
                    $file->move(
                        $this->container->getParameter('documents_directory'),
                        $fileName
                    );

                    $attachment->setName($fileName); 
                    $attachment->setType($type);
                    $attachment->setLesson($lesson);

                    $em->persist($attachment);
                    $em->flush();
            }

           return $this->redirectToRoute('Teacher_course', ['id' => $lesson->getChapter()->getCourse()->getId()]);


        }

        if($isUser && $u->getType() == 2 && $lesson->getChapter()->getCourse()->getTeacher() == $teacher){
            return $this->render('site/teacher.course.attachment.add.html.twig', [
                'info' => $this->getInfo(),
                'teacher' => $teacher,
                'lesson' => $lesson
                ]);
        }else{
            return $this->redirectToRoute('indexpage');
        }

    }

////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////

    public function getInfo(){
        $em = $this->getDoctrine()->getManager();
        $info = $em->getRepository('AppBundle:Info')->findOneById(1);
        return $info;
    }
    public function getActuality(){
        $em = $this->getDoctrine()->getManager();
        $info = $em->getRepository('AppBundle:Actuality')->findBy(
                   array('target' => 1),        // $where 
                   array('date' => 'DESC'),    // $orderBy ASC ou DESC
                   5,                        // $limit
                   0                          // $offset
                 );
        return $info;
    }
    public function getAllActuality(){
        $em = $this->getDoctrine()->getManager();
        $info = $em->getRepository('AppBundle:Actuality')->findAll();
        return $info;
    }
    public function getTeacherCourses($teacher){
        $em = $this->getDoctrine()->getManager();
        $courses = $em->getRepository('AppBundle:Course')->findByTeacher($teacher);
        return $courses;
    }
}
