<?php

namespace AppBundle\Controller;

use AppBundle\Entity\GroupModule;
use AppBundle\Entity\Module;
use AppBundle\Entity\Privilege;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Model\UserInterface;

use AppBundle\Entity\User;
use AppBundle\Entity\Teacher;
use AppBundle\Entity\Department;
use AppBundle\Entity\Classe;
use AppBundle\Entity\Groups;
use AppBundle\Entity\Course;
use AppBundle\Entity\Info;
use AppBundle\Entity\Actuality;
use AppBundle\Entity\Student;
use AppBundle\Entity\Result;

class AdminController extends Controller
{
    /**
     * @Route("/manager", name="admin")
     */
    public function adminAction(Request $request)
    {

        if($this->isAdmin()){
            return $this->render('admin/index.html.twig', [
                'teachers' => $this->getTeachers(),
                'students' => $this->getStudents(),
                'departments' => $this->getDepartments(),
                'groups' => $this->getGroups(),
                'courses' => $this->getCourses(),
                "info" => $this->getInfo(),
                'classes' => $this->getClasses()
            ]);
        }else{
            return $this->redirectToRoute('homepage');
        }
    }

    /**
     * @Route("/manager/users", name="admin_users")
     */
    public function usersManage(Request $request){

        if($this->isAdmin()){
            $isSearch = false;
            $noResult = false;
            $search = "";
            $formFactory = $this->get('fos_user.registration.form.factory');
            $userManager = $this->get('fos_user.user_manager');
            $dispatcher = $this->get('event_dispatcher');

            $user = $userManager->createUser();
            $user->setEnabled(true);

            $event = new GetResponseUserEvent($user, $request);
            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

            if (null !== $event->getResponse()) {
                return $event->getResponse();
            }

            $form = $formFactory->createForm();
            $form->setData($user);

            $form->handleRequest($request);

            /**
             * Pagination
             */
            $em    = $this->getDoctrine()->getManager();
            $query = $em->getRepository("AppBundle:User")->findAll();

            $paginator  = $this->get('knp_paginator');
            $pagination = $paginator->paginate(
                $query,
                $request->query->getInt('page', 1),
                10
            );


            if($request->isMethod("POST")){
                $data = $request->request->all();
                $search = $data["search"];
                $isSearch = true;

                if(str_replace(' ', '', $search) != ""){
                    $query = $em->getRepository("AppBundle:User")->search($search);
                    if(count($query) == 0){
                        $noResult = true;
                    }else{
                        $pagination = $paginator->paginate(
                            $query,
                            $request->query->getInt('page', 1),
                            10
                        );
                    }
                }
            }

            return $this->render('admin/users.manage.html.twig', [
                'info' => $this->getInfo(),
                'groups' => $this->getGroups(),
                'form' => $form->createView(),
                'pagination' => $pagination,
                'privileges' => $this->getPrivileges(),
                'isSearch' => $isSearch,
                'search' => $search,
                'noResult' => $noResult
            ]);
        }else{
            return $this->redirectToRoute('homepage');
        }
    }

    /**
     * @Route("/manager/users/privileges", name="admin_priviliges")
     */
    public function usersPrivileges(Request $request){
        if($this->isAdmin()) {
            return $this->render('admin/privilege.html.twig', [
                'info' => $this->getInfo(),
                'privileges' => $this->getPrivileges(),
            ]);
        }else{
            return $this->redirectToRoute('homepage');
        }
    }

