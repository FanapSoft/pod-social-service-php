<?php
use PHPUnit\Framework\TestCase;
use Pod\Social\Service\SocialService;
use Pod\Base\Service\BaseInfo;
use Pod\Base\Service\Exception\ValidationException;
use Pod\Base\Service\Exception\PodException;

final class SearchOnUserPostByMetadataTest extends TestCase
{
    public static $SocialService;
    private $token;
    public function setUp(): void
   {
        parent::setUp();
        # set serverType to SandBox or Production
        BaseInfo::initServerType(BaseInfo::SANDBOX_SERVER);
        $testData =  require __DIR__ . '/testData.php';
        $this->token = $testData['token'];

        $baseInfo = new BaseInfo();
        $baseInfo->setToken($this->token);
		self::$SocialService = new SocialService($baseInfo);
    }

	public function testSearchOnUserPostByMetadataAllParameters()
	{
		$params = [
			## ================= *Required Parameters  =================
			'userId' => '',
			'metaQuery' => '',
			## ================= Optional Parameters  =================
			'offset' => '',
			'size' => '',
			'orderBy' => '',
			'postIds' => '',
			'scVoucherHash' => '',
			'scApiKey' => '',
			'token'     => '{Put Token}',
			//'scApiKey' => '{Put Service Call Api Key}',
			//'scVoucherHash' => '['{Put Service Call Voucher Hashes}', ...]',
		];
		try {
			$result = $SocialService->searchOnUserPostByMetadata($params);
			$this->assertFalse($result['error']);
			$this->assertEquals($result['code'], 200);
		} catch (ValidationException $e) {
			$this->fail('ValidationException: ' . $e->getErrorsAsString());
		} catch (PodException $e) {
			$error = $e->getResult();
			$this->fail('PodException: ' . $error['message']);
		}
	}

	public function testSearchOnUserPostByMetadataRequiredParameters()
	{
		$params = [
			## ================= *Required Parameters  =================
			'userId' => '',
			'metaQuery' => '',
		try {
			$result = $SocialService->searchOnUserPostByMetadata($params);
			$this->assertFalse($result['error']);
			$this->assertEquals($result['code'], 200);
		} catch (ValidationException $e) {
			$this->fail('ValidationException: ' . $e->getErrorsAsString());
		} catch (PodException $e) {
			$error = $e->getResult();
			$this->fail('PodException: ' . $error['message']);
		}
	}

	public function testSearchOnUserPostByMetadataValidationError()
	{
		$paramsWithoutRequired = [];
		$paramsWrongValue = [
			## ======================= *Required Parameters  ==========================
			'metaQuery' => 123,
			'userId' => '123',
			## ======================== Optional Parameters  ==========================
			'offset' => '123',
			'size' => '123',
			'orderBy' => '123',
			'postIds' => '123',
			'scVoucherHash' => '123',
			'scApiKey' => 123,
		];
		try {
			self::$SocialService->searchOnUserPostByMetadata($paramsWithoutRequired);
		} catch (ValidationException $e) {
			$validation = $e->getErrorsAsArray();
			$this->assertNotEmpty($validation);

			$result = $e->getResult();

			$this->assertArrayHasKey('metaQuery', $validation);
			$this->assertEquals('The property metaQuery is required', $validation['metaQuery'][0]);

			$this->assertArrayHasKey('userId', $validation);
			$this->assertEquals('The property userId is required', $validation['userId'][0]);


			$this->assertEquals(887, $result['code']);
		} catch (PodException $e) {
			$error = $e->getResult();
			$this->fail('PodException: ' . $error['message']);
		}
		try {
			self::$SocialService->searchOnUserPostByMetadata($paramsWrongValue);
		} catch (ValidationException $e) {

			$validation = $e->getErrorsAsArray();
			$this->assertNotEmpty($validation);

			$result = $e->getResult();
			$this->assertArrayHasKey('metaQuery', $validation);
			$this->assertEquals('Integer value found, but a string is required', $validation['metaQuery'][1]);

			$this->assertArrayHasKey('userId', $validation);
			$this->assertEquals('String value found, but an integer is required', $validation['userId'][1]);

			$this->assertArrayHasKey('offset', $validation);
			$this->assertEquals('String value found, but an integer is required', $validation['offset'][0]);

			$this->assertArrayHasKey('size', $validation);
			$this->assertEquals('String value found, but an integer is required', $validation['size'][0]);

			$this->assertArrayHasKey('orderBy', $validation);
			$this->assertEquals('String value found, but an array is required', $validation['orderBy'][0]);

			$this->assertArrayHasKey('postIds', $validation);
			$this->assertEquals('String value found, but an array is required', $validation['postIds'][0]);

			$this->assertArrayHasKey('scVoucherHash', $validation);
			$this->assertEquals('String value found, but an array is required', $validation['scVoucherHash'][0]);

			$this->assertArrayHasKey('scApiKey', $validation);
			$this->assertEquals('Integer value found, but a string is required', $validation['scApiKey'][0]);

			$this->assertEquals(887, $result['code']);
		} catch (PodException $e) {
			$error = $e->getResult();
			$this->fail('PodException: ' . $error['message']);
		}
	}

}