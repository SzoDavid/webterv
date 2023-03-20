<?php

namespace _Interfaces;

interface IComment
{
    public function getAuthor(): IUser;

    public function getContent(): string;

    public function getTime(): string;
}