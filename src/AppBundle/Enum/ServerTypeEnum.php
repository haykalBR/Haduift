<?php


namespace AppBundle\Enum;

abstract class ServerTypeEnum
{
    const TYPE_Monitoring = 'Monitoring';
    const TYPE_Scanner = 'Scanner';
    const TYPE_Ids = 'IDS';


    /** @var array user friendly named type */
    protected static $typeName = [
        self::TYPE_Monitoring => 'Monitoring Server',
        self::TYPE_Scanner => 'Scanner Server',
        self::TYPE_Ids => 'IDS Server',
    ];

    /**
     * @param string $typeShortName
     *
     * @return string
     */
    public static function getTypeName($typeShortName)
    {
        if (!isset(static::$typeName[$typeShortName])) {
            return "Unknown type ($typeShortName)";
        }

        return static::$typeName[$typeShortName];
    }

    /**
     * @return array<string>
     */
    public static function getAvailableTypes()
    {
        return [
            self::TYPE_Monitoring,
            self::TYPE_Scanner,
            self::TYPE_Ids,

        ];
    }
}