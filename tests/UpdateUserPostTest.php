<?php
use PHPUnit\Framework\TestCase;
use Pod\Social\Service\SocialService;
use Pod\Base\Service\BaseInfo;
use Pod\Base\Service\Exception\ValidationException;
use Pod\Base\Service\Exception\PodException;

final class UpdateUserPostTest extends TestCase
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

	public function testUpdateUserPostAllParameters()
	{
		$params = [
			## ================= *Required Parameters  =================
			'entityId' => '',
			'content' => '',
			'name' => '',
			'canComment' => '',
			'canLike' => '',
			'enable' => '',
			## ================= Optional Parameters  =================
			'repliedPostId' => '',
			'metadata' => '',
			'lat' => '',
			'lng' => '',
			'tags' => '',
			'tagTrees' => '',
			'tagTreeCategoryName' => '',
			'scVoucherHash' => '',
			'scApiKey' => '',
			'token'     => '{Put Token}',
			//'scApiKey' => '{Put Service Call Api Key}',
			//'scVoucherHash' => '['{Put Service Call Voucher Hashes}', ...]',
		];
		try {
			$result = $SocialService->updateUserPost($params);
			$this->assertFalse($result['error']);
			$this->assertEquals($result['code'], 200);
		} catch (ValidationException $e) {
			$this->fail('ValidationException: ' . $e->getErrorsAsString());
		} catch (PodException $e) {
			$error = $e->getResult();
			$this->fail('PodException: ' . $error['message']);
		}
	}

	public function testUpdateUserPostRequiredParameters()
	{
		$params = [
			## ================= *Required Parameters  =================
			'entityId' => '',
			'content' => '',
			'name' => '',
			'canComment' => '',
			'canLike' => '',
			'enable' => '',
		try {
			$result = $SocialService->updateUserPost($params);
			$this->assertFalse($result['error']);
			$this->assertEquals($result['code'], 200);
		} catch (ValidationException $e) {
			$this->fail('ValidationException: ' . $e->getErrorsAsString());
		} catch (PodException $e) {
			$error = $e->getResult();
			$this->fail('PodException: ' . $error['message']);
		}
	}

	public function testUpdateUserPostValidationError()
	{
		$paramsWithoutRequired = [];
		$paramsWrongValue = [
			## ======================= *Required Parameters  ==========================
			'entityId' => '123',
			'name' => 123,
			'content' => 123,
			'canComment' => 123,
			'canLike' => 123,
			'enable' => 123,
			## ======================== Optional Parameters  ==========================
			'repliedPostId' => '123',
			'metadata' => 123,
			'lat' => 123,
			'lng' => 123,
			'tags' => '123',
			'tagTrees' => '123',
			'tagTreeCategoryName' => '123',
			'scVoucherHash' => '123',
			'scApiKey' => 123,
		];
		try {
			self::$SocialService->updateUserPost($paramsWithoutRequired);
		} catch (ValidationException $e) {
			$validation = $e->getErrorsAsArray();
			$this->assertNotEmpty($validation);

			$result = $e->getResult();

			$this->assertArrayHasKey('entityId', $validation);
			$this->assertEquals('The property entityId is required', $validation['entityId'][0]);

			$this->assertArrayHasKey('name', $validation);
			$this->assertEquals('The property name is required', $validation['name'][0]);

			$this->assertArrayHasKey('content', $validation);
			$this->assertEquals('The property content is required', $validation['content'][0]);

			$this->assertArrayHasKey('canComment', $validation);
			$this->assertEquals('The property canComment is required', $validation['canComment'][0]);

			$this->assertArrayHasKey('canLike', $validation);
			$this->assertEquals('The property canLike is required', $validation['canLike'][0]);

			$this->assertArrayHasKey('enable', $validation);
			$this->assertEquals('The property enable is required', $validation['enable'][0]);


			$this->assertEquals(887, $result['code']);
		} catch (PodException $e) {
			$error = $e->getResult();
			$this->fail('PodException: ' . $error['message']);
		}
		try {
			self::$SocialService->updateUserPost($paramsWrongValue);
		} catch (ValidationException $e) {

			$validation = $e->getErrorsAsArray();
			$this->assertNotEmpty($validation);

			$result = $e->getResult();
			$this->assertArrayHasKey('entityId', $validation);
			$this->assertEquals('String value found, but an integer is required', $validation['entityId'][1]);

			$this->assertArrayHasKey('name', $validation);
			$this->assertEquals('Integer value found, but a string is required', $validation['name'][1]);

			$this->assertArrayHasKey('repliedPostId', $validation);
			$this->assertEquals('String value found, but an integer is required', $validation['repliedPostId'][0]);

			$this->assertArrayHasKey('content', $validation);
			$this->assertEquals('Integer value found, but a string is required', $validation['content'][1]);

			$this->assertArrayHasKey('canComment', $validation);
			$this->assertEquals('Integer value found, but a string is required', $validation['canComment'][1]);

			$this->assertArrayHasKey('canLike', $validation);
			$this->assertEquals('Integer value found, but a string is required', $validation['canLike'][1]);

			$this->assertArrayHasKey('enable', $validation);
			$this->assertEquals('Integer value found, but a string is required', $validation['enable'][1]);

			$this->assertArrayHasKey('metadata', $validation);
			$this->assertEquals('Integer value found, but a string is required', $validation['metadata'][0]);

			$this->assertArrayHasKey('lat', $validation);
			$this->assertEquals('Integer value found, but a string is required', $validation['lat'][0]);

			$this->assertArrayHasKey('lng', $validation);
			$this->assertEquals('Integer value found, but a string is required', $validation['lng'][0]);

			$this->assertArrayHasKey('tags', $validation);
			$this->assertEquals('String value found, but an array is required', $validation['tags'][0]);

			$this->assertArrayHasKey('tagTrees', $validation);
			$this->assertEquals('String value found, but an array is required', $validation['tagTrees'][0]);

			$this->assertArrayHasKey('tagTreeCategoryName', $validation);
			$this->assertEquals('String value found, but an array is required', $validation['tagTreeCategoryName'][0]);

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