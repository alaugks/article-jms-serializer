<?php declare(strict_types=1);

namespace App\Handler;

use JMS\Serializer\GraphNavigatorInterface;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Visitor\DeserializationVisitorInterface;
use JMS\Serializer\Visitor\SerializationVisitorInterface;

class MappingTableHandler implements SubscribingHandlerInterface
{
    public const HANDLER_TYPE = 'MappingTable';

    public static function getSubscribingMethods(): array
    {
        $methods = [];

        foreach (['json', 'xml'] as $format) {
            $methods[] = [
                'type' => self::HANDLER_TYPE,
                'format' => $format,
                'direction' => GraphNavigatorInterface::DIRECTION_SERIALIZATION,
                'method' => 'serialize',
            ];

            $methods[] = [
                'type' => self::HANDLER_TYPE,
                'direction' => GraphNavigatorInterface::DIRECTION_DESERIALIZATION,
                'format' => $format,
                'method' => 'deserialize',
            ];
        }

        return $methods;
    }

    public function serialize(
        SerializationVisitorInterface $visitor,
        string|bool|null $value,
        array $type,
        SerializationContext $context
    ): ?string
    {
        if (null === $value || '' === $value) {
            return ''; // Reset value in CRM
        }

        $key = array_search($value, $this->getMappingTable($type));
        return $key ? (string)$key : null;
    }

    public function deserialize(
        DeserializationVisitorInterface $visitor,
        $value,
        array $type
    ): mixed
    {
        $mappingTable = $this->getMappingTable($type);
        return $mappingTable[$value] ?? null;
    }

    private function getMappingTable(array $type): array
    {
        $mappingTable = [];

        if (!isset($type['params'][0])) {
            throw new \InvalidArgumentException('mapping_table param not defined');
        }

        // Example: #[Type("MappingTable<['1', 'MALE', '2', 'FEMALE', '6', 'DIVERS']>")]
        if (is_array($type['params'][0])) {
            for ($i = 0; $i < count($type['params'][0]); $i += 2) {
                $mappingTable[$type['params'][0][$i]] = $type['params'][0][$i + 1];
            }
        }

        // Example: #[Type("MappingTable<'{\"1\": \"MALE\", \"2\": \"FEMALE\", \"6\": \"DIVERS\"}'>")]
        else if ($array = json_decode($type['params'][0], true)) {
            $mappingTable = $array;
        }

        // Example: #[Type("MappingTable<'App\Dto\ContactDto::SALUTATION'>")]
        elseif (defined($type['params'][0])) {
            $mappingTable = constant($type['params'][0]);
        }

        return $mappingTable;
    }
}
