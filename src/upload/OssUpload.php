<?php

namespace taobig\helpers\upload;

use OSS\Core\OssException;
use OSS\OssClient;
use taobig\helpers\upload\exceptions\FileExistsException;
use taobig\helpers\upload\exceptions\UploadException;


/**
 * @codeCoverageIgnore
 */
class OssUpload implements UploadInterface
{

    /** @var OssClient */
    private $ossClient;
    /** @var string */
    private $bindingDomain;
    /** @var string */
    private $bucketName;

    public function __construct(OssParams $ossParams)
    {
        $this->bindingDomain = rtrim($ossParams->bindingDomain, '/') . '/';
        $this->bucketName = $ossParams->bucketName;

        $this->ossClient = new OssClient($ossParams->accessKeyId, $ossParams->accessKeySecret, $ossParams->endpointServer);
    }

    /**
     * @deprecated
     * @param string $localFile
     * @param string $targetFileName
     * @param bool $skipSameNameFile
     * @return string
     * @throws FileExistsException
     * @throws \Throwable
     */
    public function upload(string $localFile, string $targetFileName, bool $skipSameNameFile = false): string
    {
        if (!$skipSameNameFile) {
            $isExist = $this->isFileNameExists($targetFileName);
            if ($isExist) {
                throw new FileExistsException('文件已经存在，请改名后重试');
            }
        }

        $uploadFileOptions = [
            OssClient::OSS_HEADERS => [
//                'Expires' => 'Fri, 28 Feb 2012 05:38:42 GMT',
                'Expires' => (new \DateTime(("+30 Days")))->format(\DateTime::COOKIE),//Tuesday, 24-Nov-2015 17:43:37 CST
                'Cache-Control' => 'no-cache',
//                 'Content-Disposition' => 'attachment;filename=oss_download.jpg',//不用浏览器下载，不用设置
                'Content-Encoding' => 'utf-8',
                'Content-Language' => 'zh-CN',
                'x-oss-server-side-encryption' => 'AES256',
            ],
        ];
        try {
            $this->ossClient->uploadFile($this->bucketName, $targetFileName, $localFile, $uploadFileOptions);
        } catch (OssException $e) {
            throw new UploadException('上传文件失败' . $e->getMessage());
        } catch (\Throwable $e) {
            throw $e;
        }
        return $this->buildUrl($targetFileName);
    }

    /**
     * @param string $localFile
     * @param string $targetFileName
     * @param bool $skipSameNameFile
     * @return string
     * @throws \Throwable
     */
    public function uploadFile(string $localFile, string $targetFileName, bool $skipSameNameFile = true): string
    {
        if ($skipSameNameFile) {
            $isExist = $this->isFileNameExists($targetFileName);
            if ($isExist) {
                return $this->buildUrl($targetFileName);
            }
        }

        $uploadFileOptions = [
            OssClient::OSS_HEADERS => [
//                'Expires' => 'Fri, 28 Feb 2012 05:38:42 GMT',
                'Expires' => (new \DateTime(("+30 Days")))->format(\DateTime::COOKIE),//Tuesday, 24-Nov-2015 17:43:37 CST
                'Cache-Control' => 'no-cache',
//                 'Content-Disposition' => 'attachment;filename=oss_download.jpg',//不用浏览器下载，不用设置
                'Content-Encoding' => 'utf-8',
                'Content-Language' => 'zh-CN',
                'x-oss-server-side-encryption' => 'AES256',
            ],
        ];
        try {
            $this->ossClient->uploadFile($this->bucketName, $targetFileName, $localFile, $uploadFileOptions);
        } catch (OssException $e) {
            throw new UploadException('上传文件失败' . $e->getMessage());
        } catch (\Throwable $e) {
            throw $e;
        }
        return $this->buildUrl($targetFileName);
    }

    public function moveFile(string $sourceFileName, string $targetFileName, bool $skipSameNameFile = true): string
    {
        if ($skipSameNameFile) {
            $isExist = $this->isFileNameExists($targetFileName);
            if ($isExist) {
                return $this->buildUrl($targetFileName);
            }
        }
        try {
            $this->ossClient->copyObject($this->bucketName, $sourceFileName, $this->bucketName, $targetFileName);
            $this->ossClient->deleteObject($this->bucketName, $sourceFileName);
        } catch (OssException $e) {
            throw new UploadException('移动文件失败' . $e->getMessage());
        } catch (\Throwable $e) {
            throw $e;
        }
        return $this->buildUrl($targetFileName);
    }

    public function copyFile(string $sourceFileName, string $targetFileName, bool $skipSameNameFile = true): string
    {
        if ($skipSameNameFile) {
            $isExist = $this->isFileNameExists($targetFileName);
            if ($isExist) {
                return $this->buildUrl($targetFileName);
            }
        }
        try {
            $this->ossClient->copyObject($this->bucketName, $sourceFileName, $this->bucketName, $targetFileName);
        } catch (OssException $e) {
            throw new UploadException('移动文件失败' . $e->getMessage());
        } catch (\Throwable $e) {
            throw $e;
        }
        return $this->buildUrl($targetFileName);
    }

    public function isFileNameExists(string $targetFileName): bool
    {
        return $this->ossClient->doesObjectExist($this->bucketName, $targetFileName);
    }

    public function buildUrl(string $targetFileName): string
    {
        return $this->bindingDomain . ltrim($targetFileName, '/');
    }

}