    /**
     * @Route("/manager/privilege/add",
     *     options={"expose"=true},
     *     name="admin_privilegeAdd"
     * )
     */
    public function usersPrivilegesAdd(Request $request){
        if($request->isMethod("POST")){

            $type = "";
            $message = "";
            $entity = null;

            $data = $request->request->all();

            if($data['title'] != ""){

                if(is_numeric($data["site"]) && is_numeric($data["admin"]) && is_numeric($data["users"]) && is_numeric($data["department"])
                    && is_numeric($data["classe"]) && is_numeric($data["curriculum"]) && is_numeric($data["config"])
                    && is_numeric($data["result"]) && is_numeric($data["course"]) && is_numeric($data["posts"])){

                    $privilege = new Privilege();
                    $privilege->setName($data["title"]);
                    $privilege->setSlug(strtolower($data["title"]));
                    $privilege->setAdmin($data["admin"]);
                    $privilege->setSite($data["site"]);
                    $privilege->setClasse($data["classe"]);
                    $privilege->setDepartment($data["department"]);
                    $privilege->setConfig($data["config"]);
                    $privilege->setPost($data["posts"]);
                    $privilege->setCourse($data["course"]);
                    $privilege->setCurriculum($data["curriculum"]);
                    $privilege->setResults($data["result"]);
                    $privilege->setUser($data["users"]);

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($privilege);
                    $em->flush();

                    $type = "success";
                    $message = "Le privilège ".$data['title']." a été ajouter avec succès!";
                    $entity = $data;

                }else{
                    $type = "error";
                    $message = "Un erreur s'est produit lors de l'ajout du privilège!";
                }

            }else{
                $type = "error";
                $message = "Veuillez insérer le titre du privilège!";
            }

            $response = new JsonResponse();
            return $response->setData([
                "type" => $type,
                "message" => $message,
                "entity" => $entity
            ]);

        }
    }

    /**
     * @Route("/manager/user/add",
     *    options={"expose"=true},
     *     name="admin_userAdd"
     * )
     */
    public function userAdd(Request $request){
        /**
         * User add
         */
        if($request->isMethod("POST")){

            $type = "";
            $message = "";
            $entity = null;

            $formFactory = $this->get('fos_user.registration.form.factory');
            $userManager = $this->get('fos_user.user_manager');
            $dispatcher = $this->get('event_dispatcher');

            $user = $userManager->createUser();
            $user->setEnabled(true);
            $event = new GetResponseUserEvent($user, $request);
            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

            if (null !== $event->getResponse()) {
                return $event->getResponse();
            }

            $form = $formFactory->createForm();
            $form->setData($user);
            $form->handleRequest($request);
            $data = $request->request->all();

            if ($data['entype'] == 4){
                // Admin

            }else if($data['entype'] == 2){
                // Teacher
                $teacher = $data['teacher'];
                if(
                    isset($data['fos_user_registration_form']["email"]) && filter_var($data['fos_user_registration_form']["email"], FILTER_VALIDATE_EMAIL) &&
                    isset($data['fos_user_registration_form']["username"]) &&
                    isset($data['fos_user_registration_form']["_token"]) &&
                    isset($data['privilege']) &&
                    isset($teacher['name']) &&
                    isset($teacher['surname']) &&
                    isset($teacher['pin']) && is_numeric($teacher['pin']) &&
                    isset($teacher['gender'])
                ){

                    $user->setType(2);

                    $user->setName($teacher['name']);
                    $user->setSurname($teacher['surname']);
                    $user->setGender($teacher['gender']);
                    $user->setTel($teacher['tel']);
                    $user->setPin($teacher['pin']);
                    $user->setBirthday(new \DateTime($teacher['birthday']));
                    $user->setAddress($teacher['address']);
                    $user->setCity($teacher['city']);
                    $user->setCountry($teacher['country']);
                    $user->setPrivilege($this->getPrivilege($data['privilege']));

                    $event = new FormEvent($form, $request);
                    $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);

                    $userManager->updateUser($user);

                    $t = new Teacher();

                    $t->setTitle($teacher['title']);
                    $t->setType($teacher['type']);

                    $t->setUser($user);

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($t);
                    $em->flush();

                    $teacher['id'] = $user->getId();
                    $teacher['privilege'] = $user->getPrivilege()->getName();
                    $teacher['type'] = "Enseignant";

                    $type = "success";
                    $message = "L'enseignant ". $user->getName() ." ". $user->getSurname() ." a été ajouter avec succée !";
                    $entity = $teacher;

                }else{
                    $type = "error";
                    $message = "Veuillez vérifier les données entrées !";
                    $entity = $teacher;

                }


            }else if($data['entype'] == 0){
                // Student
                $student = $data['student'];
                if(
                    isset($data['fos_user_registration_form']["email"]) && filter_var($data['fos_user_registration_form']["email"], FILTER_VALIDATE_EMAIL) &&
                    isset($data['fos_user_registration_form']["username"]) &&
                    isset($data['fos_user_registration_form']["_token"]) &&
                    isset($data['privilege']) &&
                    isset($student['name']) &&
                    isset($student['surname']) &&
                    isset($student['pin']) && is_numeric($student['pin']) &&
                    isset($student['code']) && is_numeric($student['code']) &&
                    isset($student['gender'])){

                    $user->setType(0);

                    $user->setName($student['name']);
                    $user->setSurname($student['surname']);
                    $user->setGender($student['gender']);
                    $user->setTel($student['tel']);
                    $user->setPin($student['pin']);
                    $user->setBirthday(new \DateTime($student['birthday']));
                    $user->setAddress($student['address']);
                    $user->setCity($student['city']);
                    $user->setCountry($student['country']);
                    $user->setPrivilege($this->getPrivilege($data['privilege']));

                    $event = new FormEvent($form, $request);
                    $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);

                    $userManager->updateUser($user);

                    $s = new Student();
                    $em = $this->getDoctrine()->getManager();
                    $group = $em->getRepository('AppBundle:Groups')->findOneById($student['group']);

                    $s->setGroups($group);
                    $s->setCode($student['code']);
                    $s->setUser($user);

                    $em->persist($s);
                    $em->flush();

                    $student['id'] = $user->getId();
                    $student['privilege'] = $user->getPrivilege()->getName();
                    $student['type'] = "Etudiant";

                    $type = "success";
                    $message = "L'étudiant ". $user->getName() ." ". $user->getSurname() ." a été ajouter avec succée !";
                    $entity = $student;


                }else{
                    $type = "error";
                    $message = "Veuillez vérifier les données entrées !";
                    $entity = $student;
                }


            }else{
                $type = "error";
                $message = "Veuillez choisir le type d'utilisateur!";
            }


