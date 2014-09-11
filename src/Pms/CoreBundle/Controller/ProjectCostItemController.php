<?php

namespace Pms\CoreBundle\Controller;

use Doctrine\ORM\Repository;
use Pms\CoreBundle\Entity\ProjectCostItem;
use Pms\CoreBundle\Form\ProjectCostItemType;
use Pms\CoreBundle\Form\SearchType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * ProjectCostItem controller.
 *
 */
class ProjectCostItemController extends Controller
{
    public function projectCostItemListAction()
    {
        $dql = "SELECT a FROM PmsCoreBundle:ProjectCostItem a ORDER BY a.id DESC";

        list($projectCostItem, $page) = $this->paginateProjectCostItem($dql);

        return $this->render('PmsCoreBundle:ProjectCostItem:list.html.twig', array(
            'projectCostItem' => $projectCostItem,
            'page' => $page,
        ));
    }

    public function projectCostItemDetailsAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $query = $em->getRepository('PmsCoreBundle:ProjectCostItem')
            ->createQueryBuilder('pc')
            ->select('p.projectName')
            ->addSelect('i.itemName')
            ->addSelect('pc.approvedBy')
            ->addSelect('pc.createdBy')
            ->addSelect('pc.buyer')
            ->addSelect('pc.lineTotal')
            ->addSelect('c.categoryName')
            ->addSelect('pc.createdDate')
            ->addSelect('pc.dateOfCost')
            ->addSelect('pc.subCategory')
            ->addSelect('pc.quantity')
            ->addSelect('pc.unitPrice')
            ->addSelect('pc.invoice')
            ->addSelect('pc.grn')
            ->addSelect('pc.pr')
            ->addSelect('pc.po')
            ->addSelect('pc.comment')
            ->where('pc.id = ?1')
            ->setParameter('1', $id)
            ->join('pc.project', 'p')
            ->join('pc.item', 'i')
            ->join('pc.category', 'c');
        $projectCostItem = $query->getQuery()->getResult();

        $userName = $projectCostItem[0]['createdBy'];
        $approved = $projectCostItem[0]['approvedBy'];
        $buyer = $projectCostItem[0]['buyer'];
        $subCategory = $projectCostItem[0]['subCategory'];

        $userQuery    = $em->getRepository('UserBundle:User')
            ->createQueryBuilder('u')
            ->select('u.username')
            ->where('u.id = ?1')
            ->setParameter('1', $userName);
        $user = $userQuery->getQuery()->getResult();

        $approvedQuery    = $em->getRepository('UserBundle:User')
            ->createQueryBuilder('u')
            ->select('u.username')
            ->where('u.id = ?2')
            ->setParameter('2', $approved);
        $approvedUser = $approvedQuery->getQuery()->getResult();

        $buyerQuery    = $em->getRepository('PmsCoreBundle:Buyer')
            ->createQueryBuilder('u')
            ->select('u.buyerName')
            ->where('u.id = ?3')
            ->setParameter('3', $buyer);
        $buyerUser = $buyerQuery->getQuery()->getResult();

        $subCategoryQuery    = $em->getRepository('PmsCoreBundle:Category')
            ->createQueryBuilder('u')
            ->select('u.categoryName')
            ->where('u.id = ?4')
            ->setParameter('4', $subCategory);
        $subCategoryUser = $subCategoryQuery->getQuery()->getResult();

