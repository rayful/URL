<?php
/**
 * 一个URL的操作类
 * 
 * 基本用法(改变指定URL的某个参数):
 * $URL = new URL("http://abc.com/?page=1");
 * $URL->setQuery(["page"=>1]);
 * return $URL->build();
 * 
 * 静态调用
 * 改变当前URL的某个参数:
 * return URL::rebuild(['page'=>2]);
 * 
 * 具体请见README.MD
 * 
 * Created by PhpStorm.
 * User: kingmax
 * Date: 16/4/21
 * Time: 上午7:44
 */

namespace rayful\Tool;


class URL
{
    /**
     * 当前操作的URL地址
     * @var string
     */
    public $url;

    /**
     * 当前的分析结果，包含：scheme、host、path、query
     * @var array
     */
    protected $parse;

    /**
     * URL主机地址
     * @example http://www.abc.com
     * @var string
     */
    protected $host;

    /**
     * 文件路径
     * @example /index.php
     * @var string
     */
    protected $path;

    /**
     * 查询数组
     * @example ['name'=>"a",'page'=>1]
     * @var array
     */
    protected $query;

    /**
     * #号后面的部分
     * @var string
     */
    protected $fragment;

    function __construct($url = null)
    {
        if ($url) {
            $this->url = $url;
        } else {
            $this->url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER ['REQUEST_URI'];
        }
    }

    function __toString()
    {
        return $this->build();
    }


    public static function rebuild($query)
    {
        $URL = new URL();
        $URL->setQuery($query);
        return $URL->build();
    }

    public static function domain($url)
    {
        $URL = new URL($url);
        return $URL->getDomain();
    }

    public static function query($url = null)
    {
        $URL = new URL($url);
        $URL->parse();
        return $URL->query;
    }

    public static function queryString($url = null)
    {
        $URL = new URL($url);
        $parse = parse_url($URL->url);
        return $parse['query'];
    }

    public static function dir($url = null)
    {
        $URL = new URL($url);
        return $URL->getDirectory();
    }

    public function getHost()
    {
        if (is_null($this->parse)) {
            $this->parse();
        }
        return $this->host;
    }

    public function getPath()
    {
        if (is_null($this->path)) {
            $this->parse();
        }
        return $this->path;
    }

    public function getQuery()
    {
        if (is_null($this->query)) {
            $this->parse();
        }
        return $this->query;
    }

    public function getExtension()
    {
        return pathinfo(parse_url($this->url, PHP_URL_PATH), PATHINFO_EXTENSION);
    }

    public function getDomain()
    {
        if (is_null($this->parse)) {
            $this->parse();
        }
        $domain = isset($this->parse['host']) ? $this->parse['host'] : '';
        if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
            return $regs['domain'];
        }
        return false;
    }

    public function getDirectory()
    {
        return $this->getHost() . dirname($this->getPath());
    }

    protected function parse()
    {
        $this->parse = parse_url($this->url);
        $this->host = $this->parse['scheme'] . "://" . $this->parse['host'];
        $this->path = $this->parse['path'];
        $this->fragment = $this->parse['fragment'];
        parse_str($this->parse ['query'], $this->query);
    }

    public function setQuery($query)
    {
        $old_query = $this->getQuery();
        $new_query = array_merge($old_query, $query);
        $this->query = $new_query;
        return $this;
    }

    public function replaceQuery($query)
    {
        $this->query = $query;
        return $this;
    }

    public function build()
    {
        $this->url = $this->host . $this->path . "?" . http_build_query($this->query);
        if ($this->fragment) {
            $this->url .= "#" . $this->fragment;
        }
        return $this->url;
    }

    /**
     * 将一个URL里面的特殊字符编码变成程序可以正常抓取的URL
     * @param string $method
     * @return string
     */
    public function encode($method = "urlencode")
    {
        $parts = parse_url($this->url);
        $parts ['path'] = implode("/", array_map($method, explode("/", $parts ['path'])));
        $this->url = $parts['scheme'] . "://" . $parts['host'] . $parts ['path'];
        if ($parts ['query']) $this->url .= "?" . $parts ['query'];
        return $this->url;
    }
}