<?php

namespace App;

class ResponsiveChecker
{
    protected $url;

    protected $responsive = false;

    protected $redirect = false;

    public function __construct($url)
    {
        $this->url = $url;

        $client = new \GuzzleHttp\Client();

        $this->response = $client->request('GET', $this->url, array(
            'allow_redirects' => array(
                'max'             => 10,        // allow at most 10 redirects.
                'strict'          => true,      // use "strict" RFC compliant redirects.
                'referer'         => true,      // add a Referer header
                'track_redirects' => true
            ),
            'headers' => array(
                'User-Agent' => 'Mozilla/5.0 (iPhone; U; CPU like Mac OS X; en) AppleWebKit/420.1 (KHTML, like Gecko) Version/3.0 Mobile/3B48b Safari/419.3'
            )
        ));

        $this->checkForRedirects()
            ->checkForMeta();
    }
    
    public function checkForRedirects()
    {
        $redirects = explode(',', $this->response->getHeaderLine('X-Guzzle-Redirect-History'));
        $this->finalUrl = end($redirects);

        $splitUrl = explode('/', $this->finalUrl);

        if (count($splitUrl) > 1) {
            $mobileUrls = array(
                'mobile.', 'm.'
            );

            foreach ($mobileUrls as $prefix) {
                if (substr($splitUrl[2], 0, strlen($prefix)) === $prefix) {
                    $this->redirect = true;
                    $this->responsive = true;        
                }
            }
        }

        return $this;
    }
    public function checkForMeta()
    {
        $haystack = $this->response->getBody();

        $indicators = array(
            'name="viewport"',
            'width=device-width', 
            'maximum-scale=1.0', 
            'minimum-scale=1.0'
        );

        foreach ($indicators as $needle) {
            if (strpos($haystack, $needle)) {
                $this->responsive = true;
            };
        }

        return $this; 
    }

    public function isResponsive()
    {
        return $this->responsive;
    }

    public function isRedirected()
    {
        return $this->redirect;
    }

    public function finalUrl()
    {
        return $this->finalUrl;
    }
    
    
    
}
