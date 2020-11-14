<?php
/**
 * Created by PhpStorm.
 * User: keshtgar
 * Date: 11/11/19
 * Time: 9:49 AM
 */
use PHPUnit\Framework\TestCase;
use Pod\Social\Service\SocialService;
use Pod\Base\Service\BaseInfo;
use Pod\Base\Service\Exception\ValidationException;
use Pod\Base\Service\Exception\PodException;

final class SocialServiceTest extends TestCase
{
//    public static $apiToken;
    public static $socialService;
    private $tokenIssuer;
    private $apiToken;
    private $scApiKey;
    public function setUp(): void
    {
        parent::setUp();
        # set serverType to SandBox or Production
        BaseInfo::initServerType(BaseInfo::SANDBOX_SERVER);
        $socialTestData =  require __DIR__ . '/socialTestData.php';
        $this->apiToken = $socialTestData['token'];
        // $this->tokenIssuer =  $socialTestData['tokenIssuer'];
        // $this->scApiKey = $socialTestData['scApiKey'];
        $baseInfo = new BaseInfo();
        $baseInfo->setTokenIssuer($this->apiToken);
        $baseInfo->setToken($this->apiToken);

        self::$socialService = new SocialService($baseInfo);
    }

    public function testAddCustomPostAllParameters()
    {
        $params =
            [
                "name" => uniqid('customPost_'),
                "canComment" =>true,
                "canLike" =>true,
                "canRate" =>true,
                "content" => "The Content of Post",
                "enable" =>true	, //	امکان دیده شدن پست در تایم لاین کسب و کار توسط مشتریان
                ## ======================== Optional Parameters  ==========================
                "metadata" => '{"name": "test"}', // [A json string to store extra content]
                "lat" => 22.45,
                "lng" => '45.55',
                "tags" => ['tag1', 'tag2'],
                "tagTrees" => ['tree1'],
                "tagTreeCategoryName" => ['Cat1'],
                "uniqueId" =>  uniqid() ,               // جهت جلوگیری از ثبت مجدد یک پست تکراری می توانید از این فیلد استفاده نمایید
                // 'scApiKey' => $this->scApiKey,
                'token'                => $this->apiToken,      # Api_Token 
//            'scVoucherHash'          => ["1234", "546"],
        ];

        try {
            $result = self::$socialService->addCustomPost($params);
            var_dump(result); die;
            $this->assertFalse($result['hasError']);
            $this->assertEquals($result['result']['statusCode'], 200);
        } catch (ValidationException $e) {
            $this->fail('ValidationException: ' . $e->getErrorsAsString());
        } catch (PodException $e) {
            $error = $e->getResult();
            $this->fail('PodException: ' . $error['message']);
        }
    }

    public function testAddCustomPostRequiredParameters()
    {
        $params =
            [
                ## ============= *Required Parameters  ===============
                "name" => uniqid('customPost_'),
                "canComment" =>true,
                "canLike" =>true,
                "canRate" =>true,
                "content" => "The Content of Post",
                "enable" =>true	, //
            ];
        try {
            $result = self::$socialService->addCustomPost($params);
            $this->assertFalse($result['hasError']);
            $this->assertEquals($result['result']['statusCode'], 200);
        } catch (ValidationException $e) {
            $this->fail('ValidationException: ' . $e->getErrorsAsString());
        } catch (PodException $e) {
            $error = $e->getResult();
            $this->fail('PodException: ' . $error['message']);
        }
    }

    // public function testAddCustomPostValidationError()
    // {
    //     $paramsWithoutRequired = [];
    //     $paramsWrongValue = [
    //         'scApiKey'             => 1234,
    //         'term'                 => 1234,
    //         'lat'                  => '25.34',
    //         'lng'                  => '34.49',
    //     ];
    //     try {
    //         self::$socialService->addCustomPost($paramsWithoutRequired);
    //     } catch (ValidationException $e) {

    //         $validation = $e->getErrorsAsArray();
    //         $this->assertNotEmpty($validation);

    //         $result = $e->getResult();

    //         $this->assertArrayHasKey('scApiKey', $validation);
    //         $this->assertEquals('The property scApiKey is required', $validation['scApiKey'][0]);

    //         $this->assertArrayHasKey('term', $validation);
    //         $this->assertEquals('The property term is required', $validation['term'][0]);

    //         $this->assertArrayHasKey('lat', $validation);
    //         $this->assertEquals('The property lat is required', $validation['lat'][0]);

