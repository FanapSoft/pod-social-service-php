<?php
namespace Pod\Social\Service;
use Pod\Base\Service\ApiRequestHandler;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Pod\Base\Service\Exception\PodException;
use Pod\Base\Service\Exception\RequestException;

Class SocialApiRequestHandler extends ApiRequestHandler {
    /**
     * @param string $baseUri
     * @param string $method
     * @param string $relativeUri
     * @param array $option
     * @param bool $optionHasArray
     * @param bool $restFull
     *
     * @return mixed
     *
     * @throws RequestException
     * @throws PodException
     */
    public static function Request($baseUri, $method, $relativeUri, $option, $restFull = false, $optionHasArray = false) {
        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => $baseUri,
            // You can set any number of default request options.
            'timeout'  => 30.0,
        ]);

        if ($optionHasArray && $method == 'GET') {

            $withoutBracketParams = isset($option['withoutBracketParams']) ? $option['withoutBracketParams'] : [];
            $withBracketParams = isset($option['withBracketParams']) ? $option['withBracketParams'] : [];

            $httpQuery = self::buildHttpQuery($withoutBracketParams, $withBracketParams);
            $relativeUri = $relativeUri . '?' . $httpQuery;
            unset($option['withoutBracketParams']); // unset query because it is added to uri and dont need send again in query params
            unset($option['withBracketParams']); // unset query because it is added to uri and dont need send again in query params
        }
        try {
            $response = $client->request($method, $relativeUri, $option);
        }
        catch (ClientException $e) {
            // echo Psr7\str($e->getRequest());
            if ($e->hasResponse()) {
                $response = $e->getResponse();

                $code = $response->getStatusCode();
                $responseBody = json_decode($response->getBody()->getContents(), true);
                $message = isset($responseBody['message']) ? $responseBody['message'] : $e->getMessage();
                throw new RequestException($message, $code, null, $responseBody);
            }

            $code = RequestException::SERVER_CONNECTION_ERROR;
            $message  = 'Connection Interrupt! please try again later.';
            throw new RequestException($message, $code);
        } catch (GuzzleException $e) {
            $responseBody = json_decode($response->getBody()->getContents(), true);
            throw new PodException($e->getMessage(), $e->getCode(), null, $responseBody);
        }

        $result = json_decode($response->getBody()->getContents(), true);
        var_dump($result);die;
        if(isset($result['result']['result'])) {
            $socialResult =  json_decode($result['result']['result'], true);
            $result['result']['result'] = $socialResult;
        } else {
            $socialResult = '';
        }

        // handle error from pod
        if (isset($result['hasError']) && $result['hasError']) {
            if (!isset($result['message']) && isset($result['errorDescription'])) {
                $result['message'] = $result['errorDescription'];
                unset($result['errorDescription']);
            }
            $message = isset($result['message']) ? $result['message'] :"Some unhandled error has occurred!";
            $errorCode = isset($result['errorCode']) ? $result['errorCode'] : PodException::SERVER_UNHANDLED_ERROR_CODE;
            throw new PodException($message, $errorCode,null, $result);
        // handle error from Social
        }elseif ($result['result']['statusCode'] < 200 || $result['result']['statusCode'] >= 300 || (isset($socialResult['status']) && $socialResult['status'] == 'ERROR') ){
                $message = !empty($socialResult['message'])? $socialResult['message'] : "Some unhandled error has occurred!";
                $errorCode = $result['result']['statusCode'];
                throw new PodException($message, $errorCode,null, $result);
        }
        return $result;
    }
}