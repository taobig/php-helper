<?php

namespace taobig\helpers\upload;

/**
 * @codeCoverageIgnore
 */
interface UploadInterface
{

    /**
     * @deprecated
     * @param string $localFile
     * @param string $targetFileName
     * @param bool $skipSameNameFile
     * @return string
     */
    public function upload(string $localFile, string $targetFileName, bool $skipSameNameFile = false): string;

    public function uploadFile(string $localFile, string $targetFileName, bool $skipSameNameFile = true): string;

    public function moveFile(string $sourceFileName, string $targetFileName, bool $skipSameNameFile = true): string;

    public function copyFile(string $sourceFileName, string $targetFileName, bool $skipSameNameFile = true): string;

    public function getFileInfo(string $fileName): array;

    public function isFileNameExists(string $targetFileName): bool;

    public function buildUrl(string $targetFileName): string;

}