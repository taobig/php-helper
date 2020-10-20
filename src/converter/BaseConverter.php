<?php

namespace taobig\helpers\converter;

use ReflectionProperty;
use taobig\helpers\ArrayHelper;

abstract class BaseConverter
{

    /**
     * @param array $inputArr
     * @param bool $validate
     * @return static
     * @throws ConverterException|\ReflectionException
     */
    public static function load(array $inputArr, bool $validate = true): static
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
                $reflectionProperty = new ReflectionProperty($className, $attributeName);
                $propertyType = $reflectionProperty->getType();
                if ($propertyType === null) {
                    throw new ConverterException("class {$className} property {$attributeName} has been declared without a type.");
                }


//                $propertyType instanceof ReflectionNamedType|ReflectionUnionType
                if ($propertyType instanceof \ReflectionNamedType) {
                    $typeName = $propertyType->getName();
                } else if ($propertyType instanceof \ReflectionUnionType) {
                    //$typeName = $propertyType->getTypes()[0]->getName();//使用union type时，只会读取第一个
                    throw new ConverterException("union type is not support");
                } else {
                    throw new ConverterException("unsupported class:" . get_class($propertyType));
                }
                $propertyAllowsNull = $propertyType->allowsNull();
                if (is_null($val)) {
                    if (!$propertyAllowsNull) {
                        throw new ConverterException("[$attributeName] can't be null");
                    }
                } else {
                    if (self::isScalarType($typeName)) {
                        $targetValue = self::getScalarTypeValue($typeName, $val);
                    } else {
                        switch ($typeName) {
                            case 'array':
                                if (!ArrayHelper::isIndexedArray($val)) {
                                    throw new ConverterException("参数{$attributeName}的值必须是数组格式");
                                }
                                $targetTypeName = '';
                                $dimensional = 1;
                                $reflectionAttributes = $reflectionProperty->getAttributes();
                                foreach ($reflectionAttributes as $reflectionAttribute) {
                                    $reflectionAttributeName = $reflectionAttribute->getName();
                                    if ($reflectionAttributeName == TypedArrayList::class) {
                                        $arguments = $reflectionAttribute->getArguments();
                                        $targetTypeName = $arguments[0];
                                        $dimensional = $arguments[1] ?? 1;
                                        break;
                                    }
                                }
                                if ($targetTypeName && $dimensional) {
                                    if (!class_exists($targetTypeName)) {
                                        throw new ConverterException("类{$targetTypeName}不存在");
                                    }
                                    $isScalarType = self::isScalarType($targetTypeName);
                                    if (!$isScalarType) {
                                        if (!is_subclass_of($targetTypeName, BaseConverter::class, true)) {
                                            throw new ConverterException(sprintf("配置错误！类{$targetTypeName}没有继承%s类", self::class));
                                        }
                                    }
                                    $targetValue = [];
                                    if ($dimensional == 1) {
                                        foreach ($val as $index => $item) {
                                            if ($isScalarType) {
                                                $loadedValue = self::getScalarTypeValue($targetTypeName, $item);
                                            } else {
                                                $loadedValue = $targetTypeName::load($item, $validate);
                                            }
                                            $targetValue[$index] = $loadedValue;
                                        }
                                    } else if ($dimensional == 2) {
                                        foreach ($val as $index => $list) {
                                            if (!ArrayHelper::isIndexedArray($list)) {
                                                throw new ConverterException("参数{$attributeName}的值必须是{$dimensional}维数组格式");
                                            }
                                            foreach ($list as $_index => $item) {
                                                if ($isScalarType) {
                                                    $loadedValue = self::getScalarTypeValue($targetTypeName, $item);
                                                } else {
                                                    $loadedValue = $targetTypeName::load($item, $validate);
                                                }
                                                $targetValue[$index][$_index] = $loadedValue;
                                            }
                                        }
                                    } else {
                                        throw new ConverterException("暂不支持3维素组解析");
                                    }
                                }
                                break;
                            default:
                                $targetValue = $val;
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
     * @param string $typeName
     * @return bool
     */
    private static function isScalarType(string $typeName): bool
    {
        switch ($typeName) {
            case 'int':
            case 'integer':
            case 'bool':
            case 'float':
            case 'string':
                return true;
        }
        return false;
    }

    /**
     * @param string $type
     * @param $val
     * @return bool|float|int|string
     * @throws ConverterException
     */
    private static function getScalarTypeValue(string $type, $val)
    {
        $targetValue = null;
        switch ($type) {
            case 'int':
            case 'integer':
                $targetValue = intval($val);
                break;
            case 'bool':
                $targetValue = boolval($val);
                break;
            case 'float':
                $targetValue = floatval($val);
                break;
            case 'string':
                $targetValue = strval($val);
                break;
            default:
                throw new ConverterException("source type:{$type} is a non scalar type");
        }
        return $targetValue;
    }

}