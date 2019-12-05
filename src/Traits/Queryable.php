<?php

namespace CodeHappy\DataLayer\Traits;

use CodeHappy\DataLayer\Traits\Queries\Conditionable;
use CodeHappy\DataLayer\Traits\Queries\EagerLoading;
use CodeHappy\DataLayer\Traits\Queries\Joinable;
use CodeHappy\DataLayer\Traits\Queries\Showable;
use CodeHappy\DataLayer\Traits\Queries\Sortable;
use CodeHappy\DataLayer\Traits\Queries\Summariable;

trait Queryable
{
    use Conditionable;
    use EagerLoading;
    use Joinable;
    use Showable;
    use Sortable;
    use Summariable;
}