    //         $this->assertArrayHasKey('lng', $validation);
    //         $this->assertEquals('The property lng is required', $validation['lng'][0]);

    //         $this->assertEquals(887, $result['code']);
    //     } catch (PodException $e) {
    //         $error = $e->getResult();
    //         $this->fail('PodException: ' . $error['message']);
    //     }
    //     try {
    //         self::$socialService->addCustomPost($paramsWrongValue);
    //     } catch (ValidationException $e) {

    //         $validation = $e->getErrorsAsArray();
    //         $this->assertNotEmpty($validation);

    //         $result = $e->getResult();


    //         $this->assertArrayHasKey('scApiKey', $validation);
    //         $this->assertEquals('Integer value found, but a string is required', $validation['scApiKey'][1]);

    //         $this->assertArrayHasKey('term', $validation);
    //         $this->assertEquals('Integer value found, but a string is required', $validation['term'][1]);

    //         $this->assertArrayHasKey('lat', $validation);
    //         $this->assertEquals('String value found, but a number is required', $validation['lat'][1]);

    //         $this->assertArrayHasKey('lng', $validation);
    //         $this->assertEquals('String value found, but a number is required', $validation['lng'][1]);

    //         $this->assertEquals(887, $result['code']);
    //     } catch (PodException $e) {
    //         $error = $e->getResult();
    //         $this->fail('PodException: ' . $error['message']);
    //     }
    // }

    public function testGetCustomPostListAllParameters()
    {
        $params = [
            ## ================= *Required Parameters  =================
            'businessId' => 4821,
            'size' => 10,
            'offset' => 0,
            ## ================= Optional Parameters  ==================
            'uniqueId' => '5e91bc17b55e0',
            'id' => 101016,
            // 'firstId' => '',
            // 'lastId' => '',
            'tags' => ['tag3', 'tag2'],
            // 'tagTrees' => '',
            // 'tagTreeCategoryName' => '',
            'fromDate' => '1399/01/23 10:00:00',
            'toDate' => '1399/01/24',
            'activityInfo' => true,
            'token'  => $this->apiToken,      # Api_Token | AccessToken
            // 'scApiKey' => '{Put Service Call Api Key}',
            // //'scVoucherHash' => '['{Put Service Call Voucher Hashes}', ...]',
        ];
        try {
            $result = self::$socialService->getCustomPostList($params);
            $this->assertFalse($result['hasError']);
            $this->assertEquals($result['result']['statusCode'], 200);
        } catch (ValidationException $e) {
            $this->fail('ValidationException: ' . $e->getErrorsAsString());
        } catch (PodException $e) {
            $error = $e->getResult();
            $this->fail('PodException: ' . $error['message']);
        }
    }

    public function testGetCustomPostListRequiredParameters()
    {
        $reqParams1 =
            [
            ## ================= *Required Parameters  =================
            'businessId' => 4821,
            'size' => 10,
            'offset' => 0,
            ];
        $reqParams2 =
            [
            ## ================= *Required Parameters  =================
            'businessId' => 4821,
            'size' => 10,
            'firstId' => 100,
            ];
        $reqParams3 =
            [
            ## ================= *Required Parameters  =================
            'businessId' => 4821,
            'size' => 10,
            'lastId' => 70000,
            ];
        try {
            $result1 = self::$socialService->getCustomPostList($reqParams1);
            $this->assertFalse($result1['hasError']);
            $this->assertEquals($result1['result']['statusCode'], 200);
            $result2 = self::$socialService->getCustomPostList($reqParams2);
            $this->assertFalse($result2['hasError']);
            $this->assertEquals($result2['result']['statusCode'], 200);
            $result3 = self::$socialService->getCustomPostList($reqParams3);
            $this->assertFalse($result3['hasError']);
            $this->assertEquals($result3['result']['statusCode'], 200);
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
            'scApiKey'             => 1234,
            'lat'                  => '25.34',
            'lng'                  => '34.49',
        ];
        try {
            self::$socialService->getCustomPostList($paramsWithoutRequired);
        } catch (ValidationException $e) {

            $validation = $e->getErrorsAsArray();
            $this->assertNotEmpty($validation);

            $result = $e->getResult();

            $this->assertArrayHasKey('scApiKey', $validation);
            $this->assertEquals('The property scApiKey is required', $validation['scApiKey'][0]);

            $this->assertArrayHasKey('lat', $validation);
            $this->assertEquals('The property lat is required', $validation['lat'][0]);

            $this->assertArrayHasKey('lng', $validation);
            $this->assertEquals('The property lng is required', $validation['lng'][0]);

            $this->assertEquals(887, $result['code']);
        } catch (PodException $e) {
            $error = $e->getResult();
            $this->fail('PodException: ' . $error['message']);
        }
        try {
            self::$socialService->getCustomPostList($paramsWrongValue);
        } catch (ValidationException $e) {

            $validation = $e->getErrorsAsArray();
            $this->assertNotEmpty($validation);

            $result = $e->getResult();


            $this->assertArrayHasKey('scApiKey', $validation);
            $this->assertEquals('Integer value found, but a string is required', $validation['scApiKey'][1]);

            $this->assertArrayHasKey('lat', $validation);
            $this->assertEquals('String value found, but a number is required', $validation['lat'][1]);

            $this->assertArrayHasKey('lng', $validation);
            $this->assertEquals('String value found, but a number is required', $validation['lng'][1]);

            $this->assertEquals(887, $result['code']);
        } catch (PodException $e) {
            $error = $e->getResult();
            $this->fail('PodException: ' . $error['message']);
        }
    }

