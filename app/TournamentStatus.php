<?php

namespace App;

enum TournamentStatus : string
{
    case Open = 'open';
    case Ongoing = 'ongoing';
    case Ended = 'ended';
}
