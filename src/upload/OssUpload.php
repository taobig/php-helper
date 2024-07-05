<?php

namespace taobig\helpers\upload;

use OSS\Core\OssException;
use OSS\Model\LifecycleAction;
use OSS\Model\LifecycleConfig;
use OSS\Model\LifecycleRule;
use OSS\OssClient;
use taobig\helpers\upload\exceptions\FileExistsException;
use taobig\helpers\upload\exceptions\UploadException;


/**
 * @codeCoverageIgnore
 */
class OssUpload implements UploadInterface
{

    private OssClient $ossClient;
    private string $bindingDomain;
    private string $bucketName;

    public function __construct(OssParams $ossParams)
    {
        $this->bindingDomain = rtrim($ossParams->bindingDomain, '/') . '/';
        $this->bucketName = $ossParams->bucketName;

        $this->ossClient = new OssClient($ossParams->accessKeyId, $ossParams->accessKeySecret, $ossParams->endpointServer);
    }

    /**
     * @param string $localFile
     * @param string $targetFileName
     * @param bool $skipSameNameFile
     * @return string
     * @throws FileExistsException
     * @throws \Throwable
     * @deprecated
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

        //说明 OSS支持HTTP协议规定的5个请求头：Cache-Control、Expires、Content-Encoding、Content-Disposition、Content-Type。如果上传Object时设置了这些请求头，则该Object被下载时，相应的请求头值会被自动设置成上传时的值。
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

    /**
     * @see https://help.aliyun.com/document_detail/32108.html
     * @param string $ruleId 设置规则ID。
     * @param string $matchPrefix 设置文件前缀。 eg: "A1/"
     * @throws OssException
     */
    public function setLifecycle(string $ruleId, string $matchPrefix)
    {
        $lifecycleConfig = new LifecycleConfig();
        $actions = [];
        // 距最后修改时间3天后过期。
        $actions[] = new LifecycleAction(OssClient::OSS_LIFECYCLE_EXPIRATION, OssClient::OSS_LIFECYCLE_TIMING_DAYS, 3);
        $lifecycleRule = new LifecycleRule($ruleId, $matchPrefix, LifecycleRule::LIFECYCLE_STATUS_ENABLED, $actions);
        $lifecycleConfig->addRule($lifecycleRule);
        $this->ossClient->putBucketLifecycle($this->bucketName, $lifecycleConfig);
    }

    public function getFileInfo(string $fileName): ?array
    {
        $objectName = ltrim($fileName, '/');
        return $this->ossClient->getObjectMeta($this->bucketName, $objectName);
    }

    public function isFileNameExists(string $targetFileName): ?bool
    {
        return $this->ossClient->doesObjectExist($this->bucketName, $targetFileName);
    }

    public function buildUrl(string $targetFileName): string
    {
        return $this->bindingDomain . ltrim($targetFileName, '/');
    }

}