    public function testDirectionAllParameters()
    {
        $params =
            [
                ## ======================= *Required Parameters  ==========================
                'scApiKey'             => $this->directionScApiKey,
                'origin'                 =>
                    [
                        'lat' => 36.3141962,
                        'lng' => 59.5412054
                    ],
                'destination'           =>
                    [
                        'lat' => 36.307656,
                        'lng' => 59.530862,
                    ],
                ## ======================= Optional Parameters  ===========================
                'waypoints'=>[
                    [ 'lat'=>36.32203767, 'lng'=>59.4759665 ],
                    [ 'lat'=>36.32203768, 'lng'=>59.4759666 ]
                ],
                'avoidTrafficZone'=>false,
                'avoidOddEvenZone'=>true,
                'alternative'=>true,
                'token'                => $this->apiToken,      # Api_Token | AccessToken
                'tokenIssuer'           => $this->tokenIssuer, # default is 1
//            'scVoucherHash'          => ["1234", "546"],
        ];

        try {
            $result = self::$socialService->direction($params);
            $this->assertFalse($result['hasError']);
            $this->assertEquals($result['result']['statusCode'], 200);
        } catch (ValidationException $e) {
            $this->fail('ValidationException: ' . $e->getErrorsAsString());
        } catch (PodException $e) {
            $error = $e->getResult();
            $this->fail('PodException: ' . $error['message']);
        }
    }

    public function testDirectionRequiredParameters()
    {
        $params =
            [
                ## ======================= *Required Parameters  ==========================
                'scApiKey'             => $this->directionScApiKey,
                'origin'                 =>
                    [
                        'lat' => 59.6157432,
                        'lng' => 36.2880443
                    ],
                'destination'           =>
                    [
                        'lat' => 36.307656,
                        'lng' => 59.530862,
                    ],
            ];
        try {
            $result = self::$socialService->direction($params);
            $this->assertFalse($result['hasError']);
            $this->assertEquals($result['result']['statusCode'], 200);
        } catch (ValidationException $e) {
            $this->fail('ValidationException: ' . $e->getErrorsAsString());
        } catch (PodException $e) {
            $error = $e->getResult();
            $this->fail('PodException: ' . 'code: '.$error['code'] . ';;' . $error['message']);
        }
    }

