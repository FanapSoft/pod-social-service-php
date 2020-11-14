<?php
use PHPUnit\Framework\TestCase;
use Pod\Social\Service\SocialService;
use Pod\Base\Service\BaseInfo;
use Pod\Base\Service\Exception\ValidationException;
use Pod\Base\Service\Exception\PodException;

final class UnconfirmCommentOfCustomPostTest extends TestCase
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

	public function testUnconfirmCommentOfCustomPostAllParameters()
	{
		$params = [
			## ================= *Required Parameters  =================
			'commentId' => '',
			## ================= Optional Parameters  =================
			'scVoucherHash' => '',
			'scApiKey' => '',
			'token'     => '{Put Token}',
			//'scApiKey' => '{Put Service Call Api Key}',
			//'scVoucherHash' => '['{Put Service Call Voucher Hashes}', ...]',
		];
		try {
			$result = $SocialService->unconfirmCommentOfCustomPost($params);
			$this->assertFalse($result['error']);
			$this->assertEquals($result['code'], 200);
		} catch (ValidationException $e) {
			$this->fail('ValidationException: ' . $e->getErrorsAsString());
		} catch (PodException $e) {
			$error = $e->getResult();
			$this->fail('PodException: ' . $error['message']);
		}
	}

	public function testUnconfirmCommentOfCustomPostRequiredParameters()
	{
		$params = [
			## ================= *Required Parameters  =================
			'commentId' => '',
		try {
			$result = $SocialService->unconfirmCommentOfCustomPost($params);
			$this->assertFalse($result['error']);
			$this->assertEquals($result['code'], 200);
		} catch (ValidationException $e) {
			$this->fail('ValidationException: ' . $e->getErrorsAsString());
		} catch (PodException $e) {
			$error = $e->getResult();
			$this->fail('PodException: ' . $error['message']);
		}
	}

	public function testUnconfirmCommentOfCustomPostValidationError()
	{
		$paramsWithoutRequired = [];
		$paramsWrongValue = [
			## ======================= *Required Parameters  ==========================
			'commentId' => '123',
			## ======================== Optional Parameters  ==========================
			'scVoucherHash' => '123',
			'scApiKey' => 123,
		];
		try {
			self::$SocialService->unconfirmCommentOfCustomPost($paramsWithoutRequired);
		} catch (ValidationException $e) {
			$validation = $e->getErrorsAsArray();
			$this->assertNotEmpty($validation);

			$result = $e->getResult();

			$this->assertArrayHasKey('commentId', $validation);
			$this->assertEquals('The property commentId is required', $validation['commentId'][0]);


			$this->assertEquals(887, $result['code']);
		} catch (PodException $e) {
			$error = $e->getResult();
			$this->fail('PodException: ' . $error['message']);
		}
		try {
			self::$SocialService->unconfirmCommentOfCustomPost($paramsWrongValue);
		} catch (ValidationException $e) {

			$validation = $e->getErrorsAsArray();
			$this->assertNotEmpty($validation);

			$result = $e->getResult();
			$this->assertArrayHasKey('commentId', $validation);
			$this->assertEquals('String value found, but an integer is required', $validation['commentId'][1]);

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