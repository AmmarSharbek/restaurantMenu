<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class ErrorCode extends Enum
{
    const ValidationError = 0;
    const CredentialsError = 1;
    const WorngPassword = 2;
    const PermissionsError = 3;
    const TokenInvalidException =   20;
    const TokenExpiredException =   21;
}