    public function testDirectionValidationError()
    {
        $paramsWithoutRequired = [];
        $paramsWrongValue = [
            'scApiKey'             => 1234,
            'origin'                 =>
                [
                ],
            'destination'           => '',
        ];
        try {
            self::$socialService->direction($paramsWithoutRequired);
        } catch (ValidationException $e) {

            $validation = $e->getErrorsAsArray();
            $this->assertNotEmpty($validation);

            $result = $e->getResult();

            $this->assertArrayHasKey('scApiKey', $validation);
            $this->assertEquals('The property scApiKey is required', $validation['scApiKey'][0]);

            $this->assertArrayHasKey('origin', $validation);
            $this->assertEquals('The property origin is required', $validation['origin'][0]);

            $this->assertArrayHasKey('destination', $validation);
            $this->assertEquals('The property destination is required', $validation['destination'][0]);

            $this->assertEquals(887, $result['code']);
        } catch (PodException $e) {
            $error = $e->getResult();
            $this->fail('PodException: ' . $error['message']);
        }
        try {
            self::$socialService->direction($paramsWrongValue);
        } catch (ValidationException $e) {

            $validation = $e->getErrorsAsArray();
            $this->assertNotEmpty($validation);

            $result = $e->getResult();


            $this->assertArrayHasKey('scApiKey', $validation);
            $this->assertEquals('Integer value found, but a string is required', $validation['scApiKey'][1]);

            $this->assertArrayHasKey('origin', $validation);
            $this->assertEquals('There must be a minimum of 1 items in the array', $validation['origin'][1]);

            $this->assertArrayHasKey('destination', $validation);
            $this->assertEquals('String value found, but an array is required', $validation['destination'][1]);

            $this->assertEquals(887, $result['code']);
        } catch (PodException $e) {
            $error = $e->getResult();
            $this->fail('PodException: ' . $error['message']);
        }
    }

    public function testNoTrafficDirectionAllParameters()
    {
        $params =
            [
                ## ======================= *Required Parameters  ==========================
                'scApiKey'             => $this->noTrafficDirectionScApiKey,
                'origin'                 =>
                    [
                        'lat' => 36.3141962,
                        'lng' => 59.5412054
                    ],
                'destination'           =>
                    [
                        'lat' => 36.32203769,
                        'lng' => 59.4759667
                    ],
                ## ======================= Optional Parameters  ===========================
                'waypoints'=> [
                    [ 'lat'=>36.32203767, 'lng'=>59.4759665 ],
                    [ 'lat'=>36.32203768, 'lng'=>59.4759666 ]
                ],
                'avoidTrafficZone'=>false,
                'avoidOddEvenZone'=>true,
                'alternative'=>true,
                'token'                => $this->apiToken,      # Api_Token | AccessToken
                'tokenIssuer'           => $this->tokenIssuer, # default is 1
//            'scVoucherHash'          => ["1234", "546"],
        ];

        try {
            $result = self::$socialService->noTrafficDirection($params);
            $this->assertFalse($result['hasError']);
            $this->assertEquals($result['result']['statusCode'], 200);
        } catch (ValidationException $e) {
            $this->fail('ValidationException: ' . $e->getErrorsAsString());
        } catch (PodException $e) {
            $error = $e->getResult();
            $this->fail('PodException: ' . $error['message']);
        }
    }

    public function testNoTrafficDirectionRequiredParameters()
    {
        $params =
            [
                ## ======================= *Required Parameters  ==========================
                'scApiKey'             => $this->noTrafficDirectionScApiKey,
                'origin'                 =>
                    [
                        'lat' => 36.3141962,
                        'lng' => 59.5412054
                    ],
                'destination'           =>
                    [
                        'lat' => 36.32203769,
                        'lng' => 59.4759667
                    ],
        ];
        try {
            $result = self::$socialService->noTrafficDirection($params);
            $this->assertFalse($result['hasError']);
            $this->assertEquals($result['result']['statusCode'], 200);
        } catch (ValidationException $e) {
            $this->fail('ValidationException: ' . $e->getErrorsAsString());
        } catch (PodException $e) {
            $error = $e->getResult();
            $this->fail('PodException: ' . $error['message']);
        }
    }

    public function testNoTrafficDirectionValidationError()
    {
        $paramsWithoutRequired = [];
        $paramsWrongValue = [
            'scApiKey'             => 1234,
            'origin'                 =>
                [
                ],
            'destination'           => '',
        ];
        try {
            self::$socialService->noTrafficDirection($paramsWithoutRequired);
        } catch (ValidationException $e) {

            $validation = $e->getErrorsAsArray();
            $this->assertNotEmpty($validation);

            $result = $e->getResult();

            $this->assertArrayHasKey('scApiKey', $validation);
            $this->assertEquals('The property scApiKey is required', $validation['scApiKey'][0]);

            $this->assertArrayHasKey('origin', $validation);
            $this->assertEquals('The property origin is required', $validation['origin'][0]);

            $this->assertArrayHasKey('destination', $validation);
            $this->assertEquals('The property destination is required', $validation['destination'][0]);

            $this->assertEquals(887, $result['code']);
        } catch (PodException $e) {
            $error = $e->getResult();
            $this->fail('PodException: ' . $error['message']);
        }
        try {
            self::$socialService->noTrafficDirection($paramsWrongValue);
        } catch (ValidationException $e) {

            $validation = $e->getErrorsAsArray();
            $this->assertNotEmpty($validation);

            $result = $e->getResult();


            $this->assertArrayHasKey('scApiKey', $validation);
            $this->assertEquals('Integer value found, but a string is required', $validation['scApiKey'][1]);

            $this->assertArrayHasKey('origin', $validation);
            $this->assertEquals('There must be a minimum of 1 items in the array', $validation['origin'][1]);

            $this->assertArrayHasKey('destination', $validation);
            $this->assertEquals('String value found, but an array is required', $validation['destination'][1]);

            $this->assertEquals(887, $result['code']);
        } catch (PodException $e) {
            $error = $e->getResult();
            $this->fail('PodException: ' . $error['message']);
        }
    }

