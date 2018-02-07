<?php

namespace Koodilab\Models\Providers;

use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Http\Request;
use Koodilab\Models\Transformers\Transformer;

abstract class Provider extends Transformer
{
    /**
     * The config implementation.
     *
     * @var Config
     */
    protected $config;

    /**
     * The gate implementation.
     *
     * @var Gate
     */
    protected $gate;

    /**
     * The request instance.
     *
     * @var Request
     */
    protected $request;

    /**
     * The translator implementation.
     *
     * @var Translator
     */
    protected $translator;

    /**
     * The url generator implementation.
     *
     * @var UrlGenerator
     */
    protected $url;

    /**
     * Constructor.
     *
     * @param Config       $config
     * @param Gate         $gate
     * @param Request      $request
     * @param Translator   $translator
     * @param UrlGenerator $url
     */
    public function __construct(Config $config, Gate $gate, Request $request, Translator $translator, UrlGenerator $url)
    {
        $this->config = $config;
        $this->gate = $gate;
        $this->request = $request;
        $this->translator = $translator;
        $this->url = $url;
    }

    /**
     * Has keyword?
     *
     * @return bool
     */
    public function hasKeyword()
    {
        return ! empty($this->keyword());
    }

    /**
     * Is keyword numeric?
     *
     * @return bool
     */
    public function isKeywordNumeric()
    {
        return is_numeric($this->keyword());
    }

    /**
     * Get the keyword.
     *
     * @return string
     */
    public function keyword()
    {
        return $this->request->query->get('keyword');
    }

    /**
     * Get the like query keyword.
     *
     * @return string
     */
    public function likeKeyword()
    {
        return "%{$this->keyword()}%";
    }

    /**
     * Get the like query lower keyword.
     *
     * @return string
     */
    public function likeLowerKeyword()
    {
        return mb_strtolower($this->likeKeyword());
    }

    /**
     * Get the default sort.
     *
     * @return string
     */
    public function defaultSort()
    {
        $attributes = array_keys(
            $this->attributes()
        );

        return reset($attributes);
    }

    /**
     * Get the sort.
     *
     * @return string
     */
    public function sort()
    {
        return $this->request->query->get(
            'sort',
            $this->defaultSort()
        );
    }

    /**
     * Get the default direction.
     *
     * @return string
     */
    public function defaultDirection()
    {
        return 'asc';
    }

    /**
     * Get the direction.
     *
     * @return string
     */
    public function direction()
    {
        return $this->request->query->get(
            'direction',
            $this->defaultDirection()
        );
    }

    /**
     * Get the page.
     *
     * @return int
     */
    public function page()
    {
        return max(
            1,
            $this->request->query->getInt('page', 1)
        );
    }

    /**
     * Get the view data.
     *
     * @return array
     */
    public function viewData()
    {
        return [
            'attributes' => $this->attributes(),
            'keyword' => $this->keyword(),
            'default_sort' => $this->defaultSort(),
            'sort' => $this->sort(),
            'default_direction' => $this->defaultDirection(),
            'direction' => $this->direction(),
            'page' => $this->page(),
        ];
    }

    /**
     * Get the data.
     *
     * @return mixed
     */
    public function data()
    {
        return $this->transformCollection(
            $this->query()->paginate()
        );
    }

    /**
     * Get the attributes.
     *
     * @return array
     */
    abstract public function attributes();

    /**
     * Get the query.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    abstract public function query();
}
