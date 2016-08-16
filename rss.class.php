<?php
// +----------------------------------------------------------------------
// | YBlog
// +----------------------------------------------------------------------
// | Copyright (c) 2008 http://www.hzhuti.com/nokia/n97/ All rights reserved.
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: yhustc <yhustc@gmail.com>
// | Another: lightning-zgc.com <lightning-zgc.com>
// +----------------------------------------------------------------------
// $Id$

/**
 +------------------------------------------------------------------------------
 * RSS生成类
 +------------------------------------------------------------------------------
 * @author    yhustc <yhustc@gmail.com>
 * @version   $Id$
 +------------------------------------------------------------------------------
 */
class RSS
{
    /**
     +----------------------------------------------------------
     * RSS频道名
     +----------------------------------------------------------
     * @var string
     * @access protected
     +----------------------------------------------------------
     */
    protected $channel_title = '';
    /**
     +----------------------------------------------------------
     * RSS频道链接
     +----------------------------------------------------------
     * @var string
     * @access protected
     +----------------------------------------------------------
     */
    protected $channel_link = '';
    /**
     +----------------------------------------------------------
     * RSS频道描述
     +----------------------------------------------------------
     * @var string
     * @access protected
     +----------------------------------------------------------
     */
    protected $channel_description = '';
    /**
     +----------------------------------------------------------
     * RSS频道使用的小图标的URL
     +----------------------------------------------------------
     * @var string
     * @access protected
     +----------------------------------------------------------
     */
    protected $channel_imgurl = '';
    /**
     +----------------------------------------------------------
     * RSS频道所使用的语言
     +----------------------------------------------------------
     * @var string
     * @access protected
     +----------------------------------------------------------
     */
    protected $language = 'zh';
    /**
     +----------------------------------------------------------
     * RSS文档创建日期，默认为今天
     +----------------------------------------------------------
     * @var string
     * @access protected
     +----------------------------------------------------------
     */
    protected $pubDate = '';
    protected $lastBuildDate = '';

    protected $generator = 'RSS Generator';

    /**
     +----------------------------------------------------------
     * RSS单条信息的数组
     +----------------------------------------------------------
     * @var string
     * @access protected
     +----------------------------------------------------------
     */
    protected $items = array();
    protected $this_url = '';

    /**
     +----------------------------------------------------------
     * 构造函数
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param string $title  RSS频道名
     * @param string $link  RSS频道链接
     * @param string $description  RSS频道描述
     * @param string $imgurl  RSS频道图标
     +----------------------------------------------------------
     */
    public function __construct($title, $link, $description, $imgurl = '')
    {
        $this->channel_title = $title;
        $this->channel_link = $link;
        $this->channel_description = $description;
        $this->channel_imgurl = $imgurl;
        $this->pubDate = gmdate(DATE_RFC822);
        $this->lastBuildDate = gmdate(DATE_RFC822);
        $this->this_url = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['DOCUMENT_URI'].htmlentities('?'.$_SERVER['QUERY_STRING']);
    }

    /**
     +----------------------------------------------------------
     * 设置私有变量
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param string $key  变量名
     * @param string $value  变量的值
     +----------------------------------------------------------
     */
     public function Config($key,$value)
     {
        $this->{$key} = $value;
     }

    /**
     +----------------------------------------------------------
     * 添加RSS项
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param string $title  日志的标题
     * @param string $link  日志的链接
     * @param string $description  日志的摘要
     * @param string $pubDate  日志的发布日期
     +----------------------------------------------------------
     */
     function AddItem($title, $link, $description, $pubDate)
     {
        $this->items[] = array('title' => $title, 'link' => $link, 'description' => $description, 'pubDate' => $pubDate);
     }

     /**
     +----------------------------------------------------------
     * 输出RSS的XML为字符串
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     */
    public function Fetch()
    {
        $rss = '<?xml version="1.0" encoding="utf-8" ?>';
        $rss = '<rss xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:atom="http://www.w3.org/2005/Atom" version="2.0">';
        $rss .= "<channel>";
        $rss .= "<title><![CDATA[{$this->channel_title}]]></title>";
        $rss .= "<description><![CDATA[{$this->channel_description}]]></description>";
        $rss .= "<link>{$this->channel_link}</link>";
        $rss .= "<language>{$this->language}</language>";
        $rss .= "<atom:link href=\"{$this->this_url}\" rel=\"self\" type=\"application/rss+xml\" />";

        if (!empty($this->pubDate))
            $rss .= "<pubDate>{$this->pubDate}</pubDate>";
        if (!empty($this->lastBuildDate))
            $rss .= "<lastBuildDate>{$this->lastBuildDate}</lastBuildDate>";
        if (!empty($this->generator))
            $rss .= "<generator>{$this->generator}</generator>";

        $rss .= "<ttl>5</ttl>";

        if (!empty($this->channel_imgurl)) {
            $rss .= "<image>";
            $rss .= "<title><![CDATA[{$this->channel_title}]]></title>";
            $rss .= "<link>{$this->channel_link}</link>";
            $rss .= "<url>{$this->channel_imgurl}</url>";
            $rss .= "</image>";
        }

        for ($i = 0; $i < count($this->items); $i++) {
            $rss .= "<item>";
            $rss .= "<title><![CDATA[".$this->items[$i]['title']."]]></title>";
            $rss .= "<link>".$this->items[$i]['link']."</link>";
            $rss .= "<description><![CDATA[".$this->items[$i]['description']."]]></description>";
            $rss .= "<pubDate>".$this->items[$i]['pubDate']."</pubDate>";
            $rss .= "<guid>".$this->items[$i]['link']."</guid>";
            $rss .= "</item>";
        }

        $rss .= "</channel></rss>";
        return $rss;
    }

    /**
     +----------------------------------------------------------
     * 输出RSS的XML到浏览器
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     */
    public function Display()
    {
        header("Content-Type: text/xml; charset=utf-8");
        echo $this->Fetch();
        exit;
    }
}
