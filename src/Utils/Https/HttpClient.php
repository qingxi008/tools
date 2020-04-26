<?php

namespace QingXi\Tools\Utils\Https;

class HttpClient
{

    /**
     * @var string
     */
    const METHOD_GET = 'get';


    /**
     * @var string
     */
    const METHOD_POST = 'post';


//    /**
//     * @var string
//     */
//    const METHOD_PUT = 'put';


    // ================================================
    //
    // ================================================
    /**
     * @var false|resource|null
     */
    protected $curl = null;


    /**
     * @var array
     */
    protected $options = array(
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_0,
        // CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_HEADER => null,
        // CURLOPT_SSL_VERIFYHOST => $sslFlag,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_HEADER => true
    );

    /**
     * @var null|string
     */
    protected $url = null;

    /**
     * @var string
     */
    protected $method = 'get';

    /**
     * @var null
     */
    protected $data = null;

    /**
     * @var null|string
     */
    protected $errMsg = null;

    public function __construct()
    {
        // 初始化
        $this->curl = curl_init();
    }

    /**
     * 销毁curl
     *
     * @return boolean
     */
    public function destoryCurl()
    {
        if (! is_null($this->curl)) {
            curl_close($this->curl);
        }
        return true;
    }

    /**
     *
     * @return boolean|HttpResponse
     */
    public function getHttpResponse()
    {
        // 初始化错误
        $this->initErrMsg();
        $options = $this->getOptions();
        $options[CURLOPT_URL] = $this->getUrl();
        if (! isset($options[CURLOPT_SSL_VERIFYHOST])) {
            $options[CURLOPT_SSL_VERIFYHOST] = $this->isVerifyHost();
        }
        switch ($this->getMethod()) {
            case 'get':
                break;

            case 'post':
                $options[CURLOPT_POST] = 1;
                $options[CURLOPT_POSTFIELDS] = $this->getData();
                break;

            default:
                $this->setErrMsg('请求方式目前只支持 get 与 post !');
                return false;
        }

        //curl_setopt_array($this->curl, $options);
        foreach($options as $k=>$v){
            curl_setopt($this->curl, $k, $v);
        }
        return new HttpResponse($this);
    }




    /**
     * @param $options
     * @return $this
     * @throws \Exception
     */
    public function setOptions($options)
    {
        if (! is_array($options)) {
            throw new \Exception('setopt must be array');
        }

        foreach ($options as $sk => $sv) {
            $this->options[$sk] = $sv;
        }

        return $this;
    }


    /**
     * @param $method
     * @return $this
     * @throws \Exception
     */
    public function setMethod($method)
    {
        $this->method = $method;
        switch (strtolower($method)) {
            case 'get':
                $this->method = 'get';
                break;

            case 'post':
                $this->method = 'post';
                break;

            default:
                throw new \Exception('请求方式目前只支持 get 与 post !');
                break;
        }
        return $this;
    }


    /**
     * @param $data
     * @return $this
     */
    public function setData($data)
    {
        if($data instanceof \JsonSerializable){
            $this->data =  $data->jsonSerialize();
        }else{
            $this->data = $data;
        }
        return $this;

    }


    /**
     * @#title VERIFYHOST
     * @return int
     */
    protected function isVerifyHost()
    {
        $pathUrl = parse_url($this->getUrl());
        return ($pathUrl['scheme'] == 'https' || $pathUrl['scheme'] == 'ssl') ? 2 : 0;
    }


    /**
     *
     * @#title 初始化错误日志
     */
    protected function initErrMsg()
    {
        $this->errMsg = null;
    }

    /**
     * @return false|resource|null
     */
    public function getCurl()
    {
        return $this->curl;
    }

    /**
     * @param false|resource|null $curl
     * @return HttpClient
     */
    public function setCurl($curl)
    {
        $this->curl = $curl;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param string|null $url
     * @return $this
     */
    public function setUrl(?string $url): self
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getErrMsg(): ?string
    {
        return $this->errMsg;
    }

    /**
     * @param string|null $errMsg
     * @return $this
     */
    public function setErrMsg(?string $errMsg): self
    {
        $this->errMsg = $errMsg;
        return $this;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return null
     */
    public function getData()
    {
        return $this->data;
    }


}