        return $this->render('PmsCoreBundle:ProjectCostItem:details.html.twig', array(
            'projectCostItem' => $projectCostItem,
            'created' => $user,
            'approved' => $approvedUser,
            'buyer' => $buyerUser,
            'subCategory' => $subCategoryUser,
        ));
    }

    public function projectCostItemDeleteAction(ProjectCostItem $projectCostItem)
    {
        $this->getDoctrine()->getRepository('PmsCoreBundle:ProjectCostItem')->delete($projectCostItem);

        $this->get('session')->getFlashBag()->add(
            'notice',
            'Cost Successfully Deleted'
        );

        return $this->redirect($this->generateUrl('project_cost_add'));
    }

    public function projectCostItemAddAction(Request $request)
    {
        $entity = new ProjectCostItem();

        $form = $this->createForm(new ProjectCostItemType(), $entity);

        $formSearch = $this->createForm(new SearchType());

        $dql = "SELECT a FROM PmsCoreBundle:ProjectCostItem a ";

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

                    $dql = "SELECT a FROM PmsCoreBundle:ProjectCostItem a ORDER BY a.id DESC";
                }
            }
        }

        list($projectCostItem, $page) = $this->paginateProjectCostItem($dql);

        return $this->render('PmsCoreBundle:ProjectCostItem:add.html.twig', array(
            'projectCostItem' => $projectCostItem,
            'entity' => $entity,
            'form' => $form->createView(),
            'formSearch' => $formSearch->createView(),
            'page' => $page,
        ));
    }

    public function projectCostItemApprovedAction(ProjectCostItem $projectCostItem)
    {
        $projectCostItem->setStatus(1);

        $user = $this->get('security.context')->getToken()->getUser()->getId();
        $projectCostItem->setApprovedBy($user);
        $projectCostItem->setApprovedDate(new \DateTime());

        $this->getDoctrine()->getRepository('PmsCoreBundle:ProjectCostItem')->update($projectCostItem);

        $this->get('session')->getFlashBag()->add(
            'notice',
            'Project Cost Successfully Checked'
        );

        return $this->redirect($this->generateUrl('project_cost_add'));
    }

    public function projectCostItemCheckedAction(Request $request)
    {
        $projectCostItemId = $request->request->get('projectCostItemId');
        $projectCostItemId = explode(',',$projectCostItemId);

        $projectCostItemId = $projectCostItemId[0];

        $projectCostItem = $this->getDoctrine()->getRepository('PmsCoreBundle:ProjectCostItem')->find($projectCostItemId);

        $projectCostItem->setStatus(1);
        $user = $this->get('security.context')->getToken()->getUser()->getId();
        $projectCostItem->setApprovedBy($user);
        $projectCostItem->setApprovedDate(new \DateTime());

        $this->getDoctrine()->getManager()->persist($projectCostItem);
        $this->getDoctrine()->getManager()->flush();

        $return = array("responseCode" => 202);
        $return = json_encode($return);

        return new Response($return, 200, array('Content-Type' => 'application/json'));
    }

    public function projectCostItemUpdateAction(Request $request, ProjectCostItem $entity)
    {
        $date = $entity->getDateOfCost();
        $date1 =  $date->format('Y-m-d');
        $entity->setDateOfCost($date1);

        $form = $this->createForm(new ProjectCostItemType(), $entity);

        $formSearch = $this->createForm(new SearchType());

        if ($request->getMethod() == 'POST') {

            $form->submit($request);

            if ($form->isValid()) {
                $entity->setDateOfCost(new \DateTime($form->getData()->getDateOfCost()));
                $this->getDoctrine()->getRepository('PmsCoreBundle:ProjectCostItem')->update($entity);
                $this->get('session')->getFlashBag()->add(
                    'notice',
                    'Project Cost Successfully Updated'
                );

                return $this->redirect($this->generateUrl('project_cost_add'));
            }
        }

        $dql = "SELECT a FROM PmsCoreBundle:ProjectCostItem a";

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

                    $dql = "SELECT a FROM PmsCoreBundle:ProjectCostItem a ORDER BY a.id DESC";
                }
            }
        }

        list($projectCostItem, $page) = $this->paginateProjectCostItem($dql);


        return $this->render('PmsCoreBundle:ProjectCostItem:add.html.twig', array(
            'projectCostItem' => $projectCostItem,
            'entity' => $entity,
            'form' => $form->createView(),
            'formSearch' => $formSearch->createView(),
            'page' => $page,
        ));
    }

    public function projectCostItemAjaxAddAction(Request $request)
    {
        $projectCostItemArray = $request->request->get('projectCostItemArray');
        $projectCostItemArray = explode(',',$projectCostItemArray);

        $dateOfCost = $projectCostItemArray[0];
        $project = $projectCostItemArray[1];
        $item = $projectCostItemArray[2];
        $quantity = $projectCostItemArray[3];
        $unitPrice = $projectCostItemArray[4];
        $lineTotal = $projectCostItemArray[5];
        $updateId = $projectCostItemArray[6];
        $invoice = $projectCostItemArray[7];
        $grn = $projectCostItemArray[8];
        $category = $projectCostItemArray[9];
        $subcategory = $projectCostItemArray[10];
        $pr = $projectCostItemArray[11];
        $po = $projectCostItemArray[12];
        $buyer = $projectCostItemArray[13];
        $comment = $projectCostItemArray[14];

        if(!empty($dateOfCost) && !empty($project) && !empty($item) && !empty($quantity) && !empty($unitPrice) && !empty($lineTotal)) {

            if($updateId) {
                $projectCostItem = $this->getDoctrine()->getRepository('PmsCoreBundle:ProjectCostItem')->find($updateId);

                $user = $this->get('security.context')->getToken()->getUser()->getId();
                $projectCostItem->setDateOfCost(new \DateTime($dateOfCost));
                $projectCostItem->setApprovedBy($user);
                $projectCostItem->setApprovedDate(new \DateTime());

                $projectCostItem->setProject($this->getDoctrine()->getRepository('PmsCoreBundle:Project')->findOneById($project));
                $projectCostItem->setItem($this->getDoctrine()->getRepository('PmsCoreBundle:Item')->findOneById($item));
                $projectCostItem->setBuyer($buyer);
                $projectCostItem->setCategory($this->getDoctrine()->getRepository('PmsCoreBundle:Category')->findOneById($category));
                $projectCostItem->setQuantity($quantity);
                $projectCostItem->setUnitPrice($unitPrice);
                $projectCostItem->setLineTotal($lineTotal);
                $projectCostItem->setInvoice($invoice);
                $projectCostItem->setGrn($grn);
                $projectCostItem->setSubCategory($subcategory);
                $projectCostItem->setPr($pr);
                $projectCostItem->setPo($po);
                $projectCostItem->setComment($comment);

                $this->getDoctrine()->getManager()->persist($projectCostItem);
                $this->getDoctrine()->getManager()->flush();

                $return = array("responseCode" => 202);
                $return = json_encode($return);

                return new Response($return, 200, array('Content-Type' => 'application/json'));
            } else {
                $projectCostItem = new ProjectCostItem();

                $user = $this->get('security.context')->getToken()->getUser()->getId();
                $projectCostItem->setDateOfCost(new \DateTime($dateOfCost));
                $projectCostItem->setCreatedBy($user);
                $projectCostItem->setCreatedDate(new \DateTime());
                $projectCostItem->setStatus(0);

                $projectCostItem->setProject($this->getDoctrine()->getRepository('PmsCoreBundle:Project')->findOneById($project));
                $projectCostItem->setItem($this->getDoctrine()->getRepository('PmsCoreBundle:Item')->findOneById($item));
                $projectCostItem->setBuyer($buyer);
                $projectCostItem->setCategory($this->getDoctrine()->getRepository('PmsCoreBundle:Category')->findOneById($category));
                $projectCostItem->setQuantity($quantity);
                $projectCostItem->setUnitPrice($unitPrice);
                $projectCostItem->setLineTotal($lineTotal);
                $projectCostItem->setInvoice($invoice);
                $projectCostItem->setGrn($grn);
                $projectCostItem->setSubCategory($subcategory);
                $projectCostItem->setPr($pr);
                $projectCostItem->setPo($po);
                $projectCostItem->setComment($comment);

                $this->getDoctrine()->getRepository("PmsCoreBundle:ProjectCostItem")->create($projectCostItem);

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

    public function paginateProjectCostItem($dql)
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

        $dql .= "WHERE a.category = '{$category}' ORDER BY a.id DESC";

        return $dql;
    }

    public function searchByInvoiceGrn($invoice, $grn, $dql){

        $dql .= "WHERE a.invoice = '{$invoice}' AND a.grn = '{$grn}' ORDER BY a.id DESC";

        return $dql;
    }

    public function searchByInvoice($invoice, $dql){

        $dql .= "WHERE a.invoice = '{$invoice}' ORDER BY a.id DESC";

        return $dql;
    }

    public function searchByGrn($grn, $dql){

        $dql .= "WHERE a.grn = '{$grn}' ORDER BY a.id DESC";

        return $dql;
    }

    public function searchByDateProjectItem($start_date, $end_date, $project, $item, $dql)
    {
        $dql .= "WHERE a.project = {$project} AND a.item = {$item} AND a.dateOfCost >= '{$start_date}' AND a.dateOfCost <= '{$end_date}' ORDER BY a.id DESC";

        return $dql;
    }

    public function searchByDateProject($start_date, $end_date, $project, $dql)
    {
        $dql .= "WHERE a.project = {$project} AND a.dateOfCost >= '{$start_date}' AND a.dateOfCost <= '{$end_date}' ORDER BY a.id DESC";

        return $dql;
    }

    public function searchByDateItem($start_date, $end_date, $item, $dql)
    {
        $dql .= "WHERE a.item = {$item} AND a.dateOfCost >= '{$start_date}' AND a.dateOfCost <= '{$end_date}' ORDER BY a.id DESC";

        return $dql;
    }

    public function searchByDate($start_date, $end_date, $dql)
    {
        $dql .= "WHERE a.dateOfCost >= '{$start_date}' AND a.dateOfCost <= '{$end_date}' ORDER BY a.id DESC";

        return $dql;
    }

    public function searchByProject($project, $dql)
    {
        $dql .= "WHERE a.project = {$project} ORDER BY a.id DESC";

        return $dql;
    }

    public function searchByItem($item, $dql)
    {
        $dql .= "WHERE a.item = {$item} ORDER BY a.id DESC";

        return $dql;
    }
} 