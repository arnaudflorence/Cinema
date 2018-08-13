<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class FilmRepository extends EntityRepository{

//methode pour avoir la liste de film par ordre alphabetique
  public function getFilmsOrderByTitle(){
    $qb = $this ->createQueryBuilder('f')
                ->orderBy('f.titreFilm', 'ASC')
                ->getQuery()
                ->getResult();
    return $qb;
  }

//ancienne methode de requet
  /*public function getFilmsOrderByTitle(){
    $entityManager = $this->getEntityManager();
    $query = $entityManager->createQuery(
      'SELECT f
      FROM App\Entity\Film f
      ORDER BY f.titreFilm ASC'
    );
  }*/
}