    public function testDistanceMatrixAllParameters()
    {
        $params =
            [
                ## ======================= *Required Parameters  ==========================
                'scApiKey'             => $this->distanceMatrixScApiKey,
                'origins'                =>
                    [[
                        'lat' => 36.3141962,
                        'lng' => 59.5412054
                    ],
                        [
                            'lat' => 36.32203767,
                            'lng' => 59.4759665
                        ]
                    ],
                'destinations'           =>[
                    [
                        'lat' => 36.32203769,
                        'lng' => 59.4759667
                    ],
                    [
                        'lat' => 36.12111,
                        'lng' => 59.324454
                    ]
                ],
                ## ======================== Optional Parameters  ==========================
                'token'                => $this->apiToken,      # Api_Token | AccessToken
                'tokenIssuer'           => $this->tokenIssuer, # default is 1
//            'scVoucherHash'          => ["1234", "546"],
        ];

        try {
            $result = self::$socialService->distanceMatrix($params);
            $this->assertFalse($result['hasError']);
            $this->assertEquals($result['result']['statusCode'], 200);
        } catch (ValidationException $e) {
            $this->fail('ValidationException: ' . $e->getErrorsAsString());
        } catch (PodException $e) {
            $error = $e->getResult();
            $this->fail('PodException: ' . $error['message']);
        }
    }

    public function testDistanceMatrixRequiredParameters()
    {
        $params =
            [
                ## ======================= *Required Parameters  ==========================
                'scApiKey'             => $this->distanceMatrixScApiKey,
                'origins'                =>
                    [[
                        'lat' => 36.3141962,
                        'lng' => 59.5412054
                    ],
                        [
                            'lat' => 36.32203767,
                            'lng' => 59.4759665
                        ]
                    ],
                'destinations'           =>[
                    [
                        'lat' => 36.32203769,
                        'lng' => 59.4759667
                    ],
                    [
                        'lat' => 36.12111,
                        'lng' => 59.324454
                    ]
                ],
            ];
        try {
            $result = self::$socialService->distanceMatrix($params);
            $this->assertFalse($result['hasError']);
            $this->assertEquals($result['result']['statusCode'], 200);
        } catch (ValidationException $e) {
            $this->fail('ValidationException: ' . $e->getErrorsAsString());
        } catch (PodException $e) {
            $error = $e->getResult();
            $this->fail('PodException: ' . $error['message']);
        }
    }

    public function testDistanceMatrixValidationError()
    {
        $paramsWithoutRequired = [];
        $paramsWrongValue = [
            'scApiKey' => 1234,
            'origins'  => [],
            'destinations' => [],
        ];
        try {
            self::$socialService->distanceMatrix($paramsWithoutRequired);
        } catch (ValidationException $e) {

            $validation = $e->getErrorsAsArray();
            $this->assertNotEmpty($validation);

            $result = $e->getResult();

            $this->assertArrayHasKey('scApiKey', $validation);
            $this->assertEquals('The property scApiKey is required', $validation['scApiKey'][0]);

            $this->assertArrayHasKey('origins', $validation);
            $this->assertEquals('The property origins is required', $validation['origins'][0]);

            $this->assertArrayHasKey('destinations', $validation);
            $this->assertEquals('The property destinations is required', $validation['destinations'][0]);

            $this->assertEquals(887, $result['code']);
        } catch (PodException $e) {
            $error = $e->getResult();
            $this->fail('PodException: ' . $error['message']);
        }
        try {
            self::$socialService->distanceMatrix($paramsWrongValue);
        } catch (ValidationException $e) {

            $validation = $e->getErrorsAsArray();
            $this->assertNotEmpty($validation);

            $result = $e->getResult();


            $this->assertArrayHasKey('scApiKey', $validation);
            $this->assertEquals('Integer value found, but a string is required', $validation['scApiKey'][1]);

            $this->assertArrayHasKey('origins', $validation);
            $this->assertEquals('There must be a minimum of 1 items in the array', $validation['origins'][1]);

            $this->assertArrayHasKey('destinations', $validation);
            $this->assertEquals('There must be a minimum of 1 items in the array', $validation['destinations'][1]);
            $this->assertEquals(887, $result['code']);
        } catch (PodException $e) {
            $error = $e->getResult();
            $this->fail('PodException: ' . $error['message']);
        }
    }

