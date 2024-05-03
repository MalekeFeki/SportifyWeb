<?php
// src/Event/NewClaimEvent.php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

class NewClaimEvent extends Event
{
    private $claim;

    public function __construct($claim)
    {
        $this->claim = $claim;
    }

    public function getClaim()
    {
        return $this->claim;
    }
}
