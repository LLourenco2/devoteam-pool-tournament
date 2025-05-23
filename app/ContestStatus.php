<?php

namespace App;

enum ContestStatus : string
{
    case Scheduled = 'scheduled';
    case Ongoing = 'ongoing';
    case Ended = 'ended';
}
