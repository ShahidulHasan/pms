<?php

namespace Pms\CoreBundle\Controller;

use Doctrine\ORM\Repository;
use Pms\CoreBundle\Form\SearchType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Report controller.
 *
 */
class ReportController extends Controller
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

    public function paginateOverView($itemUses)
    {
        $paginator = $this->get('knp_paginator');
        $value = $paginator->paginate(
            $itemUses,
            $page = $this->get('request')->query->get('page', 1) /*page number*/,
            10/*limit per page*/
        );

        return array($value, $page);
    }
} 