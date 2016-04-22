<?php
/**
 * Created by PhpStorm.
 * User: kingmax
 * Date: 16/4/21
 * Time: 下午1:21
 */

namespace rayful\Tool;


class Test extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        require_once "URL.php";
    }

    public function test1()
    {
        $URL = new URL("http://abc.com/?page=1&param=a");
        $URL->setQuery(["page" => 2]);
        $newUrl = $URL->build();

        echo $newUrl;   //will output: http://abc.com/?page=2&param=a
    }

    public function test2()
    {
        $URL = new URL("http://abc.com/?page=1&param=a");
        $URL->replaceQuery(["page" => 2]);
        $newUrl = $URL->build();

        echo $newUrl;   //will output: http://abc.com/?page=2
    }

    public function test3()
    {
        $URL = new URL("http://abc.com/目录1/目录2/index.php?cat=科技&rate= 非常好");
        $URL->encode();
        $newUrl = $URL->build();

        echo $newUrl;   //http://abc.com/%E7_%AE%E5%BD_1/%E7_%AE%E5%BD_2/index.php?cat=%E7%A7_%E6__&rate=+%E9__%E5%B8%B8%E5%A5%BD
    }

    public function test4()
    {
        $url = "http://url.com/doc/index.php?query=1&query2=2#abc=3";
        $domain = URL::domain($url);    //url.com
        $queryString = URL::queryString($url);  //query=1&query=2
        $query = URL::query($url);  //['query'=>1,'query2'=>2]
        $dir = URL::dir($url);  //http://url.com/doc

        echo $domain;
        echo $queryString;
        print_r($query);
        echo $dir;
    }

}
