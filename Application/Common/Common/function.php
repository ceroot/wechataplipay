<?php
/**
 * Created by PhpStorm.
 * User: liushizhao
 * Date: 2017/7/12
 * Time: 下午4:57
 */
/**
 * 签名字符串
 * @param  [type] $prestr 需要签名的字符串
 * @param  string $key 私钥
 * @return [type]         结果
 */

function getPage($count,$pageSize=10){
    $page= new \Common\Lib\Page($count,$pageSize);
    $page->setConfig("header","<li class='rows'>共<b>%TOTAL_ROW%</b>条记录&nbsp;第<b>%NOW_PAGE%</b>页/共<b>%TOTAL%</b></li>");
    $page->setConfig('prev','上一页');
    $page->setConfig("next","下一页");
    $page->setConfig("last","末页");
    $page->setConfig("first","首页");
    $page->setConfig("theme",'<ul class="pagination"><li>%UP_PAGE%</li></ul>%LINK_PAGE%<ul class="pagination"><li>%DOWN_PAGE%</li></ul>');
    $page->lastSuffix=false;
    return $page;

}
function dd($data,$die=1){
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    if($die==1){
        die;
    }
}
/**
 * 签名字符串
 * @param  [type] $prestr 需要签名的字符串
 * @param  string $key 私钥
 * @return [type]         结果
 */
function md5Sign($prestr, $key = 'blue_zx')
{
    $prestr = $prestr . $key;
    return md5($prestr);
}