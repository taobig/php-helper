<?php

namespace taobig\helpers\converter;


abstract class BaseConverter
{

    /**
     * @param array $inputArr
     * @param bool $validate
     * @return static
     * @throws ConverterException
     */
    public static function load(array $inputArr, bool $validate = true)
    {
        $className = get_called_class();
        /** @var static $obj */
        $obj = new $className;
        //get_object_vars
        //Returns an associative array of defined object accessible non-static properties for the specified object in scope.
        //不返回static属性；在类作用域中会返回私有属性，在类作用域外不返回私有属性
        $vars = get_object_vars($obj);
        foreach ($inputArr as $attributeName => $val) {
            $targetValue = $val;//先初始赋值，后面会根据规则做类型转换后覆盖初始值（如果有规则）
            if (array_key_exists($attributeName, $vars)) {
                //This function checks the existence of a property independent of accessibility.
//            if (property_exists($obj, $attributeName)) {
                $objectDefaultValue = $obj->{$attributeName};
                if (is_int($objectDefaultValue)) {
                    $targetValue = intval($val);
                } else if (is_bool($objectDefaultValue)) {
                    $targetValue = boolval($val);
                } else if (is_float($objectDefaultValue)) {
                    $targetValue = floatval($val);
                } else if (is_string($objectDefaultValue)) {
                    $targetValue = strval($val);
                } else if (is_array($objectDefaultValue)) {
                    if (!is_array($val)) {
                        throw new ConverterException("参数{$attributeName}的值必须是数组格式");
                    }
                    $typedArrayListObject = $obj->getTypedArrayListByAttributeName($attributeName);
                    if ($typedArrayListObject) {
                        $targetType = $typedArrayListObject->getTargetType();
                        $dimensional = $typedArrayListObject->getDimensional();
                        if (!class_exists($targetType)) {
                            throw new ConverterException("类{$targetType}不存在");
                        }
                        if (!is_subclass_of($targetType, BaseConverter::class, true)) {
                            throw new ConverterException(sprintf("配置错误！类{$targetType}没有继承%s类", self::class));
                        }
                        $targetValue = [];
                        if ($dimensional == 1) {
                            foreach ($val as $index => $item) {
                                $loadedValue = $targetType::load($item, $validate);
                                $targetValue[$index] = $loadedValue;
                            }
                        } else if ($dimensional == 2) {
                            foreach ($val as $index => $list) {
                                foreach ($list as $_index => $item) {
                                    $loadedValue = $targetType::load($item, $validate);
                                    $targetValue[$index][$_index] = $loadedValue;
                                }
                            }
                        } else {
                            throw new ConverterException("暂不支持3维素组解析");
                        }
                    }
                }
                $obj->{$attributeName} = $targetValue;
            }
        }

        if ($validate) {
            $obj->validate();
        }
        return $obj;
    }

    /**
     * @return void
     */
    abstract public function validate();

    protected function getTypedArrayListMapping(): array
    {
        return [
            //'images' => new TypedArrayList(Image::class),//Image is_subclass_of BaseConverter
        ];
    }

    /**
     * @param string $attributeName
     * @return TypedArrayList|null
     */
    private function getTypedArrayListByAttributeName(string $attributeName)
    {
        return $this->getTypedArrayListMapping()[$attributeName] ?? null;
    }

}