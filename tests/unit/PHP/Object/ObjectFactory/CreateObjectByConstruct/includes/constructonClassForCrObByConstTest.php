<?php
class ObjectDependency1
{
}

class ObjectDependency2
{
}

class integration
{

}


class ObjectTestByConstruct
{
    public $dep;
    public $b;
    public $c;
    
    public function __construct(ObjectDependency1 $dep, int $b = 2, int $c = 3)
    {
        $this->dep = $dep;
        $this->b = $b;
        $this->c = $c;
    }
}

class ObjectTestByConstruct2
{
    public $dep;
    public $b;
    public $c;

    public function __construct(ObjectDependency1 $dep, int $b, int $c = 3)
    {
        $this->dep = $dep;
        $this->b = $b;
        $this->c = $c;
    }
}

class ObjectTestByConstruct3
{
    public $dep;
    public $b;
    public $c;

    public function __construct(ObjectDependency1 $dep, int $b, int $c )
    {
        $this->dep = $dep;
        $this->b = $b;
        $this->c = $c;
    }
}
