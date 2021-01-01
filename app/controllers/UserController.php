<?php

namespace controller;

use core\Controller;
use core\Model;
use core\Config;

class UserController extends Controller
{
    /**
     *  在 users 表中插入一条数据
     *
     */
    public function postInsert()
    {
        $keys = array_keys($_POST);
        $values = array_values($_POST);
        $i=0;
        $prepare = '';
        while(isset($keys[$i])) {
            $prepare .= '?,';
            $i++;
        }
        return Model::prepare("insert into users (".implode($keys,',').") values (".trim($prepare,',').")")
            ->bind($values)
            ->get();
    }


    public function getOne()
	{

	    $res = Model::prepare("select * from users where id = ?")
	            ->bind([1])
	            ->get();
	    
	}
	/**
	 * 获取所有数据
	 *
	 */
	public function getAll()
	{
		
	    $res = Model::prepare("select * from users")->bind()->get();
	    var_dump($res);
	}


	public function postUpdate()
    {
        return Model::prepare("update users set age = ? where name = ?")
            ->bind([$_POST['age'],$_POST['name']])
            ->get();
    }


    public function postDelete()
    {
        return Model::prepare("delete from users where name = ?")
            ->bind([$_POST['name']])
            ->get();
    }


}