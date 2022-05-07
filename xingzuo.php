<?php

namespace addons\cms\controller\api;

use think\Db;

use function PHPSTORM_META\type;

class Test1 extends Base
{
    protected $noNeedLogin = ['*'];

    public function index()
    {
        $month = 4;
        $day = 19;
        return $this->get_xingzuo($month, $day);
    }

    /* *
     * 获取星座
     * 星座是按阳历来计算的
     * $month 阳历月份
     * $day  阳历日期
     * */
    public static function get_xingzuo($month, $day)
    {
        $xingzuo = '';
        // 检查参数有效性
        if ($month < 1 || $month > 12 || $day < 1 || $day > 31) {
            return $xingzuo;
        }

        if (($month == 1 && $day >= 20) || ($month == 2 && $day <= 18)) {
            $xingzuo = "水瓶";
        } else if (($month == 2 && $day >= 19) || ($month == 3 && $day <= 20)) {
            $xingzuo = "双鱼";
        } else if (($month == 3 && $day >= 21) || ($month == 4 && $day <= 19)) {
            $xingzuo = "白羊";
        } else if (($month == 4 && $day >= 20) || ($month == 5 && $day <= 20)) {
            $xingzuo = "金牛";
        } else if (($month == 5 && $day >= 21) || ($month == 6 && $day <= 21)) {
            $xingzuo = "双子";
        } else if (($month == 6 && $day >= 22) || ($month == 7 && $day <= 22)) {
            $xingzuo = "巨蟹";
        } else if (($month == 7 && $day >= 23) || ($month == 8 && $day <= 22)) {
            $xingzuo = "狮子";
        } else if (($month == 8 && $day >= 23) || ($month == 9 && $day <= 22)) {
            $xingzuo = "处女";
        } else if (($month == 9 && $day >= 23) || ($month == 10 && $day <= 23)) {
            $xingzuo = "天秤";
        } else if (($month == 10 && $day >= 24) || ($month == 11 && $day <= 22)) {
            $xingzuo = "天蝎";
        } else if (($month == 11 && $day >= 23) || ($month == 12 && $day <= 21)) {
            $xingzuo = "射手";
        } else if (($month == 12 && $day >= 22) || ($month == 1 && $day <= 19)) {
            $xingzuo = "摩羯";
        }

        return $xingzuo;
    }
}
