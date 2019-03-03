<?php 

class connect
{
	public function getConnect()
	{
		$link = new Redis();

		$link->connect('39.96.6.70','6379');

	    $link->auth('wC199801310017');

	    $link->select('2');

	    return $link;
	}
}
