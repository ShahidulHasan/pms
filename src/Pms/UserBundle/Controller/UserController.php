<?phpnamespace Pms\UserBundle\Controller;use Symfony\Bundle\FrameworkBundle\Controller\Controller;use Symfony\Component\HttpFoundation\Request;use Symfony\Component\HttpFoundation\Response;use Pms\UserBundle\Entity\User;use Doctrine\ORM\Repository;class UserController extends Controller{    public function userAddAction(Request $request)    {        $service = $this->get('pms_user.registration.form.type');        $entity = new User();        $form = $this->createForm($service, $entity);        if ($request->getMethod() == 'POST') {            $form->handleRequest($request);            if ($form->isValid()) {                $this->getDoctrine()->getRepository('UserBundle:User')->create($entity);                $this->get('session')->getFlashBag()->add(                    'notice',                    'User Successfully Add'                );                return $this->redirect($this->generateUrl('user_add'));            }        }        return $this->render('UserBundle:User:add.html.twig', array(            'users' => $service,            'entity' => $entity,            'form' => $form->createView()        ));    }    public function indexAction()    {        $form = $this->createFormBuilder()            ->add('AddNewUser', 'button')            ->getForm();        $em = $this->get('doctrine.orm.entity_manager');        $dql = "SELECT a FROM UserBundle:User a ";        $query = $em->createQuery($dql);        $paginator = $this->get('knp_paginator');        $pagination = $paginator->paginate(            $query,            $this->get('request')->query->get('page', 1) /*page number*/,            4/*limit per page*/        );        return $this->render('UserBundle:User:list.html.twig', array(            'form' => $form->createView(),            'pagination' => $pagination        ));    }}