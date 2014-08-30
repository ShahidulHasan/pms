<?php

namespace Pms\CoreBundle\Controller;

use Doctrine\Common\Util\Debug;
use Doctrine\ORM\Repository;
use Pms\CoreBundle\Entity\Category;
use Pms\CoreBundle\Entity\Buyer;
use Pms\CoreBundle\Entity\PurchaseOrder;
use Pms\CoreBundle\Entity\Vendor;
use Pms\CoreBundle\Entity\Item;
use Pms\CoreBundle\Entity\Project;
use Pms\CoreBundle\Entity\ProjectCostItem;
use Pms\CoreBundle\Entity\PurchaseRequisition;
use Pms\CoreBundle\Form\CategoryType;
use Pms\CoreBundle\Form\BuyerType;
use Pms\CoreBundle\Form\VendorType;
use Pms\CoreBundle\Form\ItemType;
use Pms\CoreBundle\Form\ProjectCostItemType;
use Pms\CoreBundle\Form\ProjectType;
use Pms\CoreBundle\Form\PurchaseRequisitionType;
use Pms\CoreBundle\Form\SearchType;
use Pms\CoreBundle\Form\SubCategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CoreController extends Controller
{
    public function purchaseOrderAddAction(Request $request)
    {
        return $this->render('PmsCoreBundle:PurchaseOrder:add.html.twig', array(

        ));
    }

    public function purchaseOrderNewAction(Request $request)
    {
        $purchaseOrder = new PurchaseOrder();
//        $form = $this->createForm(new PurchaseOrderType(), $purchaseOrder);
//
//        if ($request->getMethod() == 'POST') {
//
//            $form->handleRequest($request);
//
//            if ($form->isValid()) {
//
//            }
//        }

        return $this->render('PmsCoreBundle:PurchaseOrder:form.html.twig', array(
//            'form' => $form->createView(),
        ));
    }

    public function purchaseRequisitionAddAction(Request $request)
    {
        return $this->render('PmsCoreBundle:PurchaseRequisition:add.html.twig', array(

        ));
    }

    public function purchaseRequisitionNewAction(Request $request)
    {
        $purchaseRequisition = new PurchaseRequisition();
        $form = $this->createForm(new PurchaseRequisitionType(), $purchaseRequisition);

        if ($request->getMethod() == 'POST') {

            $form->handleRequest($request);

            if ($form->isValid()) {

                    $status = '1';
                    $dateOfRequisition = $form["dateOfRequisition"]->getData();
                    $purchaseRequisition->setStatus($status);
                    $purchaseRequisition->setDateOfRequisition(new \DateTime($dateOfRequisition));

                    $this->getDoctrine()->getRepository('PmsCoreBundle:PurchaseRequisition')->create($purchaseRequisition);

                    $this->get('session')->getFlashBag()->add(
                        'notice',
                        'Pr Successfully Add'
                    );

                return $this->redirect($this->generateUrl('purchase_requisition_add'));
            }
        }

        return $this->render('PmsCoreBundle:PurchaseRequisition:form.html.twig', array(
            'form' => $form->createView(),
        ));
    }

//    public function prAjaxAddAction(Request $request)
//    {
//        $prArray = $request->request->get('prArray');
//        $prArray = explode(',',$prArray);
//
//        $project = $prArray[0];
//        $requisitionNo = $prArray[1];
//        $dateOfRequisition = $prArray[2];
//        $item = $prArray[3];
//        $quantity = $prArray[4];
//        $dateOfRequired = $prArray[5];
//        $comment = $prArray[6];
//
//        if(!empty($project) && !empty($requisitionNo) && !empty($dateOfRequisition) && !empty($item) && !empty($quantity) && !empty($dateOfRequired) && !empty($comment)) {
//
//                $purchaseRequisition = new PurchaseRequisition();
//
//                $user = $this->get('security.context')->getToken()->getUser()->getId();
//            $purchaseRequisition->setDateOfRequisition(new \DateTime($dateOfRequisition));
//            $purchaseRequisition->setCreatedBy($user);
//            $purchaseRequisition->setCreatedDate(new \DateTime());
//            $purchaseRequisition->setStatus(1);
//            $purchaseRequisition->setRequisitionNo($requisitionNo);
//            $purchaseRequisition->setProject($this->getDoctrine()->getRepository('PmsCoreBundle:Project')->findOneById($project));
//
//
//            $purchaseRequisition->setItem($this->getDoctrine()->getRepository('PmsCoreBundle:PurchaseRequisitionItem')->findOneById($item));
//            $purchaseRequisition->setQuantity($this->getDoctrine()->getRepository('PmsCoreBundle:PurchaseRequisitionItem')->findOneById($quantity));
//            $purchaseRequisition->setDateOfRequired($this->getDoctrine()->getRepository('PmsCoreBundle:PurchaseRequisitionItem')->findOneById(new \DateTime($dateOfRequired)));
//            $purchaseRequisition->setComment($this->getDoctrine()->getRepository('PmsCoreBundle:PurchaseRequisitionItem')->findOneById($comment));
//
//
//                $this->getDoctrine()->getRepository("PmsCoreBundle:ProjectCostItem")->create($purchaseRequisition);
//
//                $return = array("responseCode" => '404');
//                $return = json_encode($return);
//
//                return new Response($return, 200, array('Content-Type' => 'application/json'));
//
//        } else{
//            $return = array("responseCode" => 204);
//            $return = json_encode($return);
//
//            return new Response($return, 200, array('Content-Type' => 'application/json'));
//        }
//    }

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

        $itemUses = $this->getDoctrine()->getRepository('PmsCoreBundle:ProjectCostItem')->overView($em, $start, $end);

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

            $startDate = $_GET['start_date'];
            $endDate = $_GET['end_date'];
        }else{

            $startDate = 0;
            $endDate = 0;
        }

        list($itemUses, $itemTotal, $itemsPieChartData, $sumOfTopTen, $totalItemForPie, $totalSum, $categoriesPieChartData, $totalCategoryForPic) = $this->getDoctrine()->getRepository('PmsCoreBundle:ProjectCostItem')->itemReport($em, $startDate, $endDate);

        $formSearch = $this->createForm(new SearchType());

        return $this->render('PmsCoreBundle:Report:item.html.twig', array(
            'startDate' => $startDate,
            'endDate' => $endDate,
            'itemUses' => $itemUses,
            'itemTotal' => $itemTotal,
            'sumOfTopTen' => $sumOfTopTen,
            'itemsPieChartData' => $itemsPieChartData,
            'categoriesPieChartData' => $categoriesPieChartData,
            'formSearch' => $formSearch->createView(),
            'totalItemForPie' => $totalItemForPie,
            'totalSum' => $totalSum,
            'totalCategoryForPic' => $totalCategoryForPic,
        ));
    }

    public function itemDetailsAction($id, $start, $end)
    {
        $em = $this->getDoctrine()->getManager();

        list($itemUses, $itemTotal, $reportData) = $this->getDoctrine()->getRepository('PmsCoreBundle:ProjectCostItem')->itemDetails($id, $em, $start, $end);

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

        list($itemUses, $itemTotal, $reportData) = $this->getDoctrine()->getRepository('PmsCoreBundle:ProjectCostItem')->byItemDetails($id, $em, $start, $end, $project);

        return $this->render('PmsCoreBundle:Report:by_item_details.html.twig', array(
            'start' => $start,
            'end' => $end,
            'itemUses' => $itemUses,
            'itemTotal' => $itemTotal,
            'reportData' => $reportData,
            'project' => $project,
        ));
    }

    public function byProjectDetailsAction($id, $startDate, $endDate)
    {
        $em = $this->getDoctrine()->getManager();

        list($projectItems, $itemsTotal, $itemsPieChartData, $categoriesPieChartData) = $this->getDoctrine()->getRepository('PmsCoreBundle:ProjectCostItem')->projectDetails($id, $em, $startDate, $endDate);

        return $this->render('PmsCoreBundle:Report:by_project_details.html.twig', array(
            'categoriesPieChartData' => $categoriesPieChartData,
            'itemsPieChartData' => $itemsPieChartData,
            'projectItems' => $projectItems,
            'itemsTotal' => $itemsTotal,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ));
    }

    public function projectDetailsAction($id, $start, $end)
    {
        $em = $this->getDoctrine()->getManager();

        list($projectItems, $projectItems2, $reportData) = $this->getDoctrine()->getRepository('PmsCoreBundle:ProjectCostItem')->projectDetails($id, $em, $start, $end);

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

            $startDate = $_GET['start_date'];
            $endDate = $_GET['end_date'];
        }else{

            $startDate = 0;
            $endDate = 0;
        }

        list($projectCostItems, $totalCost, $projectPieChartData, $sumOfTopTen, $totalProjectForPic, $categoryPieChartData, $totalCategoryForPic, $totalSum) = $this->getDoctrine()->getRepository('PmsCoreBundle:ProjectCostItem')->projectReport($em, $startDate, $endDate);

        $formSearch = $this->createForm(new SearchType());

        return $this->render('PmsCoreBundle:Report:project.html.twig', array(
            'categoryPieChartData' => $categoryPieChartData,
            'projectPieChartData' => $projectPieChartData,
            'totalCategoryForPic' => $totalCategoryForPic,
            'totalProjectForPic' => $totalProjectForPic,
            'formSearch' => $formSearch->createView(),
            'projectCostItems' => $projectCostItems,
            'sumOfTopTen' => $sumOfTopTen,
            'totalCost' => $totalCost,
            'totalSum' => $totalSum,
            'startDate' => $startDate,
            'endDate' => $endDate,
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

    public function subCategoryAddAction(Request $request)
    {
        $form = $this->createForm(new SubCategoryType());

        $em = $this->getDoctrine()->getManager();

        $query = $em->getRepository('PmsCoreBundle:Category')
            ->createQueryBuilder('cat')
            ->select('cat.categoryName')
            ->addSelect('cat.id')
            ->addSelect('cat.parent')
            ->addSelect('cat.status')
            ->where('cat.status = 1')
            ->andWhere('cat.parent > 0')
            ->orderBy('cat.categoryName', 'ASC');
        $subCategories = $query->getQuery()->getResult();

        $subCategoryList = array();

        foreach ($subCategories as $subCategory) {
            $data                          = array();
            $data['name']                  = $subCategory['categoryName'];
            $data['status']                  = $subCategory['status'];
            $data['id']                  = $subCategory['id'];
            $parent                 = $subCategory['parent'];

                $parentQuery = $em->getRepository('PmsCoreBundle:Category')
                    ->createQueryBuilder('cat')
                    ->select('cat.categoryName')
                    ->where('cat.id = ?1')
                    ->setParameter('1', $parent);
                $parentName = $parentQuery->getQuery()->getResult();

            $data['parent']                = $parentName;
            $subCategoryList[]                  = $data;
        }

        return $this->render('PmsCoreBundle:SubCategory:subAdd.html.twig', array(
            'form' => $form->createView(),
            'subCategoryList' => $subCategoryList,
        ));
    }

    public function subCategoryDeletedAction(Request $request)
    {
        $form = $this->createForm(new SubCategoryType());

        $em = $this->getDoctrine()->getManager();

        $query = $em->getRepository('PmsCoreBundle:Category')
            ->createQueryBuilder('cat')
            ->select('cat.categoryName')
            ->addSelect('cat.id')
            ->addSelect('cat.parent')
            ->addSelect('cat.status')
            ->where('cat.status = 0')
            ->andWhere('cat.parent > 0')
            ->orderBy('cat.categoryName', 'ASC');
        $subCategories = $query->getQuery()->getResult();

        $subCategoryList = array();

        foreach ($subCategories as $subCategory) {
            $data                          = array();
            $data['name']                  = $subCategory['categoryName'];
            $data['status']                  = $subCategory['status'];
            $data['id']                  = $subCategory['id'];
            $parent                 = $subCategory['parent'];

            $parentQuery = $em->getRepository('PmsCoreBundle:Category')
                ->createQueryBuilder('cat')
                ->select('cat.categoryName')
                ->where('cat.id = ?1')
                ->setParameter('1', $parent);
            $parentNAme = $parentQuery->getQuery()->getResult();

            $data['parent']                = $parentNAme;
            $subCategoryList[]                  = $data;
        }

        return $this->render('PmsCoreBundle:SubCategory:subDeleted.html.twig', array(
            'form' => $form->createView(),
            'subCategoryList' => $subCategoryList,
        ));
    }

    public function subCategoryActiveAction(Category $category)
    {
        $category->setStatus(1);
        $this->getDoctrine()->getRepository('PmsCoreBundle:Category')->update($category);

        $this->get('session')->getFlashBag()->add(
            'notice',
            'Sub Category Successfully Restored'
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
        $em = $this->getDoctrine()->getManager();

        $query = $em->getRepository('PmsCoreBundle:Category')
            ->createQueryBuilder('cat')
            ->select('cat.categoryName')
            ->addSelect('cat.id')
            ->addSelect('cat.parent')
            ->addSelect('cat.status')
            ->where('cat.status = 1')
            ->andWhere('cat.parent > 0')
            ->orderBy('cat.categoryName', 'ASC');
        $subCategories = $query->getQuery()->getResult();

        $subCategoryList = array();

        foreach ($subCategories as $subCategory) {
            $data                          = array();
            $data['name']                  = $subCategory['categoryName'];
            $data['status']                  = $subCategory['status'];
            $data['id']                  = $subCategory['id'];
            $parent                 = $subCategory['parent'];

            $parentQuery = $em->getRepository('PmsCoreBundle:Category')
                ->createQueryBuilder('cat')
                ->select('cat.categoryName')
                ->where('cat.id = ?1')
                ->setParameter('1', $parent);
            $parentNAme = $parentQuery->getQuery()->getResult();

            $data['parent']                = $parentNAme;
            $subCategoryList[]                  = $data;
        }

        return $this->render('PmsCoreBundle:SubCategory:subList.html.twig', array(
            'subCategoryList' => $subCategoryList,
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

    public function subCategoryDeletedListAction()
    {
        $em = $this->getDoctrine()->getManager();

        $query = $em->getRepository('PmsCoreBundle:Category')
            ->createQueryBuilder('cat')
            ->select('cat.categoryName')
            ->addSelect('cat.id')
            ->addSelect('cat.parent')
            ->addSelect('cat.status')
            ->where('cat.status = 0')
            ->andWhere('cat.parent > 0')
            ->orderBy('cat.categoryName', 'ASC');
        $subCategories = $query->getQuery()->getResult();

        $subCategoryList = array();

        foreach ($subCategories as $subCategory) {
            $data                          = array();
            $data['name']                  = $subCategory['categoryName'];
            $data['status']                  = $subCategory['status'];
            $data['id']                  = $subCategory['id'];
            $parent                 = $subCategory['parent'];

            $parentQuery = $em->getRepository('PmsCoreBundle:Category')
                ->createQueryBuilder('cat')
                ->select('cat.categoryName')
                ->where('cat.id = ?1')
                ->setParameter('1', $parent);
            $parentNAme = $parentQuery->getQuery()->getResult();

            $data['parent']                = $parentNAme;
            $subCategoryList[]                  = $data;
        }

        return $this->render('PmsCoreBundle:SubCategory:subDeletedList.html.twig', array(
            'subCategoryList' => $subCategoryList,
        ));
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
}
