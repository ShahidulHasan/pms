<?php

namespace Pms\CoreBundle\Controller;

use Doctrine\ORM\Repository;
use Pms\CoreBundle\Entity\Category;
use Pms\CoreBundle\Entity\Customer;
use Pms\CoreBundle\Entity\Item;
use Pms\CoreBundle\Entity\Project;
use Pms\CoreBundle\Entity\ProjectCost;
use Pms\CoreBundle\Form\CategoryType;
use Pms\CoreBundle\Form\CustomerType;
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
    public function overViewAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        if(!empty($_GET['start_date']) && !empty($_GET['end_date'])){

            $start = $_GET['start_date'];
            $end = $_GET['end_date'];
        }else{

            $start = 0;
            $end = 0;
        }

        $itemUses = $this->getDoctrine()->getRepository('UserBundle:User')->overView($em, $start, $end);

        list($itemUses, $page) = $this->paginateOverView($itemUses);

        $formSearch = $this->createForm(new SearchType());

        return $this->render('PmsCoreBundle:Report:over_view.html.twig', array(
            'start' => $start,
            'end' => $end,
            'itemUses' => $itemUses,
            'page' => $page,
            'formSearch' => $formSearch->createView(),
        ));
    }

    public function itemReportAction()
    {
        $em = $this->getDoctrine()->getManager();

        if(!empty($_GET['start_date']) && !empty($_GET['end_date'])){

            $start = $_GET['start_date'];
            $end = $_GET['end_date'];
        }else{

            $start = 0;
            $end = 0;
        }

        list($itemUses, $itemTotal, $reportData, $sumOfTopten, $totalItemForPie, $sum) = $this->getDoctrine()->getRepository('UserBundle:User')->itemReport($em, $start, $end);

        $formSearch = $this->createForm(new SearchType());

        return $this->render('PmsCoreBundle:Report:item.html.twig', array(
            'start' => $start,
            'end' => $end,
            'itemUses' => $itemUses,
            'itemTotal' => $itemTotal,
            'sumOfTopten' => $sumOfTopten,
            'reportData' => $reportData,
            'formSearch' => $formSearch->createView(),
            'totalItemForPie' => $totalItemForPie,
            'sum' => $sum,
        ));
    }

    public function itemDetailsAction($id, $start, $end)
    {
        $em = $this->getDoctrine()->getManager();

        list($itemUses, $itemTotal, $reportData) = $this->getDoctrine()->getRepository('UserBundle:User')->itemDetails($id, $em, $start, $end);

        return $this->render('PmsCoreBundle:Report:item_details.html.twig', array(
            'start' => $start,
            'end' => $end,
            'itemUses' => $itemUses,
            'itemTotal' => $itemTotal,
            'reportData' => $reportData,
        ));
    }

    public function byItemDetailsAction($id, $start, $end, $project)
    {
        $em = $this->getDoctrine()->getManager();

        list($itemUses, $itemTotal, $reportData) = $this->getDoctrine()->getRepository('UserBundle:User')->byItemDetails($id, $em, $start, $end, $project);

        return $this->render('PmsCoreBundle:Report:by_item_details.html.twig', array(
            'start' => $start,
            'end' => $end,
            'itemUses' => $itemUses,
            'itemTotal' => $itemTotal,
            'reportData' => $reportData,
            'project' => $project,
        ));
    }

    public function byProjectDetailsAction($id, $start, $end)
    {
        $em = $this->getDoctrine()->getManager();

        list($projectItems, $projectItems2, $reportData) = $this->getDoctrine()->getRepository('UserBundle:User')->projectDetails($id, $em, $start, $end);

        return $this->render('PmsCoreBundle:Report:by_project_details.html.twig', array(
            'start' => $start,
            'end' => $end,
            'projectItems' => $projectItems,
            'projectTotal' => $projectItems2,
            'reportData' => $reportData,
        ));
    }

    public function projectDetailsAction($id, $start, $end)
    {
        $em = $this->getDoctrine()->getManager();

        list($projectItems, $projectItems2, $reportData) = $this->getDoctrine()->getRepository('UserBundle:User')->projectDetails($id, $em, $start, $end);

        return $this->render('PmsCoreBundle:Report:project_details.html.twig', array(
            'start' => $start,
            'end' => $end,
            'projectItems' => $projectItems,
            'projectTotal' => $projectItems2,
            'reportData' => $reportData,
        ));
    }

    public function projectReportAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        if(!empty($_GET['start_date']) && !empty($_GET['end_date'])){

            $start = $_GET['start_date'];
            $end = $_GET['end_date'];
        }else{

            $start = 0;
            $end = 0;
        }

        list($projectCosts, $cost, $reportData, $sumOfTopten, $totalProjectForPic, $reportData1, $totalProjectForPicCategory, $sum) = $this->getDoctrine()->getRepository('UserBundle:User')->projectReport($em, $start, $end);

        $formSearch = $this->createForm(new SearchType());

        return $this->render('PmsCoreBundle:Report:project.html.twig', array(
            'start' => $start,
            'end' => $end,
            'projectcosts' => $projectCosts,
            'cost' => $cost,
            'sumOfTopten' => $sumOfTopten,
            'reportData' => $reportData,
            'formSearch' => $formSearch->createView(),
            'totalProjectForPic' => $totalProjectForPic,
            'reportData1' => $reportData1,
            'totalProjectForPicCategory' => $totalProjectForPicCategory,
            'sum' => $sum,
        ));
    }

    public function categoryListAction()
    {
        $dql = "SELECT a FROM PmsCoreBundle:Category a WHERE a.parent = 0 AND a.status = 1 ORDER BY a.id DESC";

        list($category, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:Category:list.html.twig', array(
            'categories' => $category,
            'page' => $page,
        ));
    }

    public function categoryDeletedListAction()
    {
        $dql = "SELECT a FROM PmsCoreBundle:Category a WHERE a.parent = 0 AND a.status = 0 ORDER BY a.id DESC";

        list($category, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:Category:deletedList.html.twig', array(
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
                $category = new Category();
                $category->setCategoryName($categoryName);
                $user = $this->get('security.context')->getToken()->getUser()->getId();
                $category->setCreatedBy($user);
                $category->setCreatedDate(new \DateTime());
                $category->setStatus(1);
                $category->setParent(0);

                $this->getDoctrine()->getRepository("PmsCoreBundle:Category")->create($category);

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

        $dql = "SELECT a FROM PmsCoreBundle:Category a WHERE a.parent = 0 AND a.status = 1 ORDER BY a.id DESC";

        list($category, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:Category:add.html.twig', array(
            'categories' => $category,
            'entity' => $entity,
            'form' => $form->createView(),
            'page' => $page,
        ));
    }

    public function categoryDeletedAction(Request $request)
    {
        $entity = new Category();

        $form = $this->createForm(new CategoryType(), $entity);

        $dql = "SELECT a FROM PmsCoreBundle:Category a WHERE a.parent = 0 AND a.status = 0 ORDER BY a.id DESC";

        list($category, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:Category:deleted.html.twig', array(
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

    public function categoryDeleteAction(Category $category)
    {
        $category->setStatus(0);
        $this->getDoctrine()->getRepository('PmsCoreBundle:Category')->update($category);

        $this->get('session')->getFlashBag()->add(
            'notice',
            'Category Successfully Deleted'
        );

        return $this->redirect($this->generateUrl('category_add'));
    }

    public function categoryActiveAction(Category $category)
    {
        $category->setStatus(1);
        $this->getDoctrine()->getRepository('PmsCoreBundle:Category')->update($category);

        $this->get('session')->getFlashBag()->add(
            'notice',
            'Category Successfully Restored'
        );

        return $this->redirect($this->generateUrl('category_deleted'));
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

        $dql = "SELECT a FROM PmsCoreBundle:Category a WHERE a.parent > 0 AND a.status = 1 ORDER BY a.id DESC";

        list($category, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:SubCategory:subAdd.html.twig', array(
            'form' => $form->createView(),
            'categories' => $category,
            'page' => $page,
        ));
    }

    public function subCategoryDeletedAction(Request $request)
    {
        $form = $this->createForm(new SubCategoryType());

        $dql = "SELECT a FROM PmsCoreBundle:Category a WHERE a.parent > 0 AND a.status = 0 ORDER BY a.id DESC";

        list($category, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:SubCategory:subDeleted.html.twig', array(
            'form' => $form->createView(),
            'categories' => $category,
            'page' => $page,
        ));
    }

    public function subCategoryActiveAction(Category $category)
    {
        $category->setStatus(1);
        $this->getDoctrine()->getRepository('PmsCoreBundle:Category')->update($category);

        $this->get('session')->getFlashBag()->add(
            'notice',
            'Category Successfully Restored'
        );

        return $this->redirect($this->generateUrl('sub_category_deleted'));
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
                $category = new Category();
                $category->setCategoryName($subCategoryName);
                $user = $this->get('security.context')->getToken()->getUser()->getId();
                $category->setCreatedBy($user);
                $category->setCreatedDate(new \DateTime());
                $category->setStatus(1);
                $category->setParent($parent);

                $this->getDoctrine()->getRepository("PmsCoreBundle:Category")->create($category);

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
        $dql = "SELECT a FROM PmsCoreBundle:Category a WHERE a.parent > 0 AND a.status = 1 ORDER BY a.id DESC";

        list($category, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:SubCategory:subList.html.twig', array(
            'categories' => $category,
            'page' => $page,
        ));
    }

    public function subCategoryDeletedListAction()
    {
        $dql = "SELECT a FROM PmsCoreBundle:Category a WHERE a.parent > 0 AND a.status = 0 ORDER BY a.id DESC";

        list($category, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:SubCategory:subDeletedList.html.twig', array(
            'categories' => $category,
            'page' => $page,
        ));
    }

    public function subCategoryDeleteAction(Category $category)
    {
        $category->setStatus(0);
        $this->getDoctrine()->getRepository('PmsCoreBundle:Category')->update($category);

        $this->get('session')->getFlashBag()->add(
            'notice',
            'Sub Category Successfully Deleted'
        );

        return $this->redirect($this->generateUrl('sub_category_add'));
    }

    public function customerAddAction(Request $request)
    {
        $customer = new Customer();

        $form = $this->createForm(new CustomerType(), $customer);

        $dql = "SELECT a FROM PmsCoreBundle:Customer a WHERE a.status = 1 ORDER BY a.id DESC";

        list($customers, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:Customer:add.html.twig', array(
            'customers' => $customers,
            'form' => $form->createView(),
            'page' => $page,
        ));
    }

    public function customerDeletedAction(Request $request)
    {
        $customer = new Customer();

        $form = $this->createForm(new CustomerType(), $customer);

        $dql = "SELECT a FROM PmsCoreBundle:Customer a WHERE a.status = 0 ORDER BY a.id DESC";

        list($customers, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:Customer:deleted.html.twig', array(
            'customers' => $customers,
            'form' => $form->createView(),
            'page' => $page,
        ));
    }

    public function customerListAction()
    {
        $dql = "SELECT a FROM PmsCoreBundle:Customer a WHERE a.status = 1 ORDER BY a.id DESC";

        list($customers, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:Customer:list.html.twig', array(
            'customers' => $customers,
            'page' => $page,
        ));
    }

    public function customerDeletedListAction()
    {
        $dql = "SELECT a FROM PmsCoreBundle:Customer a WHERE a.status = 0 ORDER BY a.id DESC";

        list($customers, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:Customer:deletedList.html.twig', array(
            'customers' => $customers,
            'page' => $page,
        ));
    }

    public function customerDeleteAction(Customer $customer)
    {
        $customer->setStatus(0);
        $this->getDoctrine()->getRepository('PmsCoreBundle:Customer')->update($customer);

        $this->get('session')->getFlashBag()->add(
            'notice',
            'Buyer Successfully Deleted'
        );

        return $this->redirect($this->generateUrl('customer_add'));
    }

    public function customerActiveAction(Customer $customer)
    {
        $customer->setStatus(1);
        $this->getDoctrine()->getRepository('PmsCoreBundle:Customer')->update($customer);

        $this->get('session')->getFlashBag()->add(
            'notice',
            'Buyer Successfully Restored'
        );

        return $this->redirect($this->generateUrl('customer_deleted'));
    }

    public function customerAjaxAddAction(Request $request)
    {
        $customerArray = $request->request->get('customerArray');
        $customerArray = explode(',',$customerArray);

        $customerName = $customerArray[0];
        $updateId = $customerArray[1];

        if($customerName) {
            $customer = $this->getDoctrine()->getRepository('PmsCoreBundle:Customer')->find($updateId);
            $customerNameCheck = $this->getDoctrine()->getRepository('PmsCoreBundle:Customer')->findOneBy(
                array('customerName' => $customerName )
            );
            if($customer) {
                $customer->setCustomerName($customerName);
                $this->getDoctrine()->getManager()->persist($customer);
                $this->getDoctrine()->getManager()->flush();

                $return = array("responseCode" => 202);
                $return = json_encode($return);

                return new Response($return, 200, array('Content-Type' => 'application/json'));
            } elseif($customerNameCheck) {
                $return = array("responseCode" => 200);
                $return = json_encode($return);

                return new Response($return, 200, array('Content-Type' => 'application/json'));
            } else {
                $customer = new Customer();
                $customer->setCustomerName($customerName);
                $user = $this->get('security.context')->getToken()->getUser()->getId();
                $customer->setCreatedBy($user);
                $customer->setCreatedDate(new \DateTime());
                $customer->setStatus(1);

                $this->getDoctrine()->getRepository("PmsCoreBundle:Customer")->create($customer);

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

    public function customerCheckAction(Request $request)
    {
        $customerName = $request->request->get('customerName');

        $customer = $this->getDoctrine()->getRepository('PmsCoreBundle:Customer')->findOneBy(
            array('customerName' => $customerName )
        );

        if ($customer) {
            $return = array("responseCode" => 200, "customer_name" => "Buyer already exist.");
            $return = json_encode($return);
            return new Response($return, 200, array('Content-Type' => 'application/json'));
        } else {
            $return = array("responseCode" => '404', "customer_name" => "Buyer name available.");
            $return = json_encode($return);
            return new Response($return, 200, array('Content-Type' => 'application/json'));
        }
    }

    public function customerUpdateAction(Request $request, Customer $customer)
    {
        $form = $this->createForm(new CustomerType(), $customer);

        $dql = "SELECT a FROM PmsCoreBundle:Customer a WHERE a.status = 1 ORDER BY a.id DESC";

        list($customers, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:Customer:add.html.twig', array(
            'customers' => $customers,
            'form' => $form->createView(),
            'page' => $page,
        ));
    }

    public function itemListAction()
    {
        $dql = "SELECT a FROM PmsCoreBundle:Item a WHERE a.status = 1 ORDER BY a.id DESC";

        list($item, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:Item:list.html.twig', array(
            'item' => $item,
            'page' => $page,
        ));
    }

    public function itemDeletedListAction()
    {
        $dql = "SELECT a FROM PmsCoreBundle:Item a WHERE a.status = 0 ORDER BY a.id DESC";

        list($item, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:Item:deletedList.html.twig', array(
            'item' => $item,
            'page' => $page,
        ));
    }

    public function itemAddAction(Request $request)
    {
        $entity = new Item();

        $form = $this->createForm(new ItemType(), $entity);

        $dql = "SELECT a FROM PmsCoreBundle:Item a WHERE a.status = 1 ORDER BY a.id DESC";

        list($item, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:Item:add.html.twig', array(
            'item' => $item,
            'entity' => $entity,
            'form' => $form->createView(),
            'page' => $page,
        ));
    }

    public function itemDeletedAction(Request $request)
    {
        $entity = new Item();

        $form = $this->createForm(new ItemType(), $entity);

        $dql = "SELECT a FROM PmsCoreBundle:Item a WHERE a.status = 0 ORDER BY a.id DESC";

        list($item, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:Item:deleted.html.twig', array(
            'item' => $item,
            'entity' => $entity,
            'form' => $form->createView(),
            'page' => $page,
        ));
    }

    public function itemDeleteAction(Item $item)
    {
        $item->setStatus(0);
        $this->getDoctrine()->getRepository('PmsCoreBundle:Item')->update($item);

        $this->get('session')->getFlashBag()->add(
            'notice',
            'Item Successfully Deleted'
        );

        return $this->redirect($this->generateUrl('item_add'));
    }

    public function itemActiveAction(Item $item)
    {
        $item->setStatus(1);
        $this->getDoctrine()->getRepository('PmsCoreBundle:Item')->update($item);

        $this->get('session')->getFlashBag()->add(
            'notice',
            'Item Successfully Restored'
        );

        return $this->redirect($this->generateUrl('item_deleted'));
    }

    public function itemUpdateAction(Request $request, Item $entity)
    {
        $form = $this->createForm(new ItemType(), $entity);

        $dql = "SELECT a FROM PmsCoreBundle:Item a WHERE a.status = 1 ORDER BY a.id DESC";

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

    public function categoryCheckAction(Request $request)
    {
        $categoryName = $request->request->get('categoryName');

        $category = $this->getDoctrine()->getRepository('PmsCoreBundle:Category')->findOneBy(
            array('categoryName' => $categoryName )
        );

        if ($category) {
            $return = array("responseCode" => 200, "category_name" => "Category already exist.");
            $return = json_encode($return);
            return new Response($return, 200, array('Content-Type' => 'application/json'));
        } else {
            $return = array("responseCode" => '404', "category_name" => "Category name available.");
            $return = json_encode($return);
            return new Response($return, 200, array('Content-Type' => 'application/json'));
        }
    }

    public function subCategoryCheckAction(Request $request)
    {
        $subCategoryName = $request->request->get('subCategoryName');

        $category = $this->getDoctrine()->getRepository('PmsCoreBundle:Category')->findOneBy(
            array('categoryName' => $subCategoryName )
        );

        if ($category) {
            $return = array("responseCode" => 200, "sub_category_name" => "This sub category already exist.");
            $return = json_encode($return);
            return new Response($return, 200, array('Content-Type' => 'application/json'));
        } else {
            $return = array("responseCode" => '404', "sub_category_name" => "Sub category available.");
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
        $itemUnit = $itemArray[2];

        if($itemName) {
            $item = $this->getDoctrine()->getRepository('PmsCoreBundle:Item')->find($updateId);
            $itemNameCheck = $this->getDoctrine()->getRepository('PmsCoreBundle:Item')->findOneBy(
                array('itemName' => $itemName )
            );
            if($item) {
                $item->setItemName($itemName);
                $item->setItemUnit($itemUnit);
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
                $item = new Item();
                $item->setItemName($itemName);
                $item->setItemUnit($itemUnit);
                $user = $this->get('security.context')->getToken()->getUser()->getId();
                $item->setCreatedBy($user);
                $item->setCreatedDate(new \DateTime());
                $item->setStatus(1);

                $this->getDoctrine()->getRepository("PmsCoreBundle:Item")->create($item);

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
        $dql = "SELECT a FROM PmsCoreBundle:Project a WHERE a.status = 1  ORDER BY a.id DESC";

        list($project, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:Project:list.html.twig', array(
            'project' => $project,
            'page' => $page,
        ));
    }

    public function projectDeletedListAction()
    {
        $dql = "SELECT a FROM PmsCoreBundle:Project a WHERE a.status = 0  ORDER BY a.id DESC";

        list($project, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:Project:deletedList.html.twig', array(
            'project' => $project,
            'page' => $page,
        ));
    }

    public function projectAddAction(Request $request)
    {
        $entity = new Project();

        $form = $this->createForm(new ProjectType(), $entity);

        $dql = "SELECT a FROM PmsCoreBundle:Project a WHERE a.status = 1 ORDER BY a.id DESC";

        list($project, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:Project:add.html.twig', array(
            'project' => $project,
            'entity' => $entity,
            'form' => $form->createView(),
            'page' => $page,
        ));
    }

    public function projectDeletedAction(Request $request)
    {
        $entity = new Project();

        $form = $this->createForm(new ProjectType(), $entity);

        $dql = "SELECT a FROM PmsCoreBundle:Project a WHERE a.status = 0 ORDER BY a.id DESC";

        list($project, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:Project:deleted.html.twig', array(
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

    public function projectActiveAction(Project $entity)
    {
        $entity->setStatus(1);
        $this->getDoctrine()->getRepository('PmsCoreBundle:Project')->update($entity);

        $this->get('session')->getFlashBag()->add(
            'notice',
            'Project Successfully Restored'
        );

        return $this->redirect($this->generateUrl('project_deleted'));
    }

    public function projectUpdateAction(Request $request, Project $entity)
    {
        $form = $this->createForm(new ProjectType(), $entity);

        $dql = "SELECT a FROM PmsCoreBundle:Project a WHERE a.status = 1 ORDER BY a.id DESC";

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
                $project = new Project();
                $project->setProjectName($projectName);
                $user = $this->get('security.context')->getToken()->getUser()->getId();
                $project->setCreatedBy($user);
                $project->setCreatedDate(new \DateTime());
                $project->setStatus(1);

                $this->getDoctrine()->getRepository("PmsCoreBundle:Project")->create($project);

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

    public function projectCostDeleteAction(ProjectCost $entity)
    {
        $this->getDoctrine()->getRepository('UserBundle:User')->delete($entity);

        $this->get('session')->getFlashBag()->add(
            'notice',
            'Cost Successfully Deleted'
        );

        return $this->redirect($this->generateUrl('cost_add'));
    }

    public function projectCostAddAction(Request $request)
    {
        $entity = new ProjectCost();

        $form = $this->createForm(new ProjectCostType(), $entity);

        $formSearch = $this->createForm(new SearchType());

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

    public function projectCostApprovedAction(ProjectCost $projectCost)
    {
        $projectCost->setStatus(1);

        $user = $this->get('security.context')->getToken()->getUser()->getId();
        $projectCost->setApprovedBy($user);
        $projectCost->setApprovedDate(new \DateTime());

        $this->getDoctrine()->getRepository('PmsCoreBundle:ProjectCost')->update($projectCost);

        $this->get('session')->getFlashBag()->add(
            'notice',
            'Project Cost Successfully Checked'
        );

        return $this->redirect($this->generateUrl('cost_add'));
    }

    public function projectCostCheckedAction(Request $request)
    {
        $projectCostId = $request->request->get('projectCostId');
        $projectCostId = explode(',',$projectCostId);

        $projectCostId = $projectCostId[0];

        $projectCost = $this->getDoctrine()->getRepository('PmsCoreBundle:ProjectCost')->find($projectCostId);

        $projectCost->setStatus(1);
        $user = $this->get('security.context')->getToken()->getUser()->getId();
        $projectCost->setApprovedBy($user);
        $projectCost->setApprovedDate(new \DateTime());

        $this->getDoctrine()->getManager()->persist($projectCost);
        $this->getDoctrine()->getManager()->flush();

        $return = array("responseCode" => 202);
        $return = json_encode($return);

        return new Response($return, 200, array('Content-Type' => 'application/json'));
    }

    public function projectCostUpdateAction(Request $request, ProjectCost $entity)
    {
        $date = $entity->getDateOfCost();
        $date1 =  $date->format('Y-m-d');
        $entity->setDateOfCost($date1);

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
        $pr = $projectCostArray[11];
        $po = $projectCostArray[12];
        $customer = $projectCostArray[13];

        if(!empty($dateOfCost) && !empty($project) && !empty($item) && !empty($quantity) && !empty($unitPrice) && !empty($lineTotal)) {

            if($updateId) {
                $projectcost = $this->getDoctrine()->getRepository('PmsCoreBundle:ProjectCost')->find($updateId);

                $user = $this->get('security.context')->getToken()->getUser()->getId();
                $projectcost->setDateOfCost(new \DateTime($dateOfCost));
                $projectcost->setApprovedBy($user);
                $projectcost->setApprovedDate(new \DateTime());

                $projectcost->setProject($this->getDoctrine()->getRepository('PmsCoreBundle:Project')->findOneById($project));
                $projectcost->setItem($this->getDoctrine()->getRepository('PmsCoreBundle:Item')->findOneById($item));
                $projectcost->setCustomer($this->getDoctrine()->getRepository('PmsCoreBundle:Customer')->findOneById($customer));
                $projectcost->setCategory($this->getDoctrine()->getRepository('PmsCoreBundle:Category')->findOneById($category));
                $projectcost->setQuantity($quantity);
                $projectcost->setUnitPrice($unitPrice);
                $projectcost->setLineTotal($lineTotal);
                $projectcost->setInvoice($invoice);
                $projectcost->setGrn($grn);
                $projectcost->setSubCategory($subcategory);
                $projectcost->setPr($pr);
                $projectcost->setPo($po);

                $this->getDoctrine()->getManager()->persist($projectcost);
                $this->getDoctrine()->getManager()->flush();

                $return = array("responseCode" => 202);
                $return = json_encode($return);

                return new Response($return, 200, array('Content-Type' => 'application/json'));
            } else {
                $projectcost = new ProjectCost();

                $user = $this->get('security.context')->getToken()->getUser()->getId();
                $projectcost->setDateOfCost(new \DateTime($dateOfCost));
                $projectcost->setCreatedBy($user);
                $projectcost->setCreatedDate(new \DateTime());
                $projectcost->setStatus(0);

                $projectcost->setProject($this->getDoctrine()->getRepository('PmsCoreBundle:Project')->findOneById($project));
                $projectcost->setItem($this->getDoctrine()->getRepository('PmsCoreBundle:Item')->findOneById($item));
                $projectcost->setCustomer($this->getDoctrine()->getRepository('PmsCoreBundle:Customer')->findOneById($customer));
                $projectcost->setCategory($this->getDoctrine()->getRepository('PmsCoreBundle:Category')->findOneById($category));
                $projectcost->setQuantity($quantity);
                $projectcost->setUnitPrice($unitPrice);
                $projectcost->setLineTotal($lineTotal);
                $projectcost->setInvoice($invoice);
                $projectcost->setGrn($grn);
                $projectcost->setSubCategory($subcategory);
                $projectcost->setPr($pr);
                $projectcost->setPo($po);

                $this->getDoctrine()->getRepository("PmsCoreBundle:ProjectCost")->create($projectcost);

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

    public function paginateOverView($itemUses)
    {
        $paginator = $this->get('knp_paginator');
        $value = $paginator->paginate(
            $itemUses,
            $page = $this->get('request')->query->get('page', 1) /*page number*/,
            5/*limit per page*/
        );

        return array($value, $page);
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
