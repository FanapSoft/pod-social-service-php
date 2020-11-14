<?php
use PHPUnit\Framework\TestCase;
use Pod\Social\Service\SocialService;
use Pod\Base\Service\BaseInfo;
use Pod\Base\Service\Exception\ValidationException;
use Pod\Base\Service\Exception\PodException;

final class GetCustomPostListTest extends TestCase
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

	public function testGetCustomPostListAllParameters()
	{
		$params = [
			## ================= *Required Parameters  =================
			'businessId' => '',
			'size' => '',
			'offset' => '',
			## ================= Optional Parameters  =================
			'uniqueId' => '',
			'id' => '',
			'firstId' => '',
			'lastId' => '',
			'tags' => '',
			'tagTrees' => '',
			'tagTreeCategoryName' => '',
			'fromDate' => '',
			'toDate' => '',
			'activityInfo' => '',
			'scVoucherHash' => '',
			'scApiKey' => '',
			'token'     => '{Put Token}',
			//'scApiKey' => '{Put Service Call Api Key}',
			//'scVoucherHash' => '['{Put Service Call Voucher Hashes}', ...]',
		];
		try {
			$result = $SocialService->getCustomPostList($params);
			$this->assertFalse($result['error']);
			$this->assertEquals($result['code'], 200);
		} catch (ValidationException $e) {
			$this->fail('ValidationException: ' . $e->getErrorsAsString());
		} catch (PodException $e) {
			$error = $e->getResult();
			$this->fail('PodException: ' . $error['message']);
		}
	}

	public function testGetCustomPostListRequiredParameters()
	{
		$params = [
			## ================= *Required Parameters  =================
			'businessId' => '',
			'size' => '',
			'offset' => '',
		try {
			$result = $SocialService->getCustomPostList($params);
			$this->assertFalse($result['error']);
			$this->assertEquals($result['code'], 200);
		} catch (ValidationException $e) {
			$this->fail('ValidationException: ' . $e->getErrorsAsString());
		} catch (PodException $e) {
			$error = $e->getResult();
			$this->fail('PodException: ' . $error['message']);
		}
	}

	public function testGetCustomPostListValidationError()
	{
		$paramsWithoutRequired = [];
		$paramsWrongValue = [
			## ======================= *Required Parameters  ==========================
			'businessId' => '123',
			'offset' => '123',
			'size' => '123',
			## ======================== Optional Parameters  ==========================
			'uniqueId' => '123',
			'id' => '123',
			'firstId' => '123',
			'lastId' => '123',
			'tags' => '123',
			'tagTrees' => '123',
			'tagTreeCategoryName' => '123',
			'fromDate' => 123,
			'toDate' => 123,
			'activityInfo' => 123,
			'scVoucherHash' => '123',
			'scApiKey' => 123,
		];
		try {
			self::$SocialService->getCustomPostList($paramsWithoutRequired);
		} catch (ValidationException $e) {
			$validation = $e->getErrorsAsArray();
			$this->assertNotEmpty($validation);

			$result = $e->getResult();

			$this->assertArrayHasKey('businessId', $validation);
			$this->assertEquals('The property businessId is required', $validation['businessId'][0]);

			$this->assertArrayHasKey('offset', $validation);
			$this->assertEquals('The property offset is required', $validation['offset'][0]);

			$this->assertArrayHasKey('size', $validation);
			$this->assertEquals('The property size is required', $validation['size'][0]);


			$this->assertEquals(887, $result['code']);
		} catch (PodException $e) {
			$error = $e->getResult();
			$this->fail('PodException: ' . $error['message']);
		}
		try {
			self::$SocialService->getCustomPostList($paramsWrongValue);
		} catch (ValidationException $e) {

			$validation = $e->getErrorsAsArray();
			$this->assertNotEmpty($validation);

			$result = $e->getResult();
			$this->assertArrayHasKey('businessId', $validation);
			$this->assertEquals('String value found, but an integer is required', $validation['businessId'][1]);

			$this->assertArrayHasKey('uniqueId', $validation);
			$this->assertEquals('String value found, but an array is required', $validation['uniqueId'][0]);

			$this->assertArrayHasKey('id', $validation);
			$this->assertEquals('String value found, but an array is required', $validation['id'][0]);

			$this->assertArrayHasKey('firstId', $validation);
			$this->assertEquals('String value found, but an integer is required', $validation['firstId'][0]);

			$this->assertArrayHasKey('lastId', $validation);
			$this->assertEquals('String value found, but an integer is required', $validation['lastId'][0]);

			$this->assertArrayHasKey('offset', $validation);
			$this->assertEquals('String value found, but an integer is required', $validation['offset'][1]);

			$this->assertArrayHasKey('size', $validation);
			$this->assertEquals('String value found, but an integer is required', $validation['size'][1]);

			$this->assertArrayHasKey('tags', $validation);
			$this->assertEquals('String value found, but an array is required', $validation['tags'][0]);

			$this->assertArrayHasKey('tagTrees', $validation);
			$this->assertEquals('String value found, but an array is required', $validation['tagTrees'][0]);

			$this->assertArrayHasKey('tagTreeCategoryName', $validation);
			$this->assertEquals('String value found, but an array is required', $validation['tagTreeCategoryName'][0]);

			$this->assertArrayHasKey('fromDate', $validation);
			$this->assertEquals('Integer value found, but a string is required', $validation['fromDate'][0]);

			$this->assertArrayHasKey('toDate', $validation);
			$this->assertEquals('Integer value found, but a string is required', $validation['toDate'][0]);

			$this->assertArrayHasKey('activityInfo', $validation);
			$this->assertEquals('Integer value found, but a string is required', $validation['activityInfo'][0]);

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