            $response = new JsonResponse();
            return $response->setData([
                "type" => $type,
                "message" => $message,
                "entity" => $entity
            ]);

        }

    }

    /**
     * @Route("/manager/user/delete/{id}",
     *    options={"expose"=true},
     *     name="admin_userDelete"
     * )
     */
    public function userDelete(Request $request, $id){
        if($request->isMethod("DELETE")){
            $type = "";
            $message = "";

            if(is_numeric($id)){

                $em = $this->getDoctrine()->getEntityManager();
                $user = $em->getRepository('AppBundle:User')->findOneById($id);

                switch ($user->getType()){
                    case 0:
                        $student = $em->getRepository('AppBundle:Student')->findOneByUser($user);
                        $em->remove($student);
                        break;
                    case 2:
                        $teacher = $em->getRepository('AppBundle:Teacher')->findOneByUser($user);
                        $em->remove($teacher);
                        break;
                }

                $em->remove($user);
                $em->flush();

                $type = "success";
                $message = "L'utilisateur a été supprimer avec succès!";

            }else{
                $type = "error";
                $message = "Un erreur s'est produit !";
            }

            $response = new JsonResponse();
            return $response->setData([
                "type" => $type,
                "message" => $message,
            ]);




        }
    }


    /**
     * @Route("/manager/curriculum", name="admin_curriculum")
     */
    public function curriculumView(Request $request)
    {
        if($this->isAdmin()){
            $isSearch = false;
            $noResult = false;
            $search = "";

            $em    = $this->getDoctrine()->getManager();
            $paginator  = $this->get('knp_paginator');

            if($request->isMethod("POST")){
                $data = $request->request->all();
                $search = $data["search"];
                $isSearch = true;

                if(str_replace(' ', '', $search) != ""){
                    $query = $em->getRepository("AppBundle:Classe")->search($search);
                    if(count($query) == 0){
                        $noResult = true;
                    }else{
                        $pagination = $paginator->paginate(
                            $query,
                            $request->query->getInt('page', 1),
                            10
                        );
                    }
                }


            }else{
                /**
                 * Pagination
                 */
                $query = $em->getRepository("AppBundle:Classe")->findAll();

                $pagination = $paginator->paginate(
                    $query, /* query NOT result */
                    $request->query->getInt('page', 1)/*page number*/,
                    10/*limit per page*/
                );
            }


            return $this->render("admin/curriculum.html.twig",[
                "info" => $this->getInfo(),
                "isSearch" => $isSearch,
                "search" => $search,
                "noResult" => $noResult,
                'pagination' => $pagination
            ]);
        }
    }

    /**
     * @Route("/manager/curriculum/Classe/{id}", name="admin_curriculum_class")
     */
    public function curriculumClassView(Request $request, $id)
    {
        return $this->render("admin/curriculum.add.html.twig",[
            "info" => $this->getInfo(),
            "Gms" => $this->getClassGroupModules($id),
            'classe' => $this->getClasse($id)
        ]);

    }

    /**
     * @Route("/manager/curriculum/add",
     *    options={"expose"=true},
     *     name="admin_curriculumAdd"
     * )
     */
    public function curriculumaddGM(Request $request){
        if($request->isMethod("POST")){
            $data = $request->request->all();
            $type = "";
            $message = "";
            $entity = null;

            if(isset($data["classId"]) && is_numeric($data["classId"])
                && isset($data["number"]) && is_numeric($data["number"])
                && isset($data["coeff"]) && is_numeric($data["coeff"])){

                $GroupModule = new GroupModule();
                $classe = $this->getClasse($data["classId"]);
                $GroupModule->setClasse($classe);
                $GroupModule->setCoefficient($data["coeff"]);
                $GroupModule->setNumber($data["number"]);

                $em = $this->getDoctrine()->getManager();
                $em->persist($GroupModule);
                $em->flush();

                $data["id"] = $GroupModule->getId();
                if($classe->getCycle() == "ING"){
                 $data["title"] = "GM.". $classe->getLevel() .".".$data["number"];
                }else{
                    $data["title"] = "UE.". $classe->getLevel() .".".$data["number"];
                }
                $type = "success";
                $message = "Le module a été ajouter avec succès!";
                $entity = $data;

            }else{
                $type = "error";
                $message = "Un erreur s'est produit lors de l'ajout!";
                $entity = $data;
            }

            $response = new JsonResponse();
            return $response->setData([
                "type" => $type,
                "message" => $message,
                "entity" => $entity
            ]);
        }
    }

    /**
     * @Route("/manager/curriculum/delete/gm/{id}",
     *    options={"expose"=true},
     *     name="admin_gmDelete"
     * )
     */
    public function curriculumdeleteGM(Request $request, $id){
        if($request->isMethod("DELETE")){
            $type = "";
            $message = "";

            if(is_numeric($id)){

                $em = $this->getDoctrine()->getEntityManager();
                $gm = $em->getRepository('AppBundle:GroupModule')->findOneById($id);


                $em->remove($gm);
                $em->flush();

                $type = "success";
                $message = "Le groupe de modules a été supprimer avec succès!";

            }else{
                $type = "error";
                $message = "Un erreur s'est produit !";
            }

            $response = new JsonResponse();
            return $response->setData([
                "type" => $type,
                "message" => $message,
            ]);




        }
    }

    /**
     * @Route("/manager/curriculum/module/add",
     *    options={"expose"=true},
     *     name="admin_curriculumAddModule"
     * )
     */
    public function curriculumAddModule(Request $request){
        if($request->isMethod("POST")){
            $data = $request->request->all();
            $type = "";
            $message = "";
            $entity = null;

            if(isset($data["gm"]) && is_numeric($data["gm"]) &&
                isset($data["module"]) &&
                isset($data["semestre"]) && is_numeric($data["semestre"]) &&
                isset($data["type"]) &&
                isset($data["coeff"]) && is_numeric($data["coeff"])
            ){

                $module = new Module();
                $module->setCoefficient($data["coeff"]);
                $module->setTitle($data["module"]);
                $module->setSemester($data["semestre"]);
                $module->setType($data["type"]);
                if(isset($data["c"])){
                    $module->setC($data["c"]);
                }
                if(isset($data["tp"])){
                    $module->setTP($data["tp"]);
                }
                if(isset($data["td"])){
                    $module->setTD($data["td"]);
                }
                $module->setGroupModule($this->getGroupModule($data["gm"]));

                $em = $this->getDoctrine()->getManager();
                $em->persist($module);
                $em->flush();

                $data["id"] = $module->getId();

                $entity = $data;
                $type = "success";
                $message = "Le module ". $data["module"] ." a été ajouter!";

            }else{
                $type = "error";
                $message = "Un erreur s'est produit lors de l'ajout!";
            }


            $response = new JsonResponse();
            return $response->setData([
                "type" => $type,
                "message" => $message,
                "entity" => $entity
            ]);



        }
    }

    /**
     * @Route("/manager/department", name="admin_department")
     */
    public function departmentAction(Request $request)
    {
        if($this->isAdmin()){

            if ($request->isMethod('POST')) {
                $data = $request->request->all();

                $department = new Department();

                $chef = new Teacher();

                $em = $this->getDoctrine()->getManager();
                $chef = $em->getRepository('AppBundle:Teacher')->findOneById($data['chef']);

                $department->setName($data['name']);
                $department->setSlug($data['slug']);
                $department->setChef($chef);
                $department->setDescription($data['description']);
                $department->setEmail($data['email']);
                $department->setTel($data['tel']);

                $em->persist($department);
                $em->flush();

                return $this->redirectToRoute('admin_department');

            }

            return $this->render('admin/department.html.twig', [
                'teachers' => $this->getTeachers(),
                'departments' => $this->getDepartments(),
                "info" => $this->getInfo()
            ]);
        }else{
            return $this->redirectToRoute('homepage');
        }
    }

    /**
     * @Route("/manager/classe", name="admin_classes")
     */
    public function classeAction(Request $request)
    {
        if($this->isAdmin()){

            if ($request->isMethod('POST')) {
                $data = $request->request->all();

                $classe = new Classe();

                $department = new Department();

                $em = $this->getDoctrine()->getManager();
                $department = $em->getRepository('AppBundle:Department')->findOneById($data['department']);

                $classe->setCycle($data['cycle']);
                $classe->setDepartment($department);
                $classe->setLevel($data['level']);
                $classe->setSpeciality($data['speciality']);
                $classe->setSlug($data['slug']);

                if(isset($data['grouped']) && $data['grouped'] == "true"){
                    $classe->setHasGroups(true);
                    $em->persist($classe);
                    $em->flush();

                    $NumGroups = $data['groups'];

                    for ($i = 1; $i <= $NumGroups; $i++) {
                        $groups = new Groups();
                        $groups->setClasse($classe);
                        $groups->setNum($i);

                        $em->persist($groups);
                        $em->flush();

                    }

                }else{
                    $classe->setHasGroups(false);
                    $em->persist($classe);
                    $em->flush();

                    $groups = new Groups();
                    $groups->setClasse($classe);
                    $groups->setNum(1);
                    $em->persist($groups);
                    $em->flush();
                }


                return $this->redirectToRoute('admin_classes');


            }

            return $this->render('admin/classe.html.twig', [
                'departments' => $this->getDepartments(),
                'classes' => $this->getClasses(),
                "info" => $this->getInfo()

            ]);
        }else{
            return $this->redirectToRoute('homepage');
        }
    }

    /**
     * @Route("/manager/classe/{id}/update", name="admin_classe_update")
     */
    public function classeUpdateAction(Request $request, $id)
    {
        if($this->isAdmin()){
            return $this->render('admin/classe.html.twig', [
                'classe' => $this->getClasse($id),
                'classes' => $this->getClasses(),
                'departments' => $this->getDepartments(),
                "info" => $this->getInfo()

            ]);
        }else{
            return $this->redirectToRoute('homepage');
        }
    }

    /**
     * @Route("/manager/classe/{id}/delete", name="admin_classe_delete")
     */
    public function classeDeleteAction(Request $request, $id)
    {
        $classe = $this->getClasse($id);

        if ($request->isMethod('POST')) {
            $data = $request->request->all();

            if($data["confirm"] == 1){
                $em = $this->getDoctrine()->getManager();
                $em->remove($classe);
                $em->flush();
                return $this->redirectToRoute('admin_classes');
            }

        }

        if($this->isAdmin()){
            return $this->render('admin/classe.html.twig', [
                'classe' =>$classe,
                'departments' => $this->getDepartments(),
                'classes' => $this->getClasses(),
                "info" => $this->getInfo()
            ]);
        }else{
            return $this->redirectToRoute('homepage');
        }
    }


    /**
     * @Route("/manager/info", name="admin_info")
     */
    public function infoAction(Request $request)
    {
        if($this->isAdmin()){

            $info = $this->getInfo();

            if ($request->isMethod('POST')) {

                $data = $request->request->all();
                $em = $this->getDoctrine()->getManager();
                $logo = $request->files->get('logo');

                $info->setName($data['name']);
                $info->setMessage($data['message']);
                $info->setSlug($data['slug']);
                $info->setJs($data['js']);
                $info->setCss($data['css']);
                $info->setFacebook($data['facebook']);
                $info->setTwitter($data['twitter']);
                $info->setFbid($data['fbid']);
                $info->setDescription($data['description']);
                $info->setCopyright($data['copyright']);
                $info->setEmail($data['email']);
                $info->setTel($data['tel']);
                $info->setLink($data['link']);

                if($logo != null){
                    $info->setLogo($logo);
                    $file = $info->getLogo();

                    $fileName = 'logo.'.$file->guessExtension();

                    $file->move(
                        $this->container->getParameter('logo_directory'),
                        $fileName
                    );

                    $info->setLogo($fileName);
                    $info->setIcon($fileName);

                }

                $em->persist($info);
                $em->flush();


                return $this->redirectToRoute('admin_courses');

            }

            return $this->render('admin/info.html.twig', [
                "info" => $info
            ]);
        }else{
            return $this->redirectToRoute('homepage');
        }
    }

    /**
     * @Route("/manager/actuality", name="admin_actuality")
     */
    public function actualityAction(Request $request)
    {
        if($this->isAdmin()){

            if ($request->isMethod('POST')) {

                $data = $request->request->all();
                $thumbnail = $request->files->get('vignette');
                $attachment = $request->files->get('attachment');

                $act = new Actuality();
                $act->setTitle($data['title']);
                $act->setContent($data['content']);
                $act->setType($data['type']);

                if($thumbnail != null){
                    $act->setThumbnail($thumbnail);
                    $file = $act->getThumbnail();

                    $fileName = md5(uniqid()).'.'.$file->guessExtension();

                    $file->move(
                        $this->container->getParameter('thumbs_directory'),
                        $fileName
                    );

                    $act->setThumbnail($fileName);
                }

                if($attachment != null){
                    $act->setAttachment($attachment);
                    $file1 = $act->getAttachment();

                    $fileName1 = md5(uniqid()).'.'.$file1->guessExtension();

                    $file1->move(
                        $this->container->getParameter('annoucements_directory'),
                        $fileName1
                    );

                    $act->setAttachment($fileName1);
                }

                if($data['target'] == 1 || $data['target'] == 2){
                    $act->setTarget($data['target']);
                }else if($data['target'] == 3){
                    $act->setTarget(3);
                    $act->setTargetId($data['department']);

                }else if($data['target'] == 4){
                    $act->setTarget(4);
                    $act->setTargetId($data['classe']);

                }else{
                    $act->setTarget(5);
                    $act->setTargetId($data['group']);

                }

                $em = $this->getDoctrine()->getManager();
                $em->persist($act);
                $em->flush();


                return $this->redirectToRoute('admin_actuality');

            }

            return $this->render('admin/actuality.html.twig', [
                "info" => $this->getInfo(),
                "departments" => $this->getDepartments(),
                "classes" => $this->getClasses(),
                "groups" => $this->getGroups()
            ]);
        }else{
            return $this->redirectToRoute('homepage');
        }
    }

    /**
     * @Route("/manager/results", name="admin_results")
     */
    public function resultsAction(Request $request)
    {

        if($this->isAdmin()){
            return $this->render('admin/results.html.twig', [
                'groups' => $this->getGroups(),
                "info" => $this->getInfo()
            ]);
        }else{
            return $this->redirectToRoute('homepage');
        }

    }

    /**
     * @Route("/manager/results/{id}", name="admin_group_results")
     */
    public function resultsGroupAction(Request $request, $id)
    {

        if($this->isAdmin()){
            return $this->render('admin/results.classe.html.twig', [
                'group' => $this->getGroup($id),
                "info" => $this->getInfo(),
            ]);
        }else{
            return $this->redirectToRoute('homepage');
        }

    }

    /**
     * @Route("/manager/results/{group}/{course}", name="admin_course_results")
     */
    public function resultsCourseAction(Request $request, $group, $course)
    {

        if($this->isAdmin()){

            //Remplir par les notes déjà existant dans la bd

            if ($request->isMethod('POST')) {

                $course = $this->getCourse($course);
                $data = $request->request->all();
                $em = $this->getDoctrine()->getManager();

                foreach ($data as $student) {

                    $s = $this->getStudent($student['id']);

                    if(array_key_exists('tp', $student)){
                        if(!is_null($student['tp']) && is_numeric($student['tp'])){
                            $old = $this->getResultByType($course,$s,'tp');
                            if($old != null){
                                $result = $old;
                            }else{
                                $result = new Result();
                            }
                            $result->setStudent($s);
                            $result->setCourse($course);
                            $result->setType('tp');
                            $result->setMark($student['tp']);

                            $em->persist($result);
                            $em->flush();

                        }
                    }
                    if(array_key_exists('ds1', $student)){
                        if(!is_null($student['ds1']) && is_numeric($student['ds1'])){
                            $old = $this->getResultByType($course,$s,'ds1');
                            if($old != null){
                                $result = $old;
                            }else{
                                $result = new Result();
                            }
                            $result->setStudent($s);
                            $result->setCourse($course);
                            $result->setType('ds1');
                            $result->setMark($student['ds1']);

                            $em->persist($result);
                            $em->flush();
                        }
                    }

                    if(array_key_exists('ds2', $student)){

                        if(!is_null($student['ds2']) && is_numeric($student['ds2'])){
                            $old = $this->getResultByType($course,$s,'ds2');
                            if($old != null){
                                $result = $old;
                            }else{
                                $result = new Result();
                            }
                            $result->setStudent($s);
                            $result->setCourse($course);
                            $result->setType('ds2');
                            $result->setMark($student['ds2']);

                            $em->persist($result);
                            $em->flush();
                        }
                    }

                    if(array_key_exists('exam', $student)){

                        if(!is_null($student['exam']) && is_numeric($student['exam'])){
                            $old = $this->getResultByType($course,$s,'exam');
                            if($old != null){
                                $result = $old;
                            }else{
                                $result = new Result();
                            }
                            $result->setStudent($s);
                            $result->setCourse($course);
                            $result->setType('exam');
                            $result->setMark($student['exam']);

                            $em->persist($result);
                            $em->flush();
                        }
                    }

                }

                return $this->redirectToRoute('admin_course_results', [
                    'group' => $group,
                    'course' => $course->getId()
                ]);
            }




            return $this->render('admin/results.course.html.twig', [
                'group' => $this->getGroup($group),
                'module' => $this->getModule($course),
                "info" => $this->getInfo(),
                'results' => $this->getResults($course)
            ]);
        }else{
            return $this->redirectToRoute('homepage');
        }

    }


    /**
     * @Route("/manager/releve/{id}", name="admin_student_results")
     */
    public function resultsStudentAction(Request $request, $id)
    {
        if($this->isAdmin()){

            $student = $this->getStudent($id);

            return $this->render('admin/results.releve.html.twig', [
                'results' => $this->getStudentResults($student),
                'student' =>$student,
                "info" => $this->getInfo()
            ]);
        }else{
            return $this->redirectToRoute('homepage');
        }

    }

