<?php

namespace Pms\UserBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends EntityRepository
{
    public function getAll()
    {
        return $this->findAll();
    }

    public function create($data)
    {
        $this->_em->persist($data);
        $this->_em->flush();
    }

    public function delete($data)
    {
        $this->_em->remove($data);
        $this->_em->flush();
    }

    public function update($data)
    {
        $this->_em->persist($data);
        $this->_em->flush();
        return $this->_em;
    }

    public function itemReport($em, $start, $end){

        if(($start != 0) && ($end != 0)){

            $query = $em->getRepository('PmsCoreBundle:ProjectCost')
                ->createQueryBuilder('pc')
                ->select('p.projectName')
                ->addSelect('i.itemName')
                ->addSelect('p.id')
                ->addSelect('SUM(pc.lineTotal) as total')
                ->addSelect('SUM(pc.status) as totalUsed')
                ->where('pc.status = 1')
                ->andWhere('pc.dateOfCost >= ?1')
                ->andWhere('pc.dateOfCost <= ?2')
                ->setParameter('1', $start)
                ->setParameter('2', $end)
                ->join('pc.project', 'p')
                ->join('pc.item', 'i')
                ->groupBy('i.id')
                ->orderBy('i.id', 'DESC');
            $itemUses = $query->getQuery()->getResult();

            $forPieChart = $em->getRepository('PmsCoreBundle:ProjectCost')
                ->createQueryBuilder('pc')
                ->select('p.projectName')
                ->addSelect('i.itemName')
                ->addSelect('p.id')
                ->addSelect('SUM(pc.lineTotal) as total')
                ->where('pc.status = 1')
                ->andWhere('pc.dateOfCost >= ?1')
                ->andWhere('pc.dateOfCost <= ?2')
                ->setParameter('1', $start)
                ->setParameter('2', $end)
                ->join('pc.project', 'p')
                ->join('pc.item', 'i')
                ->groupBy('i.id')
                ->orderBy('pc.lineTotal', 'DESC')
                ->setMaxResults('10');
            $topTen = $forPieChart->getQuery()->getResult();
        }else{

            $query = $em->getRepository('PmsCoreBundle:ProjectCost')
                ->createQueryBuilder('pc')
                ->select('p.projectName')
                ->addSelect('i.itemName')
                ->addSelect('i.id')
                ->addSelect('SUM(pc.lineTotal) as total')
                ->addSelect('SUM(pc.status) as totalUsed')
                ->where('pc.status = 1')
                ->join('pc.project', 'p')
                ->join('pc.item', 'i')
                ->groupBy('i.id')
                ->orderBy('i.id', 'DESC');
            $itemUses = $query->getQuery()->getResult();

            $forPieChart = $em->getRepository('PmsCoreBundle:ProjectCost')
                ->createQueryBuilder('pc')
                ->select('p.projectName')
                ->addSelect('i.itemName')
                ->addSelect('p.id')
                ->addSelect('SUM(pc.lineTotal) as total')
                ->where('pc.status = 1')
                ->join('pc.project', 'p')
                ->join('pc.item', 'i')
                ->groupBy('i.id')
                ->orderBy('pc.lineTotal', 'DESC')
                ->setMaxResults('10');
            $topTen = $forPieChart->getQuery()->getResult();
        }

        $query2 = $em->getRepository('PmsCoreBundle:ProjectCost')
            ->createQueryBuilder('pc')
            ->Select('SUM(pc.lineTotal) as total')
            ->where('pc.status = 1')
            ->join('pc.item', 'p');
        $itemTotal = $query2->getQuery()->getResult();

        $reportData = array();

        foreach($topTen as $key => $itemUse){
            $data = array();
            $data['data'] = ($itemUse['total']*100)/$itemTotal[0]['total'];
            $data['label'] = $itemUse['itemName'];
            $reportData[] = $data;
            $projectItems[$key]['percentage'] = ($itemUse['total']*100)/$itemTotal[0]['total'];
        }

        $sumOfTopten = 0;
        $totalItemForPie = 0;
        foreach($topTen as $toptens ){
            $totalItemForPie = $totalItemForPie + 1;
            $sumOfTopten = $sumOfTopten + $toptens['total'];
        }

        return array($itemUses, $itemTotal, $reportData, $sumOfTopten, $totalItemForPie);
    }