    public function testNoTrafficDistanceMatrixAllParameters()
    {
        $params =
            [
                ## ======================= *Required Parameters  ==========================
                'scApiKey'             => $this->noTrafficDistanceMatrixScApiKey,
                'origins'                =>
                    [[
                        'lat' => 36.3141962,
                        'lng' => 59.5412054
                    ],
                        [
                            'lat' => 36.32203767,
                            'lng' => 59.4759665
                        ]
                    ],
                'destinations'           =>[
                    [
                        'lat' => 36.32203769,
                        'lng' => 59.4759667
                    ],
                    [
                        'lat' => 36.12111,
                        'lng' => 59.324454
                    ]
                ],
                ## ======================== Optional Parameters  ==========================
                'token'                => $this->apiToken,      # Api_Token | AccessToken
                'tokenIssuer'           => $this->tokenIssuer, # default is 1
//            'scVoucherHash'          => ["1234", "546"],
        ];

        try {
            $result = self::$socialService->noTrafficDistanceMatrix($params);
            $this->assertFalse($result['hasError']);
            $this->assertEquals($result['result']['statusCode'], 200);
        } catch (ValidationException $e) {
            $this->fail('ValidationException: ' . $e->getErrorsAsString());
        } catch (PodException $e) {
            $error = $e->getResult();
            $this->fail('PodException: ' . $error['message']);
        }
    }

    public function testNoTrafficDistanceMatrixRequiredParameters()
    {
        $params =
            [
                ## ======================= *Required Parameters  ==========================
                'scApiKey'             => $this->noTrafficDistanceMatrixScApiKey,
                'origins'                =>
                    [[
                        'lat' => 36.3141962,
                        'lng' => 59.5412054
                    ],
                        [
                            'lat' => 36.32203767,
                            'lng' => 59.4759665
                        ]
                    ],
                'destinations'           =>[
                    [
                        'lat' => 36.32203769,
                        'lng' => 59.4759667
                    ],
                    [
                        'lat' => 36.12111,
                        'lng' => 59.324454
                    ]
                ],
            ];
        try {
            $result = self::$socialService->noTrafficDistanceMatrix($params);
            $this->assertFalse($result['hasError']);
            $this->assertEquals($result['result']['statusCode'], 200);
        } catch (ValidationException $e) {
            $this->fail('ValidationException: ' . $e->getErrorsAsString());
        } catch (PodException $e) {
            $error = $e->getResult();
            $this->fail('PodException: ' . $error['message']);
        }
    }

    public function testNoTrafficDistanceMatrixValidationError()
    {
        $paramsWithoutRequired = [];
        $paramsWrongValue = [
            'scApiKey' => 1234,
            'origins'  => [],
            'destinations' => [],
        ];
        try {
            self::$socialService->noTrafficDistanceMatrix($paramsWithoutRequired);
        } catch (ValidationException $e) {

            $validation = $e->getErrorsAsArray();
            $this->assertNotEmpty($validation);

            $result = $e->getResult();

            $this->assertArrayHasKey('scApiKey', $validation);
            $this->assertEquals('The property scApiKey is required', $validation['scApiKey'][0]);

            $this->assertArrayHasKey('origins', $validation);
            $this->assertEquals('The property origins is required', $validation['origins'][0]);

            $this->assertArrayHasKey('destinations', $validation);
            $this->assertEquals('The property destinations is required', $validation['destinations'][0]);

            $this->assertEquals(887, $result['code']);
        } catch (PodException $e) {
            $error = $e->getResult();
            $this->fail('PodException: ' . $error['message']);
        }
        try {
            self::$socialService->noTrafficDistanceMatrix($paramsWrongValue);
        } catch (ValidationException $e) {

            $validation = $e->getErrorsAsArray();
            $this->assertNotEmpty($validation);

            $result = $e->getResult();

            $this->assertArrayHasKey('scApiKey', $validation);
            $this->assertEquals('Integer value found, but a string is required', $validation['scApiKey'][1]);

            $this->assertArrayHasKey('origins', $validation);
            $this->assertEquals('There must be a minimum of 1 items in the array', $validation['origins'][1]);

            $this->assertArrayHasKey('destinations', $validation);
            $this->assertEquals('There must be a minimum of 1 items in the array', $validation['destinations'][1]);

            $this->assertEquals(887, $result['code']);
        } catch (PodException $e) {
            $error = $e->getResult();
            $this->fail('PodException: ' . $error['message']);
        }
    }

