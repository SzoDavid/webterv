<?php

namespace BL\_enums;

enum EPublicStatuses : int
{
    case Private = 0;
    case FriendsOnly = 1;
    case Public = 2;

    public function toString(): string
    {
        return match($this) {
            EPublicStatuses::Private => 'Private',
            EPublicStatuses::FriendsOnly => 'FriendsOnly',
            EPublicStatuses::Public => 'Public',
        };
    }
}

