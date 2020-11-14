<?php
namespace Pod\Social\Service;

use Pod\Base\Service\BaseService;
use Pod\Base\Service\ApiRequestHandler;
use Pod\Base\Service\Exception\ValidationException;

class SocialService extends BaseService
{
    private $header;
    private static $socialApi;
    private static $jsonSchema;
    private static $serviceCallProductId;

    public function __construct($baseInfo)
    {
        parent::__construct();
        $this->header = [
            '_token_issuer_'    =>  $baseInfo->getTokenIssuer(),
            '_token_'           => $baseInfo->getToken()
        ];
        self::$jsonSchema = json_decode(file_get_contents(__DIR__ . '/../config/validationSchema.json'), true);
        self::$socialApi = require __DIR__ . '/../config/apiConfig.php';
        self::$serviceCallProductId = require __DIR__ . '/../config/serviceCallProductId.php';
        self::$serviceCallProductId = self::$serviceCallProductId[self::$serverType];
    }

    public function addCustomPost($params) {
        $apiName = 'addCustomPost';
        $optionHasArray = true;
        if (isset($params['tags']) && is_array($params['tags'])) {
            $params['tags'] = implode(',', $params['tags']);
        }
        list($method, $option) = $this->prepareDataBeforeSend($params, $apiName, $optionHasArray);

        return ApiRequestHandler::Request(
            self::$config[self::$serverType][self::$socialApi[$apiName]['baseUri']],
            $method,
            self::$socialApi[$apiName]['subUri'],
            $option,
            false,
            $optionHasArray
        );
    }

    public function getCustomPostList($params) {
        $apiName = 'getCustomPostList';
        $optionHasArray = true;
        list($method, $option) = $this->prepareDataBeforeSend($params, $apiName, $optionHasArray);

        return ApiRequestHandler::Request(
            self::$config[self::$serverType][self::$socialApi[$apiName]['baseUri']],
            $method,
            self::$socialApi[$apiName]['subUri'],
            $option,
            false,
            $optionHasArray
        );
    }

    public function addCustomPostList($params) {
        $apiName = 'addCustomPostList';
        $optionHasArray = false;
        $header = $this->header;
        // $header["Content-Type"] = 'application/x-www-form-urlencoded';
        $method = self::$socialApi[$apiName]['method'];
        $paramKey = $method == 'GET' ? 'query' : 'form_params';
        array_walk_recursive($params, 'self::prepareData');

         // if token is set replace it
         if(isset($params['token'])) {
            $header["_token_"] = $params['token'];
            $header["token"] = $params['token'];
            unset($params['token']);
        } else {
            $header["token"] = $header["_token_"];
        }

        if(!isset($params['data']) || empty($params['data'])) {
            throw new ValidationException(['data' => ['The property data is required']], 'The property data is required.',ValidationException::VALIDATION_ERROR_CODE);
        }
        // prepare params to send
        foreach ($params['data'] as $dataKey => $data) {
            $optionPerData = [
                'headers' => $header,
                $paramKey => $data,
            ];

            self::validateOption($optionPerData, self::$jsonSchema[$apiName], $paramKey);
        
            if(isset($data['tags']) && is_array($data['tags'])){
                $params['data'][$dataKey]['tags'] =  implode(',', $data['tags']);
            }

            if(isset($data['tagTrees']) && is_array($data['tagTrees'])){
                $params['data'][$dataKey]['tagTrees'] =  implode(',', $data['tagTrees']);
            }

            if(isset($data['tagTreeCategoryName']) && is_array($data['tagTreeCategoryName'])){
                $params['data'][$dataKey]['tagTreeCategoryName'] =  implode(',', $data['tagTreeCategoryName']);
            }
        }

        # prepare params to send
        # set service call product Id
        $preparedParams['scProductId'] = self::$serviceCallProductId[$apiName];
        $preparedParams['data'] =  json_encode($params['data']);
        $option = [
            'headers' => $header,
            $paramKey => $preparedParams,
        ];

        if (isset($params['scVoucherHash'])) {
            $preparedParams['scVoucherHash'] = $params['scVoucherHash'];
            $option['withoutBracketParams'] =  $preparedParams;
            unset($option[$paramKey]);
            $optionHasArray = true;
            $method = 'GET';
        }

        return ApiRequestHandler::Request(
            self::$config[self::$serverType][self::$socialApi[$apiName]['baseUri']],
            $method,
            self::$socialApi[$apiName]['subUri'],
            $option,
            false,
            $optionHasArray
        );
    }

