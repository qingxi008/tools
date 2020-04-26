<?php
/**
 * Created by PhpStorm.
 * User: liangkang
 * Date: 2019-03-10
 * Time: 16:10
 */

namespace QingXi\Tools\Utils\Https;



class HttpResponse
{

    /**
     * @var
     */
    protected $requestHeader;

    /**
     * @var
     */
    protected $header;

    /**
     * @var string|null
     */
    protected $body;

    /**
     * @var int|null
     */
    protected $httpCode;

    /**
     *
     * @var HttpClient|null
     */
    protected $httpClient;

    /**
     * @var string|null
     */
    protected $error;

    /**
     * @var array|null
     */
    protected $curlInfo;

    public function __construct($httpClient)
    {
        $this->setHttpClient($httpClient);
        $this->exec();
        $this->destoryHttpClient();
    }


    /**
     *
     */
    protected function destoryHttpClient()
    {
        $this->getHttpClient()->destoryCurl();
    }

    protected function exec()
    {
        // 执行并获取HTML文档内容
        $curl = $this->getHttpClient()->getCurl();
        $output = curl_exec($curl);
        $this->setCurlInfo(curl_getinfo($curl));
        if (curl_errno($curl)) {
            $this->setError('连接主机' . $this->getHttpClient()
                    ->getUrl() . '时发生错误: ' . curl_error($curl));
            return false;
        }
        // 判断是否打印头信息
        $options = $this->getHttpClient()->getOptions();
        // 头信息处理
        if (isset($options[CURLOPT_HEADER]) && $options[CURLOPT_HEADER]) {
            $headerLen = $this->getCurlInfo('header_size');
            $this->setHeader(substr($output, 0, $headerLen));
            $this->setBody(substr($output, $headerLen));
        } else {
            $this->setBody($output);
        }
        return true;
    }


    /**
     * @return string|null
     */
    public function getHeader(): ?string
    {
        return $this->header;
    }



    /**
     * @param null $name
     * @return array|mixed|null
     */
    public function getCurlInfo($name = null)
    {
        return empty($name) ? $this->curlInfo : $this->curlInfo[$name];
    }


    public function getHttpCode()
    {
        return $this->getCurlInfo('http_code');
    }

    /**
     * @return mixed
     */
    public function getRequestHeader()
    {
        return $this->requestHeader;
    }

    /**
     * @param mixed $requestHeader
     * @return $this
     */
    public function setRequestHeader($requestHeader)
    {
        $this->requestHeader = $requestHeader;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getBody(): ?string
    {
        return $this->body;
    }

    /**
     * @param string|null $body
     * @return $this
     */
    public function setBody(?string $body): self
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @return HttpClient|null
     */
    public function getHttpClient(): ?HttpClient
    {
        return $this->httpClient;
    }

    /**
     * @param HttpClient|null $httpClient
     * @return $this
     */
    public function setHttpClient(?HttpClient $httpClient): self
    {
        $this->httpClient = $httpClient;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getError(): ?string
    {
        return $this->error;
    }

    /**
     * @param string|null $error
     * @return $this
     */
    public function setError(?string $error): self
    {
        $this->error = $error;
        return $this;
    }

    /**
     * @param mixed $header
     * @return $this
     */
    public function setHeader($header)
    {
        $this->header = $header;
        return $this;
    }

    /**
     * @param int|null $httpCode
     * @return $this
     */
    public function setHttpCode(?int $httpCode): self
    {
        $this->httpCode = $httpCode;
        return $this;
    }

    /**
     * @param array|null $curlInfo
     * @return $this
     */
    public function setCurlInfo(?array $curlInfo): self
    {
        $this->curlInfo = $curlInfo;
        return $this;
    }




}
