<?php

namespace taobig\helpers\exception\io;

use taobig\helpers\base\BaseException;

class IOException extends BaseException
{

    private ?string $path = null;

    public function __construct(string $path = null, string $message = '', int $code = 0, \Throwable $previous = null)
    {
        $this->path = $path;

        parent::__construct($message, $code, $previous);
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

}