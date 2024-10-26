<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class SocialMedia extends Enum
{
    const facebook = 0;
    const instagram = 1;
    const whatsapp = 2;
    const telegram = 3;
    const snapchat = 4;
    const twitter = 5;
    const location = 6;
}