/////////////////////////////////////
/////////////////////////////////////
/////////////////////////////////////
/////////////////////////////////////

    public function isAdmin(){
        $auth_checker = $this->get('security.authorization_checker');
        $token = $this->get('security.token_storage')->getToken();
        $u = $token->getUser();
        $isUser = $auth_checker->isGranted('ROLE_USER');

        if($isUser && $u->getType() == 4){
            return true;
        }else{
            return false;
        }

    }

    public function getTeachers(){
        $em = $this->getDoctrine()->getManager();
        $teachers = $em->getRepository('AppBundle:Teacher')->findAll();
        return $teachers;
    }

    public function getDepartments(){
        $em = $this->getDoctrine()->getManager();
        $departments = $em->getRepository('AppBundle:Department')->findAll();
        return $departments;
    }

    public function getClasses(){
        $em = $this->getDoctrine()->getManager();
        $classes = $em->getRepository('AppBundle:Classe')->findAll();
        return $classes;
    }
    public function getClasse($id){
        $em = $this->getDoctrine()->getManager();
        $classes = $em->getRepository('AppBundle:Classe')->findOneById($id);
        return $classes;
    }
    public function getGroups(){
        $em = $this->getDoctrine()->getManager();
        $groups = $em->getRepository('AppBundle:Groups')->findAll();
        return $groups;
    }
    public function getGroup($id){
        $em = $this->getDoctrine()->getManager();
        $group = $em->getRepository('AppBundle:Groups')->findOneById($id);
        return $group;
    }
    public function getCourses(){
        $em = $this->getDoctrine()->getManager();
        $courses = $em->getRepository('AppBundle:Course')->findAll();
        return $courses;
    }
    public function getUsers(){
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('AppBundle:User')->findAll();
        return $users;
    }
    public function getCourse($id){
        $em = $this->getDoctrine()->getManager();
        $courses = $em->getRepository('AppBundle:Course')->findOneById($id);
        return $courses;
    }
    public function getStudent($id){
        $em = $this->getDoctrine()->getManager();
        $student = $em->getRepository('AppBundle:Student')->findOneById($id);
        return $student;
    }
    public function getStudents(){
        $em = $this->getDoctrine()->getManager();
        $students = $em->getRepository('AppBundle:Student')->findAll();
        return $students;
    }
    public function getInfo(){
        $em = $this->getDoctrine()->getManager();
        $info = $em->getRepository('AppBundle:Info')->findOneById(1);
        return $info;
    }

    public function getResults($course){
        $em = $this->getDoctrine()->getManager();
        $results = $em->getRepository('AppBundle:Result')->findByCourse($course);
        return $results;
    }

    public function getGroupModules(){
        $em = $this->getDoctrine()->getManager();
        $GroupModules = $em->getRepository('AppBundle:GroupModule')->findAll();
        return $GroupModules;
    }

    public function getClassGroupModules($id){
        $em = $this->getDoctrine()->getManager();
        $classe = $this->getClasse($id);
        $GroupModules = $em->getRepository('AppBundle:GroupModule')->findByClasse($classe);
        return $GroupModules;
    }

    public function getGroupModule($id){
        $em = $this->getDoctrine()->getManager();
        $GroupModule = $em->getRepository('AppBundle:GroupModule')->findOneById($id);
        return $GroupModule;
    }

    public function getStudentResults($student){
        $em = $this->getDoctrine()->getManager();
        $results = $em->getRepository('AppBundle:Result')->findByStudent($student);
        return $results;
    }

    public function getResultByType($course, $student, $type){
        $results = $this->getResults($course);
        $res = null;
        if(!is_null($results)){
            foreach ($results as $result) {
                if($result->getType() == $type && $result->getStudent() == $student){
                    $res = $result;
                }
            }
        }
        return $res;
    }

    public function getPrivileges(){
        $em = $this->getDoctrine()->getManager();
        $privileges = $em->getRepository('AppBundle:Privilege')->findAll();
        return $privileges;
    }

    public function getPrivilege($id){
        $em = $this->getDoctrine()->getManager();
        $privilege = $em->getRepository('AppBundle:Privilege')->findOneById($id);
        return $privilege;
    }
    public function getModule($id){
        $em = $this->getDoctrine()->getManager();
        $module = $em->getRepository('AppBundle:Module')->findOneById($id);
        return $module;
    }

}
