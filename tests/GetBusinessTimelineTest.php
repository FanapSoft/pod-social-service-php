<?php
use PHPUnit\Framework\TestCase;
use Pod\Social\Service\SocialService;
use Pod\Base\Service\BaseInfo;
use Pod\Base\Service\Exception\ValidationException;
use Pod\Base\Service\Exception\PodException;

final class GetBusinessTimelineTest extends TestCase
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

	public function testGetBusinessTimelineAllParameters()
	{
		$params = [
			## ================= *Required Parameters  =================
			'timelineId' => '',
			## ================= Optional Parameters  =================
			'entityId' => '',
			'uniqueId' => '',
			'postId' => '',
			'firstId' => '',
			'lastId' => '',
			'offset' => '',
			'size' => '',
			'type' => '',
			'guildCodes' => '',
			'metadata' => '',
			'tags' => '',
			'tagTrees' => '',
			'tagTreeCategoryName' => '',
			'fromDate' => '',
			'toDate' => '',
			'scVoucherHash' => '',
			'scApiKey' => '',
			'token'     => '{Put Token}',
			//'scApiKey' => '{Put Service Call Api Key}',
			//'scVoucherHash' => '['{Put Service Call Voucher Hashes}', ...]',
		];
		try {
			$result = $SocialService->getBusinessTimeline($params);
			$this->assertFalse($result['error']);
			$this->assertEquals($result['code'], 200);
		} catch (ValidationException $e) {
			$this->fail('ValidationException: ' . $e->getErrorsAsString());
		} catch (PodException $e) {
			$error = $e->getResult();
			$this->fail('PodException: ' . $error['message']);
		}
	}

	public function testGetBusinessTimelineRequiredParameters()
	{
		$params = [
			## ================= *Required Parameters  =================
			'timelineId' => '',
		try {
			$result = $SocialService->getBusinessTimeline($params);
			$this->assertFalse($result['error']);
			$this->assertEquals($result['code'], 200);
		} catch (ValidationException $e) {
			$this->fail('ValidationException: ' . $e->getErrorsAsString());
		} catch (PodException $e) {
			$error = $e->getResult();
			$this->fail('PodException: ' . $error['message']);
		}
	}

	public function testGetBusinessTimelineValidationError()
	{
		$paramsWithoutRequired = [];
		$paramsWrongValue = [
			## ======================= *Required Parameters  ==========================
			'timelineId' => '123',
			## ======================== Optional Parameters  ==========================
			'entityId' => '123',
			'uniqueId' => '123',
			'postId' => '123',
			'firstId' => '123',
			'lastId' => '123',
			'offset' => '123',
			'size' => '123',
			'type' => '123',
			'guildCodes' => '123',
			'metadata' => 123,
			'tags' => '123',
			'tagTrees' => '123',
			'tagTreeCategoryName' => '123',
			'fromDate' => 123,
			'toDate' => 123,
			'scVoucherHash' => '123',
			'scApiKey' => 123,
		];
		try {
			self::$SocialService->getBusinessTimeline($paramsWithoutRequired);
		} catch (ValidationException $e) {
			$validation = $e->getErrorsAsArray();
			$this->assertNotEmpty($validation);

			$result = $e->getResult();

			$this->assertArrayHasKey('timelineId', $validation);
			$this->assertEquals('The property timelineId is required', $validation['timelineId'][0]);


			$this->assertEquals(887, $result['code']);
		} catch (PodException $e) {
			$error = $e->getResult();
			$this->fail('PodException: ' . $error['message']);
		}
		try {
			self::$SocialService->getBusinessTimeline($paramsWrongValue);
		} catch (ValidationException $e) {

			$validation = $e->getErrorsAsArray();
			$this->assertNotEmpty($validation);

			$result = $e->getResult();
			$this->assertArrayHasKey('timelineId', $validation);
			$this->assertEquals('String value found, but an integer is required', $validation['timelineId'][1]);

			$this->assertArrayHasKey('entityId', $validation);
			$this->assertEquals('String value found, but an integer is required', $validation['entityId'][0]);

			$this->assertArrayHasKey('uniqueId', $validation);
			$this->assertEquals('String value found, but an array is required', $validation['uniqueId'][0]);

			$this->assertArrayHasKey('postId', $validation);
			$this->assertEquals('String value found, but an array is required', $validation['postId'][0]);

			$this->assertArrayHasKey('firstId', $validation);
			$this->assertEquals('String value found, but an integer is required', $validation['firstId'][0]);

			$this->assertArrayHasKey('lastId', $validation);
			$this->assertEquals('String value found, but an integer is required', $validation['lastId'][0]);

			$this->assertArrayHasKey('offset', $validation);
			$this->assertEquals('String value found, but an integer is required', $validation['offset'][0]);

			$this->assertArrayHasKey('size', $validation);
			$this->assertEquals('String value found, but an integer is required', $validation['size'][0]);

			$this->assertArrayHasKey('type', $validation);
			$this->assertEquals('String value found, but an integer is required', $validation['type'][0]);

			$this->assertArrayHasKey('guildCodes', $validation);
			$this->assertEquals('String value found, but an array is required', $validation['guildCodes'][0]);

			$this->assertArrayHasKey('metadata', $validation);
			$this->assertEquals('Integer value found, but a string is required', $validation['metadata'][0]);

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