    public function itemDetails($id, $em, $start, $end){

        if(($start != 0) && ($end != 0)){

            $query = $em->getRepository('PmsCoreBundle:ProjectCost')
                ->createQueryBuilder('pc')
                ->select('p.projectName')
                ->addSelect('i.itemName')
                ->addSelect('p.id')
                ->addSelect('SUM(pc.lineTotal) as total')
                ->where('pc.status = 1')
                ->andWhere('pc.item = ?1')
                ->andWhere('pc.dateOfCost >= ?2')
                ->andWhere('pc.dateOfCost <= ?3')
                ->setParameter('1', $id)
                ->setParameter('2', $start)
                ->setParameter('3', $end)
                ->join('pc.project', 'p')
                ->join('pc.item', 'i')
                ->groupBy('p.id')
                ->orderBy('p.id', 'DESC');
            $itemUses = $query->getQuery()->getResult();

            $query2 = $em->getRepository('PmsCoreBundle:ProjectCost')
                ->createQueryBuilder('pc')
                ->Select('SUM(pc.lineTotal) as total')
                ->where('pc.status = 1')
                ->andWhere('pc.item = ?1')
                ->andWhere('pc.dateOfCost >= ?2')
                ->andWhere('pc.dateOfCost <= ?3')
                ->setParameter('1', $id)
                ->setParameter('2', $start)
                ->setParameter('3', $end)
                ->join('pc.item', 'p');
            $itemTotal = $query2->getQuery()->getResult();
        }else{
            $query = $em->getRepository('PmsCoreBundle:ProjectCost')
                ->createQueryBuilder('pc')
                ->select('p.projectName')
                ->addSelect('i.itemName')
                ->addSelect('p.id')
                ->addSelect('SUM(pc.lineTotal) as total')
                ->addSelect('SUM(pc.status) as totalUsed')
                ->where('pc.status = 1')
                ->andWhere('pc.item = ?1')
                ->setParameter('1', $id)
                ->join('pc.project', 'p')
                ->join('pc.item', 'i')
                ->groupBy('p.id')
                ->orderBy('p.id', 'DESC');
            $itemUses = $query->getQuery()->getResult();

            $query2 = $em->getRepository('PmsCoreBundle:ProjectCost')
                ->createQueryBuilder('pc')
                ->Select('SUM(pc.lineTotal) as total')
                ->where('pc.status = 1')
                ->andWhere('pc.item = ?1')
                ->setParameter('1', $id)
                ->join('pc.item', 'p');
            $itemTotal = $query2->getQuery()->getResult();
        }

        $reportData = array();

        foreach($itemUses as $key => $itemUse){
            $data = array();
            $data['data'] = ($itemUse['total']*100)/$itemTotal[0]['total'];
            $data['label'] = $itemUse['projectName'];
            $reportData[] = $data;
            $projectItems[$key]['percentage'] = ($itemUse['total']*100)/$itemTotal[0]['total'];
        }

        return array($itemUses, $itemTotal, $reportData);
    }

