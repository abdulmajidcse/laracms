<?php

namespace App;

enum ContentType: string
{
    case TEXT = 'text';
    case BANNER = 'banner';
    case CARD = 'card';
    case HERO = 'hero';
}
