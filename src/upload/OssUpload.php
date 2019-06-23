<?php

namespace taobig\helpers\upload;

use OSS\Core\OssException;
use OSS\OssClient;
use taobig\helpers\upload\exceptions\FileExistsException;
use taobig\helpers\upload\exceptions\UploadException;
use taobig\helpers\upload\OssParams;

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
     * @param string $localFile
     * @param string $targetFileName
     * @param bool $skipSameNameFile
     * @return string
     * @throws FileExistsException
     * @throws \Throwable
     */
    public function upload(string $localFile, string $targetFileName, bool $skipSameNameFile = false): string
    {
        $isExist = $this->ossClient->doesObjectExist($this->bucketName, $targetFileName);
        if ($isExist && !$skipSameNameFile) {
            throw new FileExistsException('文件已经存在，请改名后重试');
        }

        $upload_file_options = [
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
            $this->ossClient->uploadFile($this->bucketName, $targetFileName, $localFile, $upload_file_options);
        } catch (OssException $e) {
            throw new UploadException('上传文件失败' . $e->getMessage());
        } catch (\Throwable $e) {
            throw $e;
        }
        return $this->buildUrl($targetFileName);
    }

    public function buildUrl(string $targetFileName): string
    {
        return $this->bindingDomain . ltrim($targetFileName, '/');
    }

}