    public function byItemDetails($id, $em, $start, $end){

        if(($start != 0) && ($end != 0)){

            $query = $em->getRepository('PmsCoreBundle:ProjectCost')
                ->createQueryBuilder('pc')
                ->select('p.projectName')
                ->addSelect('i.itemName')
                ->addSelect('pc.lineTotal')
                ->addSelect('pc.dateOfCost')
                ->addSelect('pc.quantity')
                ->addSelect('pc.unitPrice')
                ->addSelect('i.itemUnit')
                ->where('pc.status = 1')
                ->andWhere('pc.project = ?1')
                ->andWhere('pc.dateOfCost >= ?2')
                ->andWhere('pc.dateOfCost <= ?3')
                ->setParameter('1', $id)
                ->setParameter('2', $start)
                ->setParameter('3', $end)
                ->join('pc.project', 'p')
                ->join('pc.item', 'i');
            $itemUses = $query->getQuery()->getResult();

            $query2 = $em->getRepository('PmsCoreBundle:ProjectCost')
                ->createQueryBuilder('pc')
                ->Select('SUM(pc.lineTotal) as total')
                ->where('pc.status = 1')
                ->andWhere('pc.item = ?1')
                ->andWhere('pc.dateOfCost >= ?2')
                ->andWhere('pc.dateOfCost <= ?3')
                ->setParameter('1', $id)
                ->setParameter('2', $start)
                ->setParameter('3', $end)
                ->join('pc.item', 'p');
            $itemTotal = $query2->getQuery()->getResult();
        }else{
            $query = $em->getRepository('PmsCoreBundle:ProjectCost')
                ->createQueryBuilder('pc')
                ->select('p.projectName')
                ->addSelect('i.itemName')
                ->addSelect('pc.lineTotal')
                ->addSelect('pc.dateOfCost')
                ->addSelect('pc.quantity')
                ->addSelect('pc.unitPrice')
                ->addSelect('i.itemUnit')
                ->where('pc.status = 1')
                ->andWhere('pc.project = ?1')
                ->setParameter('1', $id)
                ->join('pc.project', 'p')
                ->join('pc.item', 'i');
            $itemUses = $query->getQuery()->getResult();

            $query2 = $em->getRepository('PmsCoreBundle:ProjectCost')
                ->createQueryBuilder('pc')
                ->Select('SUM(pc.lineTotal) as total')
                ->where('pc.status = 1')
                ->andWhere('pc.project = ?1')
                ->setParameter('1', $id)
                ->join('pc.item', 'p');
            $itemTotal = $query2->getQuery()->getResult();
        }

        $reportData = array();

        foreach($itemUses as $key => $itemUse){
            $data = array();
            $data['data'] = ($itemUse['lineTotal']*100)/$itemTotal[0]['total'];
            $data['label'] = $itemUse['itemName'];
            $reportData[] = $data;
            $projectItems[$key]['percentage'] = ($itemUse['lineTotal']*100)/$itemTotal[0]['total'];
        }

        return array($itemUses, $itemTotal, $reportData);
    }

    public function projectDetails($id, $em, $start, $end){

        if(($start != 0) && ($end != 0)){

            $query = $em->getRepository('PmsCoreBundle:ProjectCost')
                ->createQueryBuilder('pc')
                ->select('p.projectName')
                ->addSelect('i.itemName')
                ->addSelect('i.id')
                ->addSelect('SUM(pc.lineTotal) as total')
                ->addSelect('SUM(pc.quantity) as quantities')
                ->addSelect('SUM(pc.status) as totalUsed')
                ->addSelect('i.itemUnit')
                ->where('pc.status = 1')
                ->andWhere('pc.project = ?1')
                ->andWhere('pc.dateOfCost >= ?2')
                ->andWhere('pc.dateOfCost <= ?3')
                ->setParameter('1', $id)
                ->setParameter('2', $start)
                ->setParameter('3', $end)
                ->join('pc.project', 'p')
                ->join('pc.item', 'i')
                ->groupBy('i.id')
                ->orderBy('i.id', 'DESC');
            $projectItems = $query->getQuery()->getResult();

            $query2 = $em->getRepository('PmsCoreBundle:ProjectCost')
                ->createQueryBuilder('pc')
                ->Select('SUM(pc.lineTotal) as total')
                ->where('pc.status = 1')
                ->andWhere('pc.project = ?1')
                ->andWhere('pc.dateOfCost >= ?2')
                ->andWhere('pc.dateOfCost <= ?3')
                ->setParameter('1', $id)
                ->setParameter('2', $start)
                ->setParameter('3', $end)
                ->join('pc.project', 'p')
                ->join('pc.item', 'i');
            $projectItems2 = $query2->getQuery()->getResult();
        }else{
            $query = $em->getRepository('PmsCoreBundle:ProjectCost')
                ->createQueryBuilder('pc')
                ->select('p.projectName')
                ->addSelect('i.itemName')
                ->addSelect('i.id')
                ->addSelect('SUM(pc.lineTotal) as total')
                ->addSelect('SUM(pc.quantity) as quantities')
                ->addSelect('SUM(pc.status) as totalUsed')
                ->addSelect('i.itemUnit')
                ->where('pc.status = 1')
                ->andWhere('pc.project = ?1')
                ->setParameter('1', $id)
                ->join('pc.project', 'p')
                ->join('pc.item', 'i')
                ->groupBy('i.id')
                ->orderBy('i.id', 'DESC');
            $projectItems = $query->getQuery()->getResult();

            $query2 = $em->getRepository('PmsCoreBundle:ProjectCost')
                ->createQueryBuilder('pc')
                ->Select('SUM(pc.lineTotal) as total')
                ->where('pc.status = 1')
                ->andWhere('pc.project = ?1')
                ->setParameter('1', $id)
                ->join('pc.project', 'p')
                ->join('pc.item', 'i');
            $projectItems2 = $query2->getQuery()->getResult();
        }

        $reportData = array();

        foreach($projectItems as $key => $projectItem){
            $data = array();
            $data['data'] = ($projectItem['total']*100)/$projectItems2[0]['total'];
            $data['label'] = $projectItem['itemName'];
            $reportData[] = $data;
            $projectItems[$key]['percentage'] = ($projectItem['total']*100)/$projectItems2[0]['total'];
        }

        return array($projectItems, $projectItems2, $reportData);
    }

    public function projectReport($em, $start, $end){

        if(($start != 0) && ($end != 0)){

            $query = $em->getRepository('PmsCoreBundle:ProjectCost')
                ->createQueryBuilder('pc')
                ->select('p.projectName')
                ->addSelect('p.id')
                ->addSelect('SUM(pc.lineTotal) as total')
                ->where('pc.status = 1')
                ->andWhere('pc.dateOfCost >= ?1')
                ->andWhere('pc.dateOfCost <= ?2')
                ->setParameter('1', $start)
                ->setParameter('2', $end)
                ->join('pc.project', 'p')
                ->groupBy('p.id')
                ->orderBy('p.id', 'DESC');
            $projectCosts = $query->getQuery()->getResult();

            $forPieChart = $em->getRepository('PmsCoreBundle:ProjectCost')
                ->createQueryBuilder('pc')
                ->select('p.projectName')
                ->addSelect('p.id')
                ->addSelect('SUM(pc.lineTotal) as total')
                ->where('pc.status = 1')
                ->andWhere('pc.dateOfCost >= ?1')
                ->andWhere('pc.dateOfCost <= ?2')
                ->setParameter('1', $start)
                ->setParameter('2', $end)
                ->join('pc.project', 'p')
                ->join('pc.item', 'i')
                ->groupBy('p.id')
                ->orderBy('pc.lineTotal', 'DESC')
                ->setMaxResults('10');
            $topTen = $forPieChart->getQuery()->getResult();
        }else{
            $query = $em->getRepository('PmsCoreBundle:ProjectCost')
                ->createQueryBuilder('pc')
                ->select('p.projectName')
                ->addSelect('p.id')
                ->addSelect('SUM(pc.lineTotal) as total')
                ->where('pc.status = 1')
                ->join('pc.project', 'p')
                ->groupBy('p.id')
                ->orderBy('p.id', 'DESC');
            $projectCosts = $query->getQuery()->getResult();

            $forPieChart = $em->getRepository('PmsCoreBundle:ProjectCost')
                ->createQueryBuilder('pc')
                ->select('p.projectName')
                ->addSelect('p.id')
                ->addSelect('SUM(pc.lineTotal) as total')
                ->where('pc.status = 1')
                ->join('pc.project', 'p')
                ->join('pc.item', 'i')
                ->groupBy('p.id')
                ->orderBy('pc.lineTotal', 'DESC')
                ->setMaxResults('10');
            $topTen = $forPieChart->getQuery()->getResult();
        }

        $query2 = $em->getRepository('PmsCoreBundle:ProjectCost')
            ->createQueryBuilder('p')
            ->Select('SUM(p.lineTotal) as total')
            ->where('p.status = 1')
            ->getQuery();
        $cost = $query2->getResult();

        $reportData = array();

        foreach($topTen as $key => $projectCost){
            $data = array();
            $data['data'] = ($projectCost['total']*100)/$cost[0]['total'];
            $data['label'] = $projectCost['projectName'];
            $reportData[] = $data;
            $projectCosts[$key]['percentage'] = ($projectCost['total']*100)/$cost[0]['total'];
        }

        $sumOfTopten = 0;
        $totalProjectForPic = 0;
        foreach($topTen as $toptens ){
            $totalProjectForPic = $totalProjectForPic + 1;
            $sumOfTopten = $sumOfTopten + $toptens['total'];
        }

        return array($projectCosts, $cost, $reportData, $sumOfTopten, $totalProjectForPic);
    }

    public function overView($em, $start, $end){

        $connection = $em->getConnection();

        if(($start != 0) && ($end != 0)){

            $query = $em->getRepository('PmsCoreBundle:ProjectCost')
                ->createQueryBuilder('pc')
                ->select('p.projectName')
                ->addSelect('i.itemName')
                ->addSelect('i.id')
                ->addSelect('SUM(pc.lineTotal) as total')
                ->addSelect('SUM(pc.quantity) as quantity')
                ->where('pc.status = 1')
                ->andWhere('pc.dateOfCost >= ?1')
                ->andWhere('pc.dateOfCost <= ?2')
                ->setParameter('1', $start)
                ->setParameter('2', $end)
                ->join('pc.project', 'p')
                ->join('pc.item', 'i')
                ->groupBy('i.id')
                ->orderBy('i.id', 'DESC');
            $itemUses = $query->getQuery()->getResult();
        }else{

            $query = $em->getRepository('PmsCoreBundle:ProjectCost')
                ->createQueryBuilder('pc')
                ->select('p.projectName')
                ->addSelect('i.itemName')
                ->addSelect('i.id')
                ->addSelect('i.itemUnit')
                ->addSelect('SUM(pc.lineTotal) as total')
                ->addSelect('SUM(pc.quantity) as quantity')
                ->where('pc.status = 1')
                ->join('pc.project', 'p')
                ->join('pc.item', 'i')
                ->groupBy('i.id')
                ->orderBy('i.id', 'DESC');
            $itemUses = $query->getQuery()->getResult();
        }

        foreach ($itemUses as $key => $item) {

            $statement = $connection->prepare("SELECT project.project_name, MAX(project_cost.unit_price) as projectHighest, MIN(project_cost.unit_price) as projectLowest, project_cost.item
            FROM project_cost
            JOIN project ON project.id = project_cost.project
            WHERE project_cost.item = :itemId AND project_cost.status = 1
            GROUP BY project_cost.project");
            $statement->bindValue('itemId', $item['id']);
            $statement->execute();
            $itemUses[$key]['projectSummary'] = $statement->fetchAll();
        }

        return $itemUses;
    }
}
