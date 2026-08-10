<?php

declare(strict_types=1);

namespace Mailjet\LaravelMailjet\Tests\Model;

use RuntimeException;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use Mailjet\LaravelMailjet\Model\ContactMetadata;

class ContactMetadataTest extends TestCase
{
    #[DataProvider('validDatatypes')]
    public function testFormatContainsNameAndDatatype(string $datatype): void
    {
        $metadata = new ContactMetadata('age', $datatype);

        $this->assertSame([
            'Name' => 'age',
            'Datatype' => $datatype,
        ], $metadata->format());
    }

    public function testConstructorRejectsInvalidDatatype(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('array: is not a valid Datatype.');

        new ContactMetadata('age', 'array');
    }

    public static function validDatatypes(): array
    {
        return [
            'str' => [ContactMetadata::DATATYPE_STR],
            'int' => [ContactMetadata::DATATYPE_INT],
            'float' => [ContactMetadata::DATATYPE_FLOAT],
            'bool' => [ContactMetadata::DATATYPE_BOOL],
            'datetime' => [ContactMetadata::DATATYPE_DATETIME],
        ];
    }
}