    public function testMapMatchingAllParameters()
    {
        $params =
            [
                ## ======================= *Required Parameters  ==========================
                'scApiKey'             => $this->mapMatchingScApiKey,
                'path'  => [
                    [
                        'lat'=> 36.297886,
                        'lng'=> 59.604335
                    ],
                    [
                        'lat'=> 36.297218,
                        'lng' =>  59.603496
                    ]
                ],
                ## ======================== Optional Parameters  ==========================
                'token'                => $this->apiToken,      # Api_Token | AccessToken
                'tokenIssuer'           => $this->tokenIssuer, # default is 1
//            'scVoucherHash'          => ["1234", "546"],
        ];

        try {
            $result = self::$socialService->mapMatching($params);
            $this->assertFalse($result['hasError']);
            $this->assertEquals($result['result']['statusCode'], 200);
        } catch (ValidationException $e) {
            $this->fail('ValidationException: ' . $e->getErrorsAsString());
        } catch (PodException $e) {
            $error = $e->getResult();
            $this->fail('PodException: ' . $error['message']);
        }
    }

    public function testMapMatchingRequiredParameters()
    {
        $params =
            [
                ## ======================= *Required Parameters  ==========================
                'scApiKey'             => $this->mapMatchingScApiKey,
                'path'  => [
                    [
                        'lat'=> 36.297886,
                        'lng'=> 59.604335
                    ],
                    [
                        'lat'=> 36.297218,
                        'lng' =>  59.603496
                    ]
                ],
            ];
        try {
            $result = self::$socialService->mapMatching($params);
            $this->assertFalse($result['hasError']);
            $this->assertEquals($result['result']['statusCode'], 200);
        } catch (ValidationException $e) {
            $this->fail('ValidationException: ' . $e->getErrorsAsString());
        } catch (PodException $e) {
            $error = $e->getResult();
            $this->fail('PodException: ' . $error['message']);
        }
    }

    public function testMapMatchingValidationError()
    {
        $paramsWithoutRequired = [];
        $paramsWrongValue = [
            'scApiKey'             => 1234,
            'path'  => [
            ],
        ];
        try {
            self::$socialService->mapMatching($paramsWithoutRequired);
        } catch (ValidationException $e) {

            $validation = $e->getErrorsAsArray();
            $this->assertNotEmpty($validation);

            $result = $e->getResult();

            $this->assertArrayHasKey('scApiKey', $validation);
            $this->assertEquals('The property scApiKey is required', $validation['scApiKey'][0]);

            $this->assertArrayHasKey('path', $validation);
            $this->assertEquals('The property path is required', $validation['path'][0]);

            $this->assertEquals(887, $result['code']);
        } catch (PodException $e) {
            $error = $e->getResult();
            $this->fail('PodException: ' . $error['message']);
        }
        try {
            self::$socialService->mapMatching($paramsWrongValue);
        } catch (ValidationException $e) {

            $validation = $e->getErrorsAsArray();
            $this->assertNotEmpty($validation);

            $result = $e->getResult();


            $this->assertArrayHasKey('scApiKey', $validation);
            $this->assertEquals('Integer value found, but a string is required', $validation['scApiKey'][1]);

            $this->assertArrayHasKey('path', $validation);
            $this->assertEquals('There must be a minimum of 2 items in the array', $validation['path'][1]);

            $this->assertEquals(887, $result['code']);
        } catch (PodException $e) {
            $error = $e->getResult();
            $this->fail('PodException: ' . $error['message']);
        }
    }
}