<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class ValidationErrorCode extends Enum
{
    const Required = 100;
    const MaxLength255 = 101;
    const MaxLength100 = 102;
    const Unique = 103;
    const UniqueTwo = 104;
    const _Integer = 105;
    const Mimes = 106;
    const MaxFileSize1024 = 107;
    const MaxLength20 = 108;
    const MinLength20 = 109;
    const Email = 110;
    const _Date = 111;
}
