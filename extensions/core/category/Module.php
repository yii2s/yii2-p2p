<?php
/**
 * @author Lujie.Zhou(lujie.zhou@jago-ag.cn) 
 * @Date 2014/11/10
 * @Time 20:11
 */

namespace core\category;


class Module extends \kiwi\base\Module
{
    public static $active = false;

    public static $version = 'v0.1.0';

    public static $depends = ['core_tree'];
} 