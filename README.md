# URL
一个对URL的通用操作类。

基本用法:
```php
$URL = new URL("http://abc.com/?page=1&param=a");
$URL->setQuery(["page"=>2]);
$newUrl = $URL->build();

echo $newUrl;   //will output: http://abc.com/?page=2&param=a
```
---
```php
$URL = new URL("http://abc.com/?page=1&param=a");
$URL->replaceQuery(["page"=>2]);
$newUrl = $URL->build();

echo $newUrl;   //will output: http://abc.com/?page=2
```

可以全局地urlencode整条URL:
```php
$URL = new URL("http://abc.com/目录1/目录2/index.php?cat=科技&rate= 非常好");
$URL->encode();
$newUrl = $URL->build();

echo $newUrl;   //http://abc.com/%E7_%AE%E5%BD_1/%E7_%AE%E5%BD_2/index.php?cat=%E7%A7_%E6__&rate=+%E9__%E5%B8%B8%E5%A5%BD
```
---
构造函数不传参,默认为改变当前URL(需要在浏览器内)的某个参数:
```php
$URL = new URL();
$URL->setQuery(["page"=>2]);
$newUrl =  $URL->build();
```

快捷方式:
```php
echo URL::rebuild(['page'=>2]);
```
---

四个静态方法的调用:
```php
$domain = URL::domain($url);    //url.com
$queryString = URL::queryString($url);  //query=1&query=2
$query = URL::query($url);  //['query'=>1,'query2'=>2]
$dir = URL::dir($url);  //http://url.com/doc
```