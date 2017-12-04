<?php

namespace taobig\helpers;

//中国有34个省市自治区，其中23个省，4个直辖市，5个少数民族自治区，2个特别行政区。
class ProvinceHelper
{
//"港"=>"香港",//省会：九龙
//"澳"=>"澳门",//省会：澳门
//"台"=>"台湾省",//省会：台北市

//https://zh.wikipedia.org/wiki/中华人民共和国民用机动车号牌

//https://baike.baidu.com/item/省份简称
//当省份有两个或多个简称时，车牌简称取与省份全称有同字的。如：四川简称川或蜀，车牌简称取“川”字。
//内蒙古自治区简称为“内蒙古”，不可简称为“蒙”，但在车牌号中，只能使用一个字，所以用“蒙”字代替。
//香港特别行政区、澳门特别行政区进入内地车牌代码分别为“粤Z 港”“粤Z 澳”

    //key为该省市自治区的简称
    private $provinces =
        [
            ["name" => "黑龙江省", "short_name" => ["黑"], "license_plate_number" => "黑", "capital" => "哈尔滨市"],
            ["name" => "吉林省", "short_name" => ["吉"], "license_plate_number" => "吉", "capital" => "长春市"],
            ["name" => "辽宁省", "short_name" => ["辽"], "license_plate_number" => "辽", "capital" => "沈阳市"],
            ["name" => "河北省", "short_name" => ["冀"], "license_plate_number" => "冀", "capital" => "石家庄市"],
            ["name" => "河南省", "short_name" => ["豫"], "license_plate_number" => "豫", "capital" => "郑州市"],
            ["name" => "云南省", "short_name" => ["云", "滇"], "license_plate_number" => "云", "capital" => "昆明市"],
            ["name" => "湖南省", "short_name" => ["湘"], "license_plate_number" => "湘", "capital" => "长沙市"],
            ["name" => "安徽省", "short_name" => ["皖"], "license_plate_number" => "皖", "capital" => "合肥市"],
            ["name" => "山东省", "short_name" => ["鲁"], "license_plate_number" => "鲁", "capital" => "济南市"],
            ["name" => "江苏省", "short_name" => ["苏"], "license_plate_number" => "苏", "capital" => "南京市"],
            ["name" => "浙江省", "short_name" => ["浙"], "license_plate_number" => "浙", "capital" => "杭州市"],
            ["name" => "江西省", "short_name" => ["赣"], "license_plate_number" => "赣", "capital" => "南昌市"],
            ["name" => "湖北省", "short_name" => ["鄂"], "license_plate_number" => "鄂", "capital" => "武汉市"],
            ["name" => "甘肃省", "short_name" => ["甘", "陇"], "license_plate_number" => "甘，", "capital" => "兰州市"],
            ["name" => "山西省", "short_name" => ["晋"], "license_plate_number" => "晋", "capital" => "太原市"],
            ["name" => "陕西省", "short_name" => ["陕", "秦"], "license_plate_number" => "陕", "capital" => "西安市"],
            ["name" => "福建省", "short_name" => ["闽"], "license_plate_number" => "闽", "capital" => "福州市"],
            ["name" => "贵州省", "short_name" => ["贵", "黔"], "license_plate_number" => "贵", "capital" => "贵阳市"],
            ["name" => "广东省", "short_name" => ["粤"], "license_plate_number" => "粤", "capital" => "广州市"],
            ["name" => "青海省", "short_name" => ["青"], "license_plate_number" => "青", "capital" => "西宁市"],
            ["name" => "四川省", "short_name" => ["川", "蜀"], "license_plate_number" => "川", "capital" => "成都市"],
            ["name" => "海南省", "short_name" => ["琼"], "license_plate_number" => "琼", "capital" => "海口市"],

            ["name" => "北京市", "short_name" => ["京"], "license_plate_number" => "京", "capital" => "东城区"],
            ["name" => "天津市", "short_name" => ["津"], "license_plate_number" => "津", "capital" => "河西区"],
            ["name" => "上海市", "short_name" => ["沪"], "license_plate_number" => "沪", "capital" => "黄浦区"],
            ["name" => "重庆市", "short_name" => ["渝"], "license_plate_number" => "渝", "capital" => "渝中区"],

            ["name" => "内蒙古自治区", "short_name" => ["内蒙古"], "license_plate_number" => "蒙", "capital" => "呼和浩特市"],
            ["name" => "宁夏回族自治区", "short_name" => ["宁"], "license_plate_number" => "宁", "capital" => "银川市"],
            ["name" => "新疆维吾尔自治区", "short_name" => ["新"], "license_plate_number" => "新", "capital" => "乌鲁木齐市"],
            ["name" => "广西壮族自治区", "short_name" => ["桂"], "license_plate_number" => "桂", "capital" => "南宁市"],
            ["name" => "西藏自治区", "short_name" => ["藏"], "license_plate_number" => "藏", "capital" => "拉萨市"],
        ];

    public function getProvinces(): array
    {
        return $this->provinces;
    }

}