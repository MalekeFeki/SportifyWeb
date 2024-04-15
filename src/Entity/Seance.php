<?php

namespace App\Entity;

use App\Repository\SRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[Doctrine\ORM\Mapping\Entity]
// #[Doctrine\ORM\Mapping\Table(name: "seance", indexes: [#[Doctrine\ORM\Mapping\Index(name: "Fk_SeanceSalle", columns: ["IdS"])])]]

class Seance
{
    #[Doctrine\ORM\Mapping\Column(type: "integer", nullable: false)]
    #[Doctrine\ORM\Mapping\Id]
    #[Doctrine\ORM\Mapping\GeneratedValue(strategy: "IDENTITY")]
    private $idseance;

    #[Doctrine\ORM\Mapping\Column(type: "string", length: 50, nullable: false)]
    private $nomseance;

    #[Doctrine\ORM\Mapping\Column(type: "time", nullable: false)]
    private $debut;

    #[Doctrine\ORM\Mapping\Column(type: "time", nullable: false)]
    private $fin;

    #[Doctrine\ORM\Mapping\Column(type: "date", nullable: false)]
    private $dates;

    // #[Doctrine\ORM\Mapping\ManyToOne(targetEntity: Salle::class)]
    // #[Doctrine\ORM\Mapping\JoinColumns([
    //     #[Doctrine\ORM\Mapping\JoinColumn(name: "IdS", referencedColumnName: "idS")]
    // ])]
    // private $ids;


}
