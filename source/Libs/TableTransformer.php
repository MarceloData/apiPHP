<?php

namespace Source\Libs;

use League\Fractal;

class TableTransformer extends Fractal\TransformerAbstract
{
    public function transform(array $table)
	{
        $array = [];
        foreach($table as $key=>$value) {
            $array = array_merge($array, [$key => $value]);
        }
        return $array;
	}
}