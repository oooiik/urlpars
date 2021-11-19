<?php

namespace Oooiik\Urlpars\Services;

use Oooiik\Urlpars\Contracts\Url as ContractUrl;

class Url implements ContractUrl
{
    public $firstUrl;

    public $protocol;
    public $user;
    public $password;

    public $host;
    public $domain_top;
    public $domain_name;
    public $domain_subs = [];

    public $port;

    public $path;
    public $dirname;
    public $file;
    public $filename;
    public $extension;

    public $query;
    public $attributes = [];

    public $fragment;

    public function __construct(string $firstUrl)
    {
        $this->firstUrl = $firstUrl;
        $this->set();
    }

    public static function one(string $firstUrl)
    {
        return new self($firstUrl);
    }

    public function set()
    {
        $parse = parse_url($this->firstUrl);
        $this->protocol = in_array($parse['scheme'] ?? null, self::PROTOCOL) ? $parse['scheme'] : null;
        $this->user = $parse['user'] ?? null;
        $this->password = $parse['password'] ?? null;
        $this->host = $parse['host'] ?? null;
        $this->port = $parse['port'] ?? null;
        $this->path = $parse['path'] ?? null;
        $this->query = $parse['query'] ?? null;
        $this->fragment = $parse['fragment'] ?? null;

        if($this->host){
            $domain = explode('.', $this->host);
            $this->domain_top = $domain[0] ? array_pop($domain) : null;
            $this->domain_name = $domain[0] ? array_pop($domain) : null;
            $this->domain_subs = $domain;
        }

        if($this->path){
            $path = pathinfo($this->path);
            $this->dirname = !in_array($path['dirname'], ['', null, '/', '\/', '\\']) ? $path['dirname'] : null;
            $this->extension = $path['extension'] ?? null;
            $this->file = $this->extension ? $path['basename'] : null;
            $this->filename = $this->file ? $path['filename'] : null;
            if(!$this->file && $path['basename']) $this->dirname .= ($this->dirname ? '/' : '' ) . $path['basename'];
        }

        if($this->query){
            parse_str($this->query, $this->attributes);
        }
    }


    public function all()
    {
        return (object)get_object_vars($this);
    }

}