    public function updateCustomPost($params) {
        $apiName = 'updateCustomPost';
        $optionHasArray = true;
        if (isset($params['tags']) && is_array($params['tags'])) {
            $params['tags'] = implode(',', $params['tags']);
        }
        list($method, $option) = $this->prepareDataBeforeSend($params, $apiName, $optionHasArray);

        return ApiRequestHandler::Request(
            self::$config[self::$serverType][self::$socialApi[$apiName]['baseUri']],
            $method,
            self::$socialApi[$apiName]['subUri'],
            $option,
            false,
            $optionHasArray
        );
    }

    public function updateUserPost($params) {
        $apiName = 'updateUserPost';
        $optionHasArray = true;
        if (isset($params['tags']) && is_array($params['tags'])) {
            $params['tags'] = implode(',', $params['tags']);
        }
        list($method, $option) = $this->prepareDataBeforeSend($params, $apiName, $optionHasArray);

        return ApiRequestHandler::Request(
            self::$config[self::$serverType][self::$socialApi[$apiName]['baseUri']],
            $method,
            self::$socialApi[$apiName]['subUri'],
            $option,
            false,
            $optionHasArray
        );
    }

    public function addComment($params) {
        $apiName = 'addComment';
        list($method, $option, $optionHasArray) = $this->prepareDataBeforeSend($params, $apiName);

        return ApiRequestHandler::Request(
            self::$config[self::$serverType][self::$socialApi[$apiName]['baseUri']],
            $method,
            self::$socialApi[$apiName]['subUri'],
            $option,
            false,
            $optionHasArray
        );
    }

    public function getCommentList($params) {
        $apiName = 'getCommentList';
        list($method, $option, $optionHasArray) = $this->prepareDataBeforeSend($params, $apiName);

        return ApiRequestHandler::Request(
            self::$config[self::$serverType][self::$socialApi[$apiName]['baseUri']],
            $method,
            self::$socialApi[$apiName]['subUri'],
            $option,
            false,
            $optionHasArray
        );
    }

    public function getConfirmedComments($params) {
        $apiName = 'getConfirmedComments';
        list($method, $option, $optionHasArray) = $this->prepareDataBeforeSend($params, $apiName);

        return ApiRequestHandler::Request(
            self::$config[self::$serverType][self::$socialApi[$apiName]['baseUri']],
            $method,
            self::$socialApi[$apiName]['subUri'],
            $option,
            false,
            $optionHasArray
        );
    }

    public function confirmCommentOfCustomPost($params) {
        $apiName = 'confirmCommentOfCustomPost';
        list($method, $option, $optionHasArray) = $this->prepareDataBeforeSend($params, $apiName);

        return ApiRequestHandler::Request(
            self::$config[self::$serverType][self::$socialApi[$apiName]['baseUri']],
            $method,
            self::$socialApi[$apiName]['subUri'],
            $option,
            false,
            $optionHasArray
        );
    }

    public function unconfirmCommentOfCustomPost($params) {
        $apiName = 'unconfirmCommentOfCustomPost';
        list($method, $option, $optionHasArray) = $this->prepareDataBeforeSend($params, $apiName);

        return ApiRequestHandler::Request(
            self::$config[self::$serverType][self::$socialApi[$apiName]['baseUri']],
            $method,
            self::$socialApi[$apiName]['subUri'],
            $option,
            false,
            $optionHasArray
        );
    }

    public function likePost($params) {
        $apiName = 'likePost';
        list($method, $option, $optionHasArray) = $this->prepareDataBeforeSend($params, $apiName);

        return ApiRequestHandler::Request(
            self::$config[self::$serverType][self::$socialApi[$apiName]['baseUri']],
            $method,
            self::$socialApi[$apiName]['subUri'],
            $option,
            false,
            $optionHasArray
        );
    }
    public function ratePost($params) {
        $apiName = 'ratePost';
        list($method, $option, $optionHasArray) = $this->prepareDataBeforeSend($params, $apiName);

        return ApiRequestHandler::Request(
            self::$config[self::$serverType][self::$socialApi[$apiName]['baseUri']],
            $method,
            self::$socialApi[$apiName]['subUri'],
            $option,
            false,
            $optionHasArray
        );
    }

