<?php

namespace app\components\upload;

interface UploadInterface
{

    public function upload(string $localFile, string $targetFileName, bool $skipSameNameFile = false): string;

    public function buildUrl(string $targetFileName): string;

}