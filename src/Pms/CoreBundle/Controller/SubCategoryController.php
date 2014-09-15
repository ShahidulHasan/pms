<?php

namespace Pms\CoreBundle\Controller;

use Doctrine\ORM\Repository;
use Pms\CoreBundle\Entity\Category;
use Pms\CoreBundle\Form\SubCategoryType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * SubCategory controller.
 *
 */
class SubCategoryController extends Controller
{
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
            $parentName = $parentQuery->getQuery()->getResult();

            $data['parent']                = $parentName;
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
                $category->setCreatedBy($this->getDoctrine()->getRepository('UserBundle:User')->findOneById($user));
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
            $parentName = $parentQuery->getQuery()->getResult();

            $data['parent']                = $parentName;
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
            $parentName = $parentQuery->getQuery()->getResult();

            $data['parent']                = $parentName;
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