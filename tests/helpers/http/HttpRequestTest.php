<?php

use taobig\helpers\http\HttpRequest;
use taobig\helpers\utils\UploadedFile;

class HttpRequestTest extends TestCase
{

    public function testGet()
    {
        $content = (new HttpRequest())->get("localhost:8080", 10);
        $this->assertSame("Hello world!", $content);
    }

    public function testPostJson()
    {
        $time = time();
        $params = ['time' => $time];
        $key = 'Customer-Header';
        $value = 'hello';
        $headers = [$key => $value];
        $content = (new HttpRequest())->postJson("localhost:8080/json", $params, 10, $headers);
        $this->assertSame("string", gettype($content));
        $obj = json_decode($content);
        $this->assertSame("object", gettype($obj));
        $this->assertSame($time, $obj->time);

        $this->assertSame($value, $obj->headers->{$key});
    }

    public function testPostForm()
    {
        $time = time();
        $params = ['time' => $time];
        $content = (new HttpRequest())->postForm("localhost:8080/form", $params, 10);
        $this->assertSame("string", gettype($content));
        $this->assertSame("time={$time}", $content);
    }

    public function testPostFile()
    {
        $time = time();
        $params = [
            //必须有name、filename、contents三个参数才能被填充到$_FILES
            [
                'name' => 'file1',//$_FILES['file1']
                'contents' => file_get_contents(__FILE__),
                'filename' => '1.php',
            ],
            [
                'name' => 'files[]',//$_FILES['files']
                'contents' => file_get_contents(__FILE__),
                'filename' => '2_0.php',
            ],
            [
                'name' => 'files[]',
                'contents' => file_get_contents(__FILE__),
                'filename' => '2_1.php',
            ],
            [
                'name' => 'file_size',//$_POST['file_size'] => filesize(__FILE__)
                'contents' => filesize(__FILE__),
            ],
            [
                'name' => 'time',//$_POST['time'] => $time
                'contents' => $time,
            ],
        ];
        $content = (new HttpRequest())->postFile("localhost:8080/file", $params, 10);
        $this->assertSame("string", gettype($content));
        $json = json_decode($content, true);
        $this->assertSame($time, intval($json['post']['time']));
        $this->assertSame($json['files']['file1']['size'], intval($json['post']['file_size']));
        return $json['files'];
    }

    /**
     * @depends testPostFile
     * @param array $files
     * @return UploadedFile|null
     */
    public function testUploadedFile(array $files)
    {
        $_FILES = $files;

        $uploadedFiles = UploadedFile::getInstancesByName('files');
        foreach ($uploadedFiles as $index => $uploadedFile) {
            $this->assertSame("2_{$index}", $uploadedFile->getBaseName());
            $this->assertSame('php', $uploadedFile->getExtension());
            $this->assertSame(false, $uploadedFile->getHasError());
        }
        unset($uploadedFiles);
        unset($uploadedFile);
        UploadedFile::reset();

        $uploadedFiles = UploadedFile::getInstancesByName('file1');
        $this->assertSame("1", $uploadedFiles[0]->getBaseName());
        $this->assertSame('php', $uploadedFiles[0]->getExtension());
        $this->assertSame(false, $uploadedFiles[0]->getHasError());
        unset($uploadedFiles);
        UploadedFile::reset();

        $uploadedFiles = UploadedFile::getInstances();
        $this->assertSame(3, count($uploadedFiles));
        unset($uploadedFiles);
        UploadedFile::reset();

        $uploadedFile = UploadedFile::getInstanceByName('file1');
        $this->assertInstanceOf(UploadedFile::class, $uploadedFile);
        $this->assertSame('1', $uploadedFile->getBaseName());
        $this->assertSame('php', $uploadedFile->getExtension());
        $this->assertSame(false, $uploadedFile->getHasError());
        return $uploadedFile;
    }

    /**
     * @depends testUploadedFile
     * @param UploadedFile $uploadedFile
     */
    public function testSaveUploadedFile(UploadedFile $uploadedFile)
    {
        $dstDir = __DIR__;
        $time = time();
        echo "{$dstDir}/{$time}/tmp_uploaded_file_{$time}_1";
        try {
            $targetFile = "{$dstDir}/{$time}/tmp_uploaded_file_{$time}_1";
            $this->assertSame(true, $uploadedFile->saveAs($targetFile, false));
            $this->assertSame(true, file_exists($targetFile));
        } finally {
            unlink($targetFile);
        }
        try {
            $targetFile = "{$dstDir}/{$time}/tmp_uploaded_file_{$time}_2";
            $this->assertSame(true, $uploadedFile->saveAs($targetFile, false));
            $this->assertSame(true, file_exists($targetFile));
        } finally {
            unlink($targetFile);
        }
        //TODO: is_uploaded_file检测不能通过
//        try {
//            $targetFile = "{$dstDir}/{$time}/tmp_uploaded_file_{$time}_3";
//            $this->assertSame(true, $uploadedFile->saveAs($targetFile));
//            $this->assertSame(false, file_exists($targetFile));
//        } finally {
//            unlink($targetFile);
//        }
        unset($uploadedFile);
        UploadedFile::reset();
    }

}
