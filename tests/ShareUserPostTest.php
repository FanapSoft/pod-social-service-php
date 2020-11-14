<?php
use PHPUnit\Framework\TestCase;
use Pod\Social\Service\SocialService;
use Pod\Base\Service\BaseInfo;
use Pod\Base\Service\Exception\ValidationException;
use Pod\Base\Service\Exception\PodException;

final class ShareUserPostTest extends TestCase
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

	public function testShareUserPostAllParameters()
	{
		$params = [
			## ================= *Required Parameters  =================
			'forwardedPostId' => '',
			'confirmation' => '',
			'canComment' => '',
			'canLike' => '',
			'canRate' => '',
			'enable' => '',
			## ================= Optional Parameters  =================
			'scVoucherHash' => '',
			'scApiKey' => '',
			'token'     => '{Put Token}',
			//'scApiKey' => '{Put Service Call Api Key}',
			//'scVoucherHash' => '['{Put Service Call Voucher Hashes}', ...]',
		];
		try {
			$result = $SocialService->shareUserPost($params);
			$this->assertFalse($result['error']);
			$this->assertEquals($result['code'], 200);
		} catch (ValidationException $e) {
			$this->fail('ValidationException: ' . $e->getErrorsAsString());
		} catch (PodException $e) {
			$error = $e->getResult();
			$this->fail('PodException: ' . $error['message']);
		}
	}

	public function testShareUserPostRequiredParameters()
	{
		$params = [
			## ================= *Required Parameters  =================
			'forwardedPostId' => '',
			'confirmation' => '',
			'canComment' => '',
			'canLike' => '',
			'canRate' => '',
			'enable' => '',
		try {
			$result = $SocialService->shareUserPost($params);
			$this->assertFalse($result['error']);
			$this->assertEquals($result['code'], 200);
		} catch (ValidationException $e) {
			$this->fail('ValidationException: ' . $e->getErrorsAsString());
		} catch (PodException $e) {
			$error = $e->getResult();
			$this->fail('PodException: ' . $error['message']);
		}
	}

	public function testShareUserPostValidationError()
	{
		$paramsWithoutRequired = [];
		$paramsWrongValue = [
			## ======================= *Required Parameters  ==========================
			'forwardedPostId' => '123',
			'canComment' => 123,
			'canLike' => 123,
			'canRate' => 123,
			'enable' => 123,
			'confirmation' => 123,
			## ======================== Optional Parameters  ==========================
			'scVoucherHash' => '123',
			'scApiKey' => 123,
		];
		try {
			self::$SocialService->shareUserPost($paramsWithoutRequired);
		} catch (ValidationException $e) {
			$validation = $e->getErrorsAsArray();
			$this->assertNotEmpty($validation);

			$result = $e->getResult();

			$this->assertArrayHasKey('forwardedPostId', $validation);
			$this->assertEquals('The property forwardedPostId is required', $validation['forwardedPostId'][0]);

			$this->assertArrayHasKey('canComment', $validation);
			$this->assertEquals('The property canComment is required', $validation['canComment'][0]);

			$this->assertArrayHasKey('canLike', $validation);
			$this->assertEquals('The property canLike is required', $validation['canLike'][0]);

			$this->assertArrayHasKey('canRate', $validation);
			$this->assertEquals('The property canRate is required', $validation['canRate'][0]);

			$this->assertArrayHasKey('enable', $validation);
			$this->assertEquals('The property enable is required', $validation['enable'][0]);

			$this->assertArrayHasKey('confirmation', $validation);
			$this->assertEquals('The property confirmation is required', $validation['confirmation'][0]);


			$this->assertEquals(887, $result['code']);
		} catch (PodException $e) {
			$error = $e->getResult();
			$this->fail('PodException: ' . $error['message']);
		}
		try {
			self::$SocialService->shareUserPost($paramsWrongValue);
		} catch (ValidationException $e) {

			$validation = $e->getErrorsAsArray();
			$this->assertNotEmpty($validation);

			$result = $e->getResult();
			$this->assertArrayHasKey('forwardedPostId', $validation);
			$this->assertEquals('String value found, but an integer is required', $validation['forwardedPostId'][1]);

			$this->assertArrayHasKey('canComment', $validation);
			$this->assertEquals('Integer value found, but a string is required', $validation['canComment'][1]);

			$this->assertArrayHasKey('canLike', $validation);
			$this->assertEquals('Integer value found, but a string is required', $validation['canLike'][1]);

			$this->assertArrayHasKey('canRate', $validation);
			$this->assertEquals('Integer value found, but a string is required', $validation['canRate'][1]);

			$this->assertArrayHasKey('enable', $validation);
			$this->assertEquals('Integer value found, but a string is required', $validation['enable'][1]);

			$this->assertArrayHasKey('confirmation', $validation);
			$this->assertEquals('Integer value found, but a string is required', $validation['confirmation'][1]);

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