    public function dislikePost($params) {
        $apiName = 'dislikePost';
        list($method, $option, $optionHasArray) = $this->prepareDataBeforeSend($params, $apiName);

        return ApiRequestHandler::Request(
            self::$config[self::$serverType][self::$socialApi[$apiName]['baseUri']],
            $method,
            self::$socialApi[$apiName]['subUri'],
            $option,
            false,
            $optionHasArray
        );
    }

    public function getLikeList($params) {
        $apiName = 'getLikeList';
        list($method, $option, $optionHasArray) = $this->prepareDataBeforeSend($params, $apiName);

        return ApiRequestHandler::Request(
            self::$config[self::$serverType][self::$socialApi[$apiName]['baseUri']],
            $method,
            self::$socialApi[$apiName]['subUri'],
            $option,
            false,
            $optionHasArray
        );
    }

    public function getUsersRate($params) {
        $apiName = 'getUsersRate';
        list($method, $option, $optionHasArray) = $this->prepareDataBeforeSend($params, $apiName, true);

        return ApiRequestHandler::Request(
            self::$config[self::$serverType][self::$socialApi[$apiName]['baseUri']],
            $method,
            self::$socialApi[$apiName]['subUri'],
            $option,
            false,
            $optionHasArray
        );
    }

    public function likeComment($params) {
        $apiName = 'likeComment';
        list($method, $option, $optionHasArray) = $this->prepareDataBeforeSend($params, $apiName);

        return ApiRequestHandler::Request(
            self::$config[self::$serverType][self::$socialApi[$apiName]['baseUri']],
            $method,
            self::$socialApi[$apiName]['subUri'],
            $option,
            false,
            $optionHasArray
        );
    }

    public function getCommentLikeList($params) {
        $apiName = 'getCommentLikeList';
        list($method, $option, $optionHasArray) = $this->prepareDataBeforeSend($params, $apiName);

        return ApiRequestHandler::Request(
            self::$config[self::$serverType][self::$socialApi[$apiName]['baseUri']],
            $method,
            self::$socialApi[$apiName]['subUri'],
            $option,
            false,
            $optionHasArray
        );
    }

    public function getBusinessTimeline($params) {
        $apiName = 'getBusinessTimeline';
        $optionHasArray = true;
        list($method, $option) = $this->prepareDataBeforeSend($params, $apiName, $optionHasArray);

        return ApiRequestHandler::Request(
            self::$config[self::$serverType][self::$socialApi[$apiName]['baseUri']],
            $method,
            self::$socialApi[$apiName]['subUri'],
            $option,
            false,
            $optionHasArray
        );
    }

    public function getTimeline($params) {
        $apiName = 'getTimeline';
        $optionHasArray = true;
        list($method, $option) = $this->prepareDataBeforeSend($params, $apiName, $optionHasArray);

        return ApiRequestHandler::Request(
            self::$config[self::$serverType][self::$socialApi[$apiName]['baseUri']],
            $method,
            self::$socialApi[$apiName]['subUri'],
            $option,
            false,
            $optionHasArray
        );
    }

    public function searchTimelineByMetadata($params) {
        $apiName = 'searchTimelineByMetadata';
        $optionHasArray = true;
        list($method, $option) = $this->prepareDataBeforeSend($params, $apiName, $optionHasArray);

        return ApiRequestHandler::Request(
            self::$config[self::$serverType][self::$socialApi[$apiName]['baseUri']],
            $method,
            self::$socialApi[$apiName]['subUri'],
            $option,
            false,
            $optionHasArray
        );
    }

    public function fullTextSearch($params) {
        $apiName = 'fullTextSearch';
        $optionHasArray = true;
        list($method, $option) = $this->prepareDataBeforeSend($params, $apiName, $optionHasArray);

        return ApiRequestHandler::Request(
            self::$config[self::$serverType][self::$socialApi[$apiName]['baseUri']],
            $method,
            self::$socialApi[$apiName]['subUri'],
            $option,
            false,
            $optionHasArray
        );
    }

    public function addUserPost($params) {
        $apiName = 'addUserPost';
        if (isset($params['tags']) && is_array($params['tags'])) {
            $params['tags'] = implode(',', $params['tags']);
        }
        list($method, $option, $optionHasArray) = $this->prepareDataBeforeSend($params, $apiName);

        return ApiRequestHandler::Request(
            self::$config[self::$serverType][self::$socialApi[$apiName]['baseUri']],
            $method,
            self::$socialApi[$apiName]['subUri'],
            $option,
            false,
            $optionHasArray
        );
    }

