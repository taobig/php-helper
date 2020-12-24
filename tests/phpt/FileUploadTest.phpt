--TEST--
is_uploaded_file() function
--CREDITS--
Dave Kelsey <d_kelsey@uk.ibm.com>
--SKIPIF--
<?php if (php_sapi_name()=='cli') die('skip'); ?>
--POST_RAW--
Content-type: multipart/form-data, boundary=AaB03x

--AaB03x
content-disposition: form-data; name="field1"

Joe Blow
--AaB03x
content-disposition: form-data; name="pics"; filename="file1.txt"
Content-Type: text/plain

abcdef123456789
--AaB03x--
--FILE--
<?php

declare(strict_types=1);

namespace taobig\tests\helpers\phpt;

require_once __DIR__.'/../../vendor/autoload.php';
use taobig\helpers\utils\UploadedFile;

function testSaveUploadedFile(UploadedFile $uploadedFile)
{
    $dstDir = sys_get_temp_dir();
    $time = time();
    try {
        $targetFile = "{$dstDir}/{$time}/tmp_uploaded_file_{$time}_1";
        var_dump($uploadedFile->saveAs($targetFile, false));
        var_dump(file_exists($targetFile));
    } finally {
        unlink($targetFile);
    }
    try {
        $targetFile = "{$dstDir}/{$time}/tmp_uploaded_file_{$time}_2";
        var_dump($uploadedFile->saveAs($targetFile, false));
        var_dump(file_exists($targetFile));
    } finally {
        unlink($targetFile);
    }

    try {
        $targetFile = "{$dstDir}/{$time}/tmp_uploaded_file_{$time}_3";
        var_dump($uploadedFile->saveAs($targetFile));
        var_dump(file_exists($targetFile));
        var_dump(file_exists($uploadedFile->tempName));
    } finally {
        unlink($targetFile);
    }
}
$uploadedFile = UploadedFile::getInstanceByName('pics');
testSaveUploadedFile($uploadedFile);

?>
--EXPECTF--
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(false)

