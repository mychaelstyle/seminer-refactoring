<?php

class Csv
{
    public function read($path)
    {
        $fp = fopen($path, "r");
    }
}