    public function userPostList($params) {
        $apiName = 'userPostList';
        $optionHasArray = true;
        list($method, $option) = $this->prepareDataBeforeSend($params, $apiName);

        return ApiRequestHandler::Request(
            self::$config[self::$serverType][self::$socialApi[$apiName]['baseUri']],
            $method,
            self::$socialApi[$apiName]['subUri'],
            $option,
            false,
            $optionHasArray
        );
    }

    public function loadUserPost($params) {
        $apiName = 'loadUserPost';
        list($method, $option, $optionHasArray) = $this->prepareDataBeforeSend($params, $apiName);

        return ApiRequestHandler::Request(
            self::$config[self::$serverType][self::$socialApi[$apiName]['baseUri']],
            $method,
            self::$socialApi[$apiName]['subUri'],
            $option,
            false,
            $optionHasArray
        );
    }

    public function searchOnUserPostByMetadata($params) {
        $apiName = 'searchOnUserPostByMetadata';
        $optionHasArray = true;
        list($method, $option) = $this->prepareDataBeforeSend($params, $apiName);

        return ApiRequestHandler::Request(
            self::$config[self::$serverType][self::$socialApi[$apiName]['baseUri']],
            $method,
            self::$socialApi[$apiName]['subUri'],
            $option,
            false,
            $optionHasArray
        );
    }

    public function confirmCommentOfUserPost($params) {
        $apiName = 'confirmCommentOfUserPost';
        list($method, $option, $optionHasArray) = $this->prepareDataBeforeSend($params, $apiName);

        return ApiRequestHandler::Request(
            self::$config[self::$serverType][self::$socialApi[$apiName]['baseUri']],
            $method,
            self::$socialApi[$apiName]['subUri'],
            $option,
            false,
            $optionHasArray
        );
    }

    public function unconfirmCommentOfUserPost($params) {
        $apiName = 'unconfirmCommentOfUserPost';
        list($method, $option, $optionHasArray) = $this->prepareDataBeforeSend($params, $apiName);

        return ApiRequestHandler::Request(
            self::$config[self::$serverType][self::$socialApi[$apiName]['baseUri']],
            $method,
            self::$socialApi[$apiName]['subUri'],
            $option,
            false,
            $optionHasArray
        );
    }

    public function shareUserPost($params) {
        $apiName = 'shareUserPost';
        list($method, $option, $optionHasArray) = $this->prepareDataBeforeSend($params, $apiName);

        return ApiRequestHandler::Request(
            self::$config[self::$serverType][self::$socialApi[$apiName]['baseUri']],
            $method,
            self::$socialApi[$apiName]['subUri'],
            $option,
            false,
            $optionHasArray
        );
    }

    private function prepareDataBeforeSend($params, $apiName, $optionHasArray = false){
        $header = $this->header;
        array_walk_recursive($params, 'self::prepareData');
        $method = self::$socialApi[$apiName]['method'];
        $paramKey = $method == 'GET' ? 'query' : 'form_params';

        // if token is set replace it
        if(isset($params['token'])) {
            $header["_token_"] = $params['token'];
            $header["token"] = $params['token'];
            unset($params['token']);
        } else {
            $header["token"] = $header["_token_"];
        }

        $option = [
            'headers' => $header,
            $paramKey => $params,
        ];

        self::validateOption($option, self::$jsonSchema[$apiName], $paramKey);

        if (isset($option[$paramKey]['metadata']) && is_array($option[$paramKey]['metadata'])) {
            $option[$paramKey]['metadata'] = json_encode($option[$paramKey]['metadata'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }

        // if (isset($option[$paramKey]['tags'])) {
        //     $option[$paramKey]['tags'] = implode(',', $option[$paramKey]['tags']);
        // }
        // if (isset($option[$paramKey]['tagTrees'])) {
        //     $option[$paramKey]['tagTrees'] = implode(',', $option[$paramKey]['tagTrees']);
        // }
        // if (isset($option[$paramKey]['tagTreeCategoryName'])) {
        //     $option[$paramKey]['tagTreeCategoryName'] = implode(',', $option[$paramKey]['tagTreeCategoryName']);
        // }

        # set service call product Id
        $option[$paramKey]['scProductId'] = self::$serviceCallProductId[$apiName];

        if (isset($params['scVoucherHash']) || $optionHasArray) {
            $option['withoutBracketParams'] =  $option[$paramKey];
            unset($option[$paramKey]);
            $optionHasArray = true;
            $method = 'GET';
        }

        return [$method, $option, $optionHasArray];
    }
}