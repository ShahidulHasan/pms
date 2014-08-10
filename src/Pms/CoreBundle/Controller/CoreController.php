<?php

namespace Pms\CoreBundle\Controller;

use Doctrine\ORM\Repository;
use Pms\CoreBundle\Entity\Category;
use Pms\CoreBundle\Entity\Item;
use Pms\CoreBundle\Entity\Project;
use Pms\CoreBundle\Entity\ProjectCost;
use Pms\CoreBundle\Form\CategoryType;
use Pms\CoreBundle\Form\ItemType;
use Pms\CoreBundle\Form\ProjectCostType;
use Pms\CoreBundle\Form\ProjectType;
use Pms\CoreBundle\Form\SearchType;
use Pms\CoreBundle\Form\SubCategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CoreController extends Controller
{
    public function overViewAction()
    {
        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();

        $query = $em->getRepository('PmsCoreBundle:ProjectCost')
            ->createQueryBuilder('pc')
            ->select('p.projectName')
            ->addSelect('i.itemName')
            ->addSelect('i.id')
            ->addSelect('SUM(pc.lineTotal) as total')
            ->addSelect('SUM(pc.quantity) as quantity')
            ->where('pc.status = 1')
            ->join('pc.project', 'p')
            ->join('pc.item', 'i')
            ->groupBy('i.id')
            ->orderBy('i.id', 'DESC');
        $itemUses = $query->getQuery()->getResult();

        foreach ($itemUses as $key => $item) {

            $statement = $connection->prepare("SELECT project.project_name, MAX(project_cost.unit_price) as projectHighest, MIN(project_cost.unit_price) as projectLowest, project_cost.item
            FROM project_cost
            JOIN project ON project.id = project_cost.project
            WHERE project_cost.item = :itemId
            GROUP BY project_cost.project");
            $statement->bindValue('itemId', $item['id']);
            $statement->execute();
            $itemUses[$key]['projectSummary'] = $statement->fetchAll();
        }

        $query2 = $em->getRepository('PmsCoreBundle:ProjectCost')
            ->createQueryBuilder('pc')
            ->Select('SUM(pc.lineTotal) as total')
            ->where('pc.status = 1')
            ->join('pc.item', 'p');
        $itemTotal = $query2->getQuery()->getResult();

        return $this->render('PmsCoreBundle:Report:over_view.html.twig', array(
            'itemUses' => $itemUses,
            'itemTotal' => $itemTotal,
        ));
    }

    public function itemReportAction()
    {
        $em = $this->getDoctrine()->getManager();

        list($itemUses, $itemTotal) = $this->getDoctrine()->getRepository('UserBundle:User')->itemReport($em);

        $reportData = array();

        foreach($itemUses as $key => $itemUse){
            $data = array();
            $data['data'] = ($itemUse['total']*100)/$itemTotal[0]['total'];
            $data['label'] = $itemUse['itemName'];
            $reportData[] = $data;
            $projectItems[$key]['percentage'] = ($itemUse['total']*100)/$itemTotal[0]['total'];
        }

        return $this->render('PmsCoreBundle:Report:item.html.twig', array(
            'itemUses' => $itemUses,
            'itemTotal' => $itemTotal,
            'reportData' => $reportData,
        ));
    }

    public function itemDetailsAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        list($itemUses, $itemTotal) = $this->getDoctrine()->getRepository('UserBundle:User')->itemDetails($id, $em);

        $reportData = array();

        foreach($itemUses as $key => $itemUse){
            $data = array();
            $data['data'] = ($itemUse['total']*100)/$itemTotal[0]['total'];
            $data['label'] = $itemUse['projectName'];
            $reportData[] = $data;
            $projectItems[$key]['percentage'] = ($itemUse['total']*100)/$itemTotal[0]['total'];
        }

        return $this->render('PmsCoreBundle:Report:item_details.html.twig', array(
            'itemUses' => $itemUses,
            'itemTotal' => $itemTotal,
            'reportData' => $reportData,
        ));
    }

    public function projectDetailsAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        list($projectItems, $projectItems2) = $this->getDoctrine()->getRepository('UserBundle:User')->projectDetails($id, $em);

        $reportData = array();

        foreach($projectItems as $key => $projectItem){
            $data = array();
            $data['data'] = ($projectItem['total']*100)/$projectItems2[0]['total'];
            $data['label'] = $projectItem['itemName'];
            $reportData[] = $data;
            $projectItems[$key]['percentage'] = ($projectItem['total']*100)/$projectItems2[0]['total'];
        }

        return $this->render('PmsCoreBundle:Report:project_details.html.twig', array(
            'projectItems' => $projectItems,
            'projectTotal' => $projectItems2,
            'reportData' => $reportData,
        ));
    }

    public function projectReportAction()
    {
        $em = $this->getDoctrine()->getManager();

        list($projectCosts, $cost) = $this->getDoctrine()->getRepository('UserBundle:User')->projectReport($em);

        $reportData = array();

        foreach($projectCosts as $key => $projectCost){
            $data = array();
            $data['data'] = ($projectCost['total']*100)/$cost[0]['total'];
            $data['label'] = $projectCost['projectName'];
            $reportData[] = $data;
            $projectCosts[$key]['percentage'] = ($projectCost['total']*100)/$cost[0]['total'];
        }

        return $this->render('PmsCoreBundle:Report:project.html.twig', array(
            'projectcosts' => $projectCosts,
            'cost' => $cost,
            'reportData' => $reportData,
        ));
    }

    public function categoryListAction()
    {
        $dql = "SELECT a FROM PmsCoreBundle:Category a ORDER BY a.id DESC";

        list($category, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:Category:list.html.twig', array(
            'categories' => $category,
            'page' => $page,
        ));
    }

    public function categoryAjaxAddAction(Request $request)
    {
        $categoryArray = $request->request->get('categoryArray');
        $categoryArray = explode(',',$categoryArray);

        $categoryName = $categoryArray[0];
        $updateId = $categoryArray[1];

        if($categoryName) {
            $category = $this->getDoctrine()->getRepository('PmsCoreBundle:Category')->find($updateId);
            $categoryNameCheck = $this->getDoctrine()->getRepository('PmsCoreBundle:Category')->findOneBy(
                array('categoryName' => $categoryName )
            );
            if($category) {
                $category->setCategoryName($categoryName);
                $this->getDoctrine()->getManager()->persist($category);
                $this->getDoctrine()->getManager()->flush();

                $return = array("responseCode" => 202);
                $return = json_encode($return);

                return new Response($return, 200, array('Content-Type' => 'application/json'));
            } elseif($categoryNameCheck) {
                $return = array("responseCode" => 200);
                $return = json_encode($return);

                return new Response($return, 200, array('Content-Type' => 'application/json'));
            } else {
                $entity = new Category();
                $entity->setCategoryName($categoryName);
                $user = $this->get('security.context')->getToken()->getUser()->getId();
                $entity->setCreatedBy($user);
                $entity->setCreatedDate(new \DateTime());
                $entity->setStatus(1);
                $entity->setParent(0);

                $this->getDoctrine()->getRepository("PmsCoreBundle:Category")->create($entity);

                $return = array("responseCode" => 404);
                $return = json_encode($return);

                return new Response($return, 200, array('Content-Type' => 'application/json'));
            }
        } else{
            $return = array("responseCode" => 204);
            $return = json_encode($return);

            return new Response($return, 200, array('Content-Type' => 'application/json'));
        }
    }

    public function categoryAddAction(Request $request)
    {
        $entity = new Category();

        $form = $this->createForm(new CategoryType(), $entity);

        $dql = "SELECT a FROM PmsCoreBundle:Category a WHERE a.parent = 0 ORDER BY a.id DESC";

        list($category, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:Category:add.html.twig', array(
            'categories' => $category,
            'entity' => $entity,
            'form' => $form->createView(),
            'page' => $page,
        ));
    }

    public function getSubcategoryByCategoryAction(Request $request) {
        $categoryId = $request->request->get('category');

        $categories = $this->getDoctrine()->getRepository('PmsCoreBundle:Category')->findByParent($categoryId);
        $subCat = array();
        foreach ($categories as $category) {
            $subCat[] = array('id' => $category->getId(), 'categoryName' => $category->getCategoryName());
        }

        $data = array(
            'responseCode' => 200,
            'subCats' => $subCat
        );

        $response = new Response(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    public function categoryDeleteAction(Category $entity)
    {
        $entity->setStatus(0);
        $this->getDoctrine()->getRepository('PmsCoreBundle:Category')->update($entity);

        $this->get('session')->getFlashBag()->add(
            'notice',
            'Category Successfully Deleted'
        );

        return $this->redirect($this->generateUrl('category_add'));
    }

    public function categoryUpdateAction(Request $request, Category $entity)
    {
        $form = $this->createForm(new CategoryType(), $entity);

        $dql = "SELECT a FROM PmsCoreBundle:Category a ORDER BY a.id DESC";

        list($category, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:Category:add.html.twig', array(
            'categories' => $category,
            'entity' => $entity,
            'form' => $form->createView(),
            'page' => $page,
        ));
    }

    public function subCategoryAddAction(Request $request)
    {
        $form = $this->createForm(new SubCategoryType());

        $dql = "SELECT a FROM PmsCoreBundle:Category a WHERE a.parent > 0 ORDER BY a.id DESC";

        list($category, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:Category:subcategory.html.twig', array(
            'form' => $form->createView(),
            'categories' => $category,
            'page' => $page,
        ));
    }

    public function subCategoryAjaxAddAction(Request $request)
    {
        $subcategoryArray = $request->request->get('subcategoryArray');
        $subcategoryArray = explode(',',$subcategoryArray);

        $subCategoryName = $subcategoryArray[0];
        $parent = $subcategoryArray[1];
        $updateId = $subcategoryArray[2];

        if(($subCategoryName) && ($parent > 0) ) {
            $category = $this->getDoctrine()->getRepository('PmsCoreBundle:Category')->find($updateId);
            $categoryNameCheck = $this->getDoctrine()->getRepository('PmsCoreBundle:Category')->findOneBy(
                array('categoryName' => $subCategoryName )
            );
            if($category) {
                $category->setCategoryName($subCategoryName);
                $this->getDoctrine()->getManager()->persist($category);
                $this->getDoctrine()->getManager()->flush();

                $return = array("responseCode" => 202);
                $return = json_encode($return);

                return new Response($return, 200, array('Content-Type' => 'application/json'));
            } elseif($categoryNameCheck) {
                $return = array("responseCode" => 200);
                $return = json_encode($return);

                return new Response($return, 200, array('Content-Type' => 'application/json'));
            } else {
                $entity = new Category();
                $entity->setCategoryName($subCategoryName);
                $user = $this->get('security.context')->getToken()->getUser()->getId();
                $entity->setCreatedBy($user);
                $entity->setCreatedDate(new \DateTime());
                $entity->setStatus(1);
                $entity->setParent($parent);

                $this->getDoctrine()->getRepository("PmsCoreBundle:Category")->create($entity);

                $return = array("responseCode" => 404);
                $return = json_encode($return);

                return new Response($return, 200, array('Content-Type' => 'application/json'));
            }
        } else{
            $return = array("responseCode" => 204);
            $return = json_encode($return);

            return new Response($return, 200, array('Content-Type' => 'application/json'));
        }
    }

    public function subCategoryListAction()
    {
        $dql = "SELECT a FROM PmsCoreBundle:Category a WHERE a.parent > 0 ORDER BY a.id DESC";

        list($category, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:Category:subList.html.twig', array(
            'categories' => $category,
            'page' => $page,
        ));
    }

    public function subCategoryDeleteAction(Category $entity)
    {
        $entity->setStatus(0);
        $this->getDoctrine()->getRepository('PmsCoreBundle:Category')->update($entity);

        $this->get('session')->getFlashBag()->add(
            'notice',
            'Sub Category Successfully Deleted'
        );

        return $this->redirect($this->generateUrl('sub_category_add'));
    }

//    public function subCategoryUpdateAction(Request $request, Category $entity)
//    {
//        $form = $this->createForm(new SubCategoryType(), $entity);
//
//        $dql = "SELECT a FROM PmsCoreBundle:Category a WHERE a.parent > 0 ORDER BY a.id DESC";
//
//        list($category, $page) = $this->paginate($dql);
//
//        return $this->render('PmsCoreBundle:Category:subcategory.html.twig', array(
//            'categories' => $category,
//            'entity' => $entity,
//            'form' => $form->createView(),
//            'page' => $page,
//        ));
//    }

    public function itemListAction()
    {
        $dql = "SELECT a FROM PmsCoreBundle:Item a ORDER BY a.id DESC";

        list($item, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:Item:list.html.twig', array(
            'item' => $item,
            'page' => $page,
        ));
    }

    public function itemAddAction(Request $request)
    {
        $entity = new Item();

        $form = $this->createForm(new ItemType(), $entity);

//        if ($request->getMethod() == 'POST') {
//
//            $form->handleRequest($request);
//
//            if ($form->isValid()) {
//
//                $itemName = $form->get('itemName')->getData();
//
//                $item = $this->getDoctrine()->getRepository('PmsCoreBundle:Item')->findOneBy(
//                    array('itemName' => $itemName )
//                );
//
//               if ($item == null) {
//
//    //                $var = $form->get('itemName')->getData();
//    //                var_dump($var);die;
//
//    //                $product = $this->getDoctrine()->getRepository('PmsCoreBundle:Item')->findOneBy(
//    //                    array('itemName' => 'shanto')
//    //                );
//    //                if($product == true){
//    //                echo('ok');die;
//    //                }
//    //                echo('no');die;
//
//                    $user = $this->get('security.context')->getToken()->getUser()->getId();
//                    $entity->setCreatedBy($user);
//                    $entity->setCreatedDate(new \DateTime());
//                    $entity->setStatus(1);
//
//                    $this->getDoctrine()->getRepository("PmsCoreBundle:Item")->create($entity);
//                    $this->get('session')->getFlashBag()->add(
//                        'notice',
//                        'Item Successfully Add'
//                    );
//                }else{
//                   $this->get('session')->getFlashBag()->add(
//                       'notice',
//                       'Item Error For Duplicat Entry'
//                   );
//                }
//
//                return $this->redirect($this->generateUrl('item_add'));
//            }
//        }

        $dql = "SELECT a FROM PmsCoreBundle:Item a ORDER BY a.id DESC";

        list($item, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:Item:add.html.twig', array(
            'item' => $item,
            'entity' => $entity,
            'form' => $form->createView(),
            'page' => $page,
        ));
    }

    public function itemDeleteAction(Item $entity)
    {
        $entity->setStatus(0);
        $this->getDoctrine()->getRepository('PmsCoreBundle:Item')->update($entity);

        $this->get('session')->getFlashBag()->add(
            'notice',
            'Item Successfully Deleted'
        );

        return $this->redirect($this->generateUrl('item_add'));
    }

    public function itemUpdateAction(Request $request, Item $entity)
    {
        $form = $this->createForm(new ItemType(), $entity);

//        if ($request->getMethod() == 'POST') {
//
//            $form->submit($request);
//
//            if ($form->isValid()) {
//
//                $this->getDoctrine()->getRepository('PmsCoreBundle:Item')->update($entity);
//                $this->get('session')->getFlashBag()->add(
//                    'notice',
//                    'Item Successfully Updated'
//                );
//
//                return $this->redirect($this->generateUrl('item_add'));
//            }
//        }

        $dql = "SELECT a FROM PmsCoreBundle:Item a ORDER BY a.id DESC";

        list($item, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:Item:add.html.twig', array(
            'item' => $item,
            'entity' => $entity,
            'form' => $form->createView(),
            'page' => $page,
        ));
    }

    public function itemCheckAction(Request $request)
    {
        $itemName = $request->request->get('itemName');

        $item = $this->getDoctrine()->getRepository('PmsCoreBundle:Item')->findOneBy(
        array('itemName' => $itemName )
        );

        if ($item) {
            $return = array("responseCode" => 200, "item_name" => "Item name already exist.");
            $return = json_encode($return);
            return new Response($return, 200, array('Content-Type' => 'application/json'));
        } else {
            $return = array("responseCode" => '404', "item_name" => "Item name available.");
            $return = json_encode($return);
            return new Response($return, 200, array('Content-Type' => 'application/json'));
        }
    }

    public function itemAjaxAddAction(Request $request)
    {
        $itemArray = $request->request->get('itemArray');
        $itemArray = explode(',',$itemArray);

        $itemName = $itemArray[0];
        $updateId = $itemArray[1];

        if($itemName) {
            $item = $this->getDoctrine()->getRepository('PmsCoreBundle:Item')->find($updateId);
            $itemNameCheck = $this->getDoctrine()->getRepository('PmsCoreBundle:Item')->findOneBy(
                array('itemName' => $itemName )
            );
            if($item) {
                $item->setItemName($itemName);
                $this->getDoctrine()->getManager()->persist($item);
                $this->getDoctrine()->getManager()->flush();

                $return = array("responseCode" => 202);
                $return = json_encode($return);

                return new Response($return, 200, array('Content-Type' => 'application/json'));
            } elseif($itemNameCheck) {
                $return = array("responseCode" => 200);
                $return = json_encode($return);

                return new Response($return, 200, array('Content-Type' => 'application/json'));
            } else {
                $entity = new Item();
                $entity->setItemName($itemName);
                $user = $this->get('security.context')->getToken()->getUser()->getId();
                $entity->setCreatedBy($user);
                $entity->setCreatedDate(new \DateTime());
                $entity->setStatus(1);

                $this->getDoctrine()->getRepository("PmsCoreBundle:Item")->create($entity);

                $return = array("responseCode" => 404);
                $return = json_encode($return);

                return new Response($return, 200, array('Content-Type' => 'application/json'));
            }
        } else{
            $return = array("responseCode" => 204);
            $return = json_encode($return);

            return new Response($return, 200, array('Content-Type' => 'application/json'));
        }
    }

    public function projectListAction()
    {
        $dql = "SELECT a FROM PmsCoreBundle:Project a ORDER BY a.id DESC";

        list($project, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:Project:list.html.twig', array(
            'project' => $project,
            'page' => $page,
        ));
    }

    public function projectAddAction(Request $request)
    {
        $entity = new Project();

        $form = $this->createForm(new ProjectType(), $entity);

//        if ($request->getMethod() == 'POST') {
//
//            $form->handleRequest($request);
//
//            if ($form->isValid()) {
//
//                $projectName = $form->get('projectName')->getData();
//
//                $item = $this->getDoctrine()->getRepository('PmsCoreBundle:Project')->findOneBy(
//                    array('projectName' => $projectName )
//                );
//
//                if ($item == null) {
//
//                    $user = $this->get('security.context')->getToken()->getUser()->getId();
//                    $entity->setCreatedBy($user);
//                    $entity->setCreatedDate(new \DateTime());
//                    $entity->setStatus(1);
//
//                    $this->getDoctrine()->getRepository("PmsCoreBundle:Project")->create($entity);
//                    $this->get('session')->getFlashBag()->add(
//                        'notice',
//                        'Project Successfully Add'
//                    );
//                }else{
//                    $this->get('session')->getFlashBag()->add(
//                        'notice',
//                        'Project Error For Duplicat Entry'
//                    );
//                }
//
//                return $this->redirect($this->generateUrl('project_add'));
//            }
//        }

        $dql = "SELECT a FROM PmsCoreBundle:Project a ORDER BY a.id DESC";

        list($project, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:Project:add.html.twig', array(
            'project' => $project,
            'entity' => $entity,
            'form' => $form->createView(),
            'page' => $page,
        ));
    }

    public function projectDeleteAction(Project $entity)
    {
        $entity->setStatus(0);
        $this->getDoctrine()->getRepository('PmsCoreBundle:Project')->update($entity);

        $this->get('session')->getFlashBag()->add(
            'notice',
            'Project Successfully Deleted'
        );

        return $this->redirect($this->generateUrl('project_add'));
    }

    public function projectUpdateAction(Request $request, Project $entity)
    {
        $form = $this->createForm(new ProjectType(), $entity);

//        if ($request->getMethod() == 'POST') {
//
//            $form->submit($request);
//
//            if ($form->isValid()) {
//
//                $this->getDoctrine()->getRepository('PmsCoreBundle:Project')->update($entity);
//                $this->get('session')->getFlashBag()->add(
//                    'notice',
//                    'Project Successfully Updated'
//                );
//
//                return $this->redirect($this->generateUrl('project_add'));
//            }
//        }

        $dql = "SELECT a FROM PmsCoreBundle:Project a ORDER BY a.id DESC";

        list($project, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:Project:add.html.twig', array(
            'project' => $project,
            'entity' => $entity,
            'form' => $form->createView(),
            'page' => $page,
        ));
    }

    public function projectCheckAction(Request $request)
    {
        $projectName = $request->request->get('projectName');

        $project = $this->getDoctrine()->getRepository('PmsCoreBundle:Project')->findOneBy(
            array('projectName' => $projectName )
        );

        if ($project) {
            $return = array("responseCode" => 200, "project_name" => "Project name already exist.");
            $return = json_encode($return);
            return new Response($return, 200, array('Content-Type' => 'application/json'));
        } else {
            $return = array("responseCode" => '404', "project_name" => "Project name available.");
            $return = json_encode($return);
            return new Response($return, 200, array('Content-Type' => 'application/json'));
        }
    }

    public function projectAjaxAddAction(Request $request)
    {
        $projectArray = $request->request->get('projectArray');
        $projectArray = explode(',',$projectArray);

        $projectName = $projectArray[0];
        $updateId = $projectArray[1];

        if($projectName) {
            $project = $this->getDoctrine()->getRepository('PmsCoreBundle:Project')->find($updateId);
            $projectNameCheck = $this->getDoctrine()->getRepository('PmsCoreBundle:Project')->findOneBy(
                array('projectName' => $projectName )
            );
            if($project) {
                $project->setProjectName($projectName);
                $this->getDoctrine()->getManager()->persist($project);
                $this->getDoctrine()->getManager()->flush();

                $return = array("responseCode" => 202);
                $return = json_encode($return);

                return new Response($return, 200, array('Content-Type' => 'application/json'));
            } elseif($projectNameCheck) {

                $return = array("responseCode" => 200);
                $return = json_encode($return);
                return new Response($return, 200, array('Content-Type' => 'application/json'));
            } else {
                $entity = new Project();
                $entity->setProjectName($projectName);
                $user = $this->get('security.context')->getToken()->getUser()->getId();
                $entity->setCreatedBy($user);
                $entity->setCreatedDate(new \DateTime());
                $entity->setStatus(1);

                $this->getDoctrine()->getRepository("PmsCoreBundle:Project")->create($entity);

                $return = array("responseCode" => '404');
                $return = json_encode($return);
                return new Response($return, 200, array('Content-Type' => 'application/json'));
            }
        } else {
            $return = array("responseCode" => 204);
            $return = json_encode($return);
            return new Response($return, 200, array('Content-Type' => 'application/json'));
        }
    }

    public function projectCostListAction()
    {
        $dql = "SELECT a FROM PmsCoreBundle:ProjectCost a ORDER BY a.id DESC";

        list($projectcost, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:ProjectCost:list.html.twig', array(
            'projectcost' => $projectcost,
            'page' => $page,
        ));
    }

    public function projectCostAddAction(Request $request)
    {
        $entity = new ProjectCost();

        $form = $this->createForm(new ProjectCostType(), $entity);

        $formSearch = $this->createForm(new SearchType());

//        if ($request->getMethod() == 'POST') {
//
//            $form->handleRequest($request);
//
//            if ($form->isValid()) {
//
//                $user = $this->get('security.context')->getToken()->getUser()->getId();
//                $entity->setDateOfCost(new \DateTime($form->getData()->getDateOfCost()));
//                $entity->setCreatedBy($user);
//                $entity->setCreatedDate(new \DateTime());
//                $entity->setStatus(0);
//
//                $this->getDoctrine()->getRepository("PmsCoreBundle:ProjectCost")->create($entity);
//                $this->get('session')->getFlashBag()->add(
//                    'notice',
//                    'Project Cost Successfully Add'
//                );
//
//                return $this->redirect($this->generateUrl('cost_add'));
//            }
//        }

        $dql = "SELECT a FROM PmsCoreBundle:ProjectCost a WHERE 1 = 1 ";

        if(!empty($_GET['search']['categoryWise'])){

            $dql = $this->searchByCategoryWise($_GET['search']['categoryWise'], $dql);
        }else{

            if(!empty($_GET['invoice']) && !empty($_GET['grn'])){

                $dql = $this->searchByInvoiceGrn($_GET['invoice'], $_GET['grn'], $dql);
            }elseif(!empty($_GET['invoice'])){

                $dql = $this->searchByInvoice($_GET['invoice'], $dql);
            }elseif(!empty($_GET['grn'])){

                $dql = $this->searchByGrn($_GET['grn'], $dql);
            }else{

                if(!empty($_GET['search']['project']) && !empty($_GET['search']['item']) && !empty($_GET['start_date']) && !empty($_GET['end_date']) ){

                    $dql = $this->searchByDateProjectItem($_GET['start_date'], $_GET['end_date'], $_GET['search']['project'], $_GET['search']['item'], $dql);
                }elseif(!empty($_GET['search']['project']) && !empty($_GET['start_date']) && !empty($_GET['end_date'])) {

                    $dql = $this->searchByDateProject($_GET['start_date'], $_GET['end_date'], $_GET['search']['project'], $dql);
                }elseif(!empty($_GET['search']['item']) && !empty($_GET['start_date']) && !empty($_GET['end_date'])){

                    $dql = $this->searchByDateItem($_GET['start_date'], $_GET['end_date'], $_GET['search']['item'], $dql);
                }elseif(!empty($_GET['start_date']) && !empty($_GET['end_date'])){

                    $dql = $this->searchByDate($_GET['start_date'], $_GET['end_date'], $dql);
                }elseif(!empty($_GET['search']['project'])){

                    $dql = $this->searchByProject($_GET['search']['project'], $dql);
                }elseif(!empty($_GET['search']['item'])){

                    $dql = $this->searchByItem($_GET['search']['item'], $dql);
                }else{

                    $dql = "SELECT a FROM PmsCoreBundle:ProjectCost a WHERE 1 = 1 ORDER BY a.id DESC";
                }
            }
        }

        list($projectcost, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:ProjectCost:add.html.twig', array(
            'projectcost' => $projectcost,
            'entity' => $entity,
            'form' => $form->createView(),
            'formSearch' => $formSearch->createView(),
            'page' => $page,
        ));
    }

    public function projectCostApprovedAction(ProjectCost $entity)
    {
        $entity->setStatus(1);

        $user = $this->get('security.context')->getToken()->getUser()->getId();
        $entity->setApprovedBy($user);
        $entity->setApprovedDate(new \DateTime());

        $this->getDoctrine()->getRepository('PmsCoreBundle:ProjectCost')->update($entity);

        $this->get('session')->getFlashBag()->add(
            'notice',
            'Project Cost Successfully Approved'
        );

        return $this->redirect($this->generateUrl('cost_add'));
    }

    public function projectCostUpdateAction(Request $request, ProjectCost $entity)
    {
//        $request = $this->getRequest();
//        $id = $request->get('id');
//        $date = $entity->getLineTotal();

        $date = $entity->getDateOfCost();
        $date1 =  $date->format('Y-m-d');
        $date1 = $entity->setDateOfCost($date1);

        $form = $this->createForm(new ProjectCostType(), $entity);

        $formSearch = $this->createForm(new SearchType());

        if ($request->getMethod() == 'POST') {

            $form->submit($request);

            if ($form->isValid()) {
                $entity->setDateOfCost(new \DateTime($form->getData()->getDateOfCost()));
                $this->getDoctrine()->getRepository('PmsCoreBundle:ProjectCost')->update($entity);
                $this->get('session')->getFlashBag()->add(
                    'notice',
                    'Project Cost Successfully Updated'
                );

                return $this->redirect($this->generateUrl('cost_add'));
            }
        }

        $dql = "SELECT a FROM PmsCoreBundle:ProjectCost a WHERE 1 = 1";

        if(!empty($_GET['search']['categoryWise'])){

            $dql = $this->searchByCategoryWise($_GET['search']['categoryWise'], $dql);
        }else{

            if(!empty($_GET['invoice']) && !empty($_GET['grn'])){

                $dql = $this->searchByInvoiceGrn($_GET['invoice'], $_GET['grn'], $dql);
            }elseif(!empty($_GET['invoice'])){

                $dql = $this->searchByInvoice($_GET['invoice'], $dql);
            }elseif(!empty($_GET['grn'])){

                $dql = $this->searchByGrn($_GET['grn'], $dql);
            }else{

                if(!empty($_GET['search']['project']) && !empty($_GET['search']['item']) && !empty($_GET['start_date']) && !empty($_GET['end_date']) ){

                    $dql = $this->searchByDateProjectItem($_GET['start_date'], $_GET['end_date'], $_GET['search']['project'], $_GET['search']['item'], $dql);
                }elseif(!empty($_GET['search']['project']) && !empty($_GET['start_date']) && !empty($_GET['end_date'])) {

                    $dql = $this->searchByDateProject($_GET['start_date'], $_GET['end_date'], $_GET['search']['project'], $dql);
                }elseif(!empty($_GET['search']['item']) && !empty($_GET['start_date']) && !empty($_GET['end_date'])){

                    $dql = $this->searchByDateItem($_GET['start_date'], $_GET['end_date'], $_GET['search']['item'], $dql);
                }elseif(!empty($_GET['start_date']) && !empty($_GET['end_date'])){

                    $dql = $this->searchByDate($_GET['start_date'], $_GET['end_date'], $dql);
                }elseif(!empty($_GET['search']['project'])){

                    $dql = $this->searchByProject($_GET['search']['project'], $dql);
                }elseif(!empty($_GET['search']['item'])){

                    $dql = $this->searchByItem($_GET['search']['item'], $dql);
                }else{

                    $dql = "SELECT a FROM PmsCoreBundle:ProjectCost a WHERE 1 = 1 ORDER BY a.id DESC";
                }
            }
        }

        list($projectcost, $page) = $this->paginate($dql);


        return $this->render('PmsCoreBundle:ProjectCost:add.html.twig', array(
            'projectcost' => $projectcost,
            'entity' => $entity,
            'form' => $form->createView(),
            'formSearch' => $formSearch->createView(),
            'page' => $page,
        ));
    }

    public function projectCostAjaxAddAction(Request $request)
    {
        $projectCostArray = $request->request->get('projectCostArray');
        $projectCostArray = explode(',',$projectCostArray);

        $dateOfCost = $projectCostArray[0];
        $project = $projectCostArray[1];
        $item = $projectCostArray[2];
        $quantity = $projectCostArray[3];
        $unitPrice = $projectCostArray[4];
        $lineTotal = $projectCostArray[5];
        $updateId = $projectCostArray[6];
        $invoice = $projectCostArray[7];
        $grn = $projectCostArray[8];
        $category = $projectCostArray[9];
        $subcategory = $projectCostArray[10];

        if(!empty($dateOfCost) && !empty($project) && !empty($item) && !empty($quantity) && !empty($unitPrice) && !empty($lineTotal)) {

            if($updateId) {
                $projectcost = $this->getDoctrine()->getRepository('PmsCoreBundle:ProjectCost')->find($updateId);

                $user = $this->get('security.context')->getToken()->getUser()->getId();
                $projectcost->setDateOfCost(new \DateTime($dateOfCost));
                $projectcost->setApprovedBy($user);
                $projectcost->setApprovedDate(new \DateTime());

                $projectcost->setProject($this->getDoctrine()->getRepository('PmsCoreBundle:Project')->findOneById($project));
                $projectcost->setItem($this->getDoctrine()->getRepository('PmsCoreBundle:Item')->findOneById($item));
                $projectcost->setQuantity($quantity);
                $projectcost->setUnitPrice($unitPrice);
                $projectcost->setLineTotal($lineTotal);
                $projectcost->setInvoice($invoice);
                $projectcost->setGrn($grn);
                $projectcost->setCategory($category);
                $projectcost->setSubCategory($subcategory);

                $this->getDoctrine()->getManager()->persist($projectcost);
                $this->getDoctrine()->getManager()->flush();

                $return = array("responseCode" => 202);
                $return = json_encode($return);

                return new Response($return, 200, array('Content-Type' => 'application/json'));
            } else {
                $entity = new ProjectCost();

                $user = $this->get('security.context')->getToken()->getUser()->getId();
                $entity->setDateOfCost(new \DateTime($dateOfCost));
                $entity->setCreatedBy($user);
                $entity->setCreatedDate(new \DateTime());
                $entity->setStatus(0);

                $entity->setProject($this->getDoctrine()->getRepository('PmsCoreBundle:Project')->findOneById($project));
                $entity->setItem($this->getDoctrine()->getRepository('PmsCoreBundle:Item')->findOneById($item));
                $entity->setQuantity($quantity);
                $entity->setUnitPrice($unitPrice);
                $entity->setLineTotal($lineTotal);
                $entity->setInvoice($invoice);
                $entity->setGrn($grn);
                $entity->setCategory($category);
                $entity->setSubCategory($subcategory);

                $this->getDoctrine()->getRepository("PmsCoreBundle:ProjectCost")->create($entity);

                $return = array("responseCode" => '404');
                $return = json_encode($return);

                return new Response($return, 200, array('Content-Type' => 'application/json'));
            }
        } else{
            $return = array("responseCode" => 204);
            $return = json_encode($return);

            return new Response($return, 200, array('Content-Type' => 'application/json'));
        }
    }

    public function paginate($dql)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $value = $paginator->paginate(
            $query,
            $page = $this->get('request')->query->get('page', 1) /*page number*/,
            10/*limit per page*/
        );

        return array($value, $page);
    }

    public function searchByCategoryWise($category, $dql){

        $dql .= "AND a.category = '{$category}' ORDER BY a.id DESC";

        return $dql;
    }

    public function searchByInvoiceGrn($invoice, $grn, $dql){

        $dql .= "AND a.invoice = '{$invoice}' AND a.grn = '{$grn}' ORDER BY a.id DESC";

        return $dql;
    }

    public function searchByInvoice($invoice, $dql){

        $dql .= "AND a.invoice = '{$invoice}' ORDER BY a.id DESC";

        return $dql;
    }

    public function searchByGrn($grn, $dql){

        $dql .= "AND a.grn = '{$grn}' ORDER BY a.id DESC";

        return $dql;
    }

    public function searchByDateProjectItem($start_date, $end_date, $project, $item, $dql)
    {
        $dql .= "AND a.project = {$project} AND a.item = {$item} AND a.dateOfCost >= '{$start_date}' AND a.dateOfCost <= '{$end_date}' ORDER BY a.id DESC";

        return $dql;
    }

    public function searchByDateProject($start_date, $end_date, $project, $dql)
    {
        $dql .= "AND a.project = {$project} AND a.dateOfCost >= '{$start_date}' AND a.dateOfCost <= '{$end_date}' ORDER BY a.id DESC";

        return $dql;
    }

    public function searchByDateItem($start_date, $end_date, $item, $dql)
    {
        $dql .= "AND a.item = {$item} AND a.dateOfCost >= '{$start_date}' AND a.dateOfCost <= '{$end_date}' ORDER BY a.id DESC";

        return $dql;
    }

    public function searchByDate($start_date, $end_date, $dql)
    {
        $dql .= "AND a.dateOfCost >= '{$start_date}' AND a.dateOfCost <= '{$end_date}' ORDER BY a.id DESC";

        return $dql;
    }

    public function searchByProject($project, $dql)
    {
        $dql .= "AND a.project = {$project} ORDER BY a.id DESC";

        return $dql;
    }

    public function searchByItem($item, $dql)
    {
        $dql .= "AND a.item = {$item} ORDER BY a.id DESC";

        return $dql;
    }
}
