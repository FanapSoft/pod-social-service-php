<?php
use PHPUnit\Framework\TestCase;
use Pod\Social\Service\SocialService;
use Pod\Base\Service\BaseInfo;
use Pod\Base\Service\Exception\ValidationException;
use Pod\Base\Service\Exception\PodException;

final class FullTextSearchTest extends TestCase
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

	public function testFullTextSearchAllParameters()
	{
		$params = [
			## ================= *Required Parameters  =================
			'query' => '',
			'size' => '',
			'offset' => '',
			## ================= Optional Parameters  =================
			'type' => '',
			'guildCodes' => '',
			'distance' => '',
			'tags' => '',
			'tagTrees' => '',
			'tagTreeCodes' => '',
			'dateFrom' => '',
			'dateTo' => '',
			'lat' => '',
			'lng' => '',
			'scVoucherHash' => '',
			'scApiKey' => '',
			'token'     => '{Put Token}',
			//'scApiKey' => '{Put Service Call Api Key}',
			//'scVoucherHash' => '['{Put Service Call Voucher Hashes}', ...]',
		];
		try {
			$result = $SocialService->fullTextSearch($params);
			$this->assertFalse($result['error']);
			$this->assertEquals($result['code'], 200);
		} catch (ValidationException $e) {
			$this->fail('ValidationException: ' . $e->getErrorsAsString());
		} catch (PodException $e) {
			$error = $e->getResult();
			$this->fail('PodException: ' . $error['message']);
		}
	}

	public function testFullTextSearchRequiredParameters()
	{
		$params = [
			## ================= *Required Parameters  =================
			'query' => '',
			'size' => '',
			'offset' => '',
		try {
			$result = $SocialService->fullTextSearch($params);
			$this->assertFalse($result['error']);
			$this->assertEquals($result['code'], 200);
		} catch (ValidationException $e) {
			$this->fail('ValidationException: ' . $e->getErrorsAsString());
		} catch (PodException $e) {
			$error = $e->getResult();
			$this->fail('PodException: ' . $error['message']);
		}
	}

	public function testFullTextSearchValidationError()
	{
		$paramsWithoutRequired = [];
		$paramsWrongValue = [
			## ======================= *Required Parameters  ==========================
			'query' => 123,
			'offset' => '123',
			'size' => '123',
			## ======================== Optional Parameters  ==========================
			'type' => '123',
			'guildCodes' => '123',
			'distance' => '123',
			'tags' => '123',
			'tagTrees' => '123',
			'tagTreeCodes' => '123',
			'dateFrom' => 123,
			'dateTo' => 123,
			'lat' => 123,
			'lng' => 123,
			'scVoucherHash' => '123',
			'scApiKey' => 123,
		];
		try {
			self::$SocialService->fullTextSearch($paramsWithoutRequired);
		} catch (ValidationException $e) {
			$validation = $e->getErrorsAsArray();
			$this->assertNotEmpty($validation);

			$result = $e->getResult();

			$this->assertArrayHasKey('query', $validation);
			$this->assertEquals('The property query is required', $validation['query'][0]);

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
			self::$SocialService->fullTextSearch($paramsWrongValue);
		} catch (ValidationException $e) {

			$validation = $e->getErrorsAsArray();
			$this->assertNotEmpty($validation);

			$result = $e->getResult();
			$this->assertArrayHasKey('query', $validation);
			$this->assertEquals('Integer value found, but a string is required', $validation['query'][1]);

			$this->assertArrayHasKey('offset', $validation);
			$this->assertEquals('String value found, but an integer is required', $validation['offset'][1]);

			$this->assertArrayHasKey('size', $validation);
			$this->assertEquals('String value found, but an integer is required', $validation['size'][1]);

			$this->assertArrayHasKey('type', $validation);
			$this->assertEquals('String value found, but an integer is required', $validation['type'][0]);

			$this->assertArrayHasKey('guildCodes', $validation);
			$this->assertEquals('String value found, but an array is required', $validation['guildCodes'][0]);

			$this->assertArrayHasKey('distance', $validation);
			$this->assertEquals('String value found, but a number is required', $validation['distance'][0]);

			$this->assertArrayHasKey('tags', $validation);
			$this->assertEquals('String value found, but an array is required', $validation['tags'][0]);

			$this->assertArrayHasKey('tagTrees', $validation);
			$this->assertEquals('String value found, but an array is required', $validation['tagTrees'][0]);

			$this->assertArrayHasKey('tagTreeCodes', $validation);
			$this->assertEquals('String value found, but an array is required', $validation['tagTreeCodes'][0]);

			$this->assertArrayHasKey('dateFrom', $validation);
			$this->assertEquals('Integer value found, but a string is required', $validation['dateFrom'][0]);

			$this->assertArrayHasKey('dateTo', $validation);
			$this->assertEquals('Integer value found, but a string is required', $validation['dateTo'][0]);

			$this->assertArrayHasKey('lat', $validation);
			$this->assertEquals('Integer value found, but a string is required', $validation['lat'][0]);

			$this->assertArrayHasKey('lng', $validation);
			$this->assertEquals('Integer value found, but a string is required', $validation['lng'][0]);

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