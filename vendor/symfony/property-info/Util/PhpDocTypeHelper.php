<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\PropertyInfo\Util;

use phpDocumentor\Reflection\Type as DocType;
use phpDocumentor\Reflection\Types\Compound;
use phpDocumentor\Reflection\Types\Null_;
use phpDocumentor\Reflection\Types\Nullable;
use Symfony\Component\PropertyInfo\Type;

/**
 * Transforms a php doc type to a {@link Type} instance.
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 * @author Guilhem N. <egetick@gmail.com>
 */
final class PhpDocTypeHelper
{
    /**
     * Creates a {@see Type} from a PHPDoc type.
     *
     * @return Type[]
     */
    public function getTypes(DocType $varType): array
    {
        $types = array();
        $nullable = false;

        if ($varType instanceof Nullable) {
            $nullable = true;
            $varType = $varType->getActualType();
        }

        if (!$varType instanceof Compound) {
            if ($varType instanceof Null_) {
                $nullable = true;
            }

            $type = $this->createType((string) $varType, $nullable);
            if (null !== $type) {
                $types[] = $type;
            }

            return $types;
        }

        $varTypes = array();
        for ($typeIndex = 0; $varType->has($typeIndex); ++$typeIndex) {
            $varTypes[] = (string) $varType->get($typeIndex);
        }

        // If null is present, all types are nullable
        $nullKey = array_search(Type::BUILTIN_TYPE_NULL, $varTypes);
        $nullable = $nullable || false !== $nullKey;

        // Remove the null type from the type if other types are defined
        if ($nullable && false !== $nullKey && \count($varTypes) > 1) {
            unset($varTypes[$nullKey]);
        }

        foreach ($varTypes as $varType) {
            $type = $this->createType($varType, $nullable);
            if (null !== $type) {
                $types[] = $type;
            }
        }

        return $types;
    }

    /**
     * Creates a {@see Type} from a PHPDoc type.
     */
    private function createType(string $docType, bool $nullable): ?Type
    {
        // Cannot guess
        if (!$docType || 'mixed' === $docType) {
            return null;
        }

        if ($collection = '[]' === substr($docType, -2)) {
            $docType = substr($docType, 0, -2);
        }

        $docType = $this->normalizeType($docType);
        list($phpType, $class) = $this->getPhpTypeAndClass($docType);

        $array = 'array' === $docType;

        if ($collection || $array) {
            if ($array || 'mixed' === $docType) {
                $collectionKeyType = null;
                $collectionValueType = null;
            } else {
                $collectionKeyType = new Type(Type::BUILTIN_TYPE_INT);
                $collectionValueType = new Type($phpType, $nullable, $class);
            }

            return new Type(Type::BUILTIN_TYPE_ARRAY, $nullable, null, true, $collectionKeyType, $collectionValueType);
        }

        return new Type($phpType, $nullable, $class);
    }

    private function normalizeType(string $docType): string
    {
        switch ($docType) {
            case 'integer':
                return 'int';

            case 'boolean':
                return 'bool';

            // real is not part of the PHPDoc standard, so we ignore it
            case 'double':
                return 'float';

            case 'callback':
                return 'callable';

            case 'void':
                return 'null';

            default:
                return $docType;
        }
    }

    private function getPhpTypeAndClass(string $docType): array
    {
        if (\in_array($docType, Type::$builtinTypes)) {
            return array($docType, null);
        }

        return array('object', substr($docType, 1));
    }
}
