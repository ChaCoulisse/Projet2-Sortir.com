<?php

namespace App\Repository;

use App\Entity\Trip;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Statement;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use function Doctrine\ORM\QueryBuilder;

/**
 * @method Trip|null find($id, $lockMode = null, $lockVersion = null)
 * @method Trip|null findOneBy(array $criteria, array $orderBy = null)
 * @method Trip[]    findAll()
 * @method Trip[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TripRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trip::class);
    }

    public function findAllTrip(){
        $queryBuilder = $this->createQueryBuilder('trip')
            ->join('trip.campus', 'campus')->addSelect('campus')
            ->join('trip.organizer', 'organizer')->addSelect('organizer')
            ->join('trip.state', 'state')->addSelect('state')
            ->join('trip.participant', 'participant')->addSelect('participant')
            ->where('state.wording != :archive')
            ->setParameter('archive', 'Archivé')
            ->addOrderBy('trip.startHour', 'ASC');
        return $queryBuilder->getQuery()->getResult();
    }

    public function findPlaceByCity($city_id){

        $queryBuilder= $this->createQueryBuilder('place')
                ->join('place.city_id', 'city')->addSelect('city')
                ->where('place.city_id = :city')
                ->setParameter('city', $city_id)
                ->orderBy('place.name', 'asc');
        return $queryBuilder->getQuery()->getResult();
    }

    public function displayTrip($id)
    {
        $queryBuilder = $this->createQueryBuilder('trip')
            ->join('trip.campus', 'campus')->addSelect('campus')
            ->join('trip.organizer', 'organizer')->addSelect('organizer')
            ->join('trip.place', 'place')
            ->join('place.city', 'city')
            ->where('trip.id = :id')
            ->setParameter('id', $id);
//dd($queryBuilder->getQuery()->getResult());
        return $queryBuilder->getQuery()->getResult();

    }

    public function listParticipantsByTrip($id){
        $queryBuilder = $this->createQueryBuilder('trip')
            ->join('trip.participantsTrip', 'participants')
            ->where('trip.id = :id')
            ->setParameter('id', $id);
        dd($queryBuilder->getQuery()->getResult());
    }

    public function findTripByParameter(int $campusId, string $contain, string $startDate, string $endDate, bool $ownerId, bool $registeredY, bool $registeredN, bool $stateId, int $userId){

        $queryNotIn = $this->createQueryBuilder('user')
            ->select('participant.id')
            ->join('user.participant', 'participant')
            ->where('user.id != :registered');

        $queryBuilder = $this->createQueryBuilder('trip')
            ->join('trip.campus', 'campus')->addSelect('campus')
            ->join('trip.organizer', 'organizer')->addSelect('organizer')
            ->join('trip.state', 'state')->addSelect('state')
            ->join('trip.participant', 'participant')->addSelect('participant')
            ->where('state.wording != :archive')
            ->setParameter('archive', 'Archivé')
            ->andWhere('trip.campus = :campusId')
            ->setParameter('campusId', $campusId)
            ->andWhere('trip.name like :name')
            ->setParameter('name', '%'.$contain.'%')
            ->andWhere('trip.startHour >= :start')
            ->setParameter('start', $startDate)
            ->andWhere("DATE_ADD(trip.startHour, trip.duration, 'second') <= :end")
            ->setParameter('end', $endDate);
//            ->andWhere('trip.campus = :campus')
//            ->setParameter('campus', $endDate)
            if($stateId){
                $queryBuilder->andWhere('trip.state != :stateId')
                    ->setParameter('stateId', 5);
            }
            if ($ownerId){
                $queryBuilder->andWhere('trip.organizer = :ownerId')
                    ->setParameter('ownerId', $userId);
            }
            if ($registeredY){
                $queryBuilder->andWhere($queryBuilder->expr()->notIn('participant.id', $queryNotIn->getDQL()))
                    ->setParameter('registered', $userId);
            }
            if($registeredN){
                $queryBuilder->andWhere('participant.id != :registered')
                    ->setParameter('registered', $userId);
            }
            $queryBuilder->addOrderBy('trip.startHour', 'ASC');
//        dd($queryBuilder->getQuery()->getResult());
        return $queryBuilder->getQuery()->getResult();
    }

    public function updateState($tripId, $stateId)
    {
        $queryBuilder = $this->createQueryBuilder('trip')
            ->where('trip.id = :tripId')
            ->setParameter('tripId', $tripId)
            ->update()
            ->set('trip.state', ':id')
            ->setParameter('id', $stateId);
        return $queryBuilder->getQuery()->getResult();
    }
}