<?php

namespace AirTable;


/**
 * Class Api
 * @package AirTable
 */
class Api implements ApiInterface
{
    /**
     *
     */
    const HOST = "https://api.airtable.com";
    /**
     *
     */
    const RATE_LIMIT = 5;

    /**
     * @var string
     */
    protected $v = "v0";

    /**
     * @var
     */
    protected $base;
    /**
     * @var
     */
    protected $token;
    /**
     * @var
     */
    protected $table;

    /**
     * @var array
     */
    protected $sort = [];
    /**
     * @var array
     */
    protected $fields = [];
    /**
     * @var array
     */
    protected $filters = [];
    /**
     * @var
     */
    protected $maxRecords;
    /**
     * @var int
     */
    protected $pageSize = 15;
    /**
     * @var
     */
    protected $offset;

    /**
     * Api constructor.
     * @param string $token
     * @param string $base
     * @param string $table
     */
    public function __construct(string $token, string $base, string $table)
    {
        $this->setBase($base);
        $this->setToken($token);
        $this->setTable($table);
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->v;
    }

    /**
     * @param string $v
     */
    public function setVersion(string $v): void
    {
        $this->v = $v;
    }

    /**
     * @return array
     */
    protected function getSort(): array
    {
        return $this->sort;
    }

    /**
     * @param array $sort
     */
    protected function setSort(array $sort): void
    {
        $this->sort = $sort;
    }

    /**
     * @return array
     */
    protected function getFields(): array
    {
        return $this->fields;
    }

    /**
     * @param array $fields
     */
    protected function setFields(array $fields): void
    {
        $this->fields = $fields;
    }

    /**
     * @return array
     */
    protected function getFilters(): array
    {
        return $this->filters;
    }

    /**
     * @param array $filters
     */
    protected function setFilters(array $filters): void
    {
        $this->filters = $filters;
    }

    /**
     * @param string $filters
     */
    protected function setFilter(string $filters): void
    {
        $this->filters[] = $filters;
    }

    /**
     * @return mixed
     */
    protected function getMaxRecords()
    {
        return $this->maxRecords;
    }

    /**
     * @param mixed $maxRecords
     */
    protected function setMaxRecords($maxRecords): void
    {
        $this->maxRecords = $maxRecords;
    }

    /**
     * @return int
     */
    protected function getPageSize(): int
    {
        return $this->pageSize;
    }

    /**
     * @param int $pageSize
     */
    protected function setPageSize(int $pageSize): void
    {
        $this->pageSize = $pageSize;
    }

    /**
     * @return mixed
     */
    protected function getOffset()
    {
        return $this->offset;
    }

    /**
     * @param mixed $offset
     */
    protected function setOffset($offset): void
    {
        $this->offset = $offset;
    }



    /**
     * @param string $id
     * @return string
     */
    public function getEndpointUrl(string $id = "") : string {
        return $this->getHost() . "/" . trim("/{$this->getVersion()}/{$this->getBase()}/{$this->getTable()}/{$id}", "/");
    }

    /**
     * @return string
     */
    public function getHost() : string {
        return static::HOST;
    }

    /**
     * @return int
     */
    public function getRateLimit() : int {
        return static::RATE_LIMIT;
    }

    /**
     * @param string $token
     */
    public function setToken(string $token) : void {
        $this->token = $token;
    }

    /**
     * @return string
     */
    public function getToken() : string {
        return $this->token;
    }

    /**
     * @param string $base
     */
    public function setBase(string $base) : void  {
        $this->base = $base;
    }

    /**
     * @return string
     */
    public function getBase() : string  {
        return $this->base;
    }

    /**
     * @param string $table
     */
    public function setTable(string $table) {
        $this->table = $table;
    }

    /**
     * @return string
     */
    public function getTable() : string {
        return $this->table;
    }

    /**
     * @param array $fields
     * @return Api
     */
    public function fields(array $fields) : Api
    {
        $this->setFields($fields);

        return $this;
    }

    /**
     * @param string $field
     * @param string $direction
     * @return Api
     */
    public function sort(string $field, string $direction = "desc") : Api
    {
        $this->setSort([["field" => $field, "direction" => strtolower($direction)]]);

        return $this;
    }

    /**
     * @param string $column
     * @param string $operator
     * @param string|null $value
     * @return Api
     */
    public function filterByFormula(string $column, string $operator = "=", string $value = null) : Api
    {
        $this->setFilter("{{$column}}{$operator}\"{$value}\"");

        return $this;
    }

    /**
     * @param int $maxRecords
     * @return Api
     */
    public function maxRecords(int $maxRecords) : Api {
        $this->setMaxRecords($maxRecords);

        return $this;
    }

    /**
     * @param int $pageSize
     * @return Api
     */
    public function pageSize(int $pageSize) : Api {
        $this->setPageSize($pageSize);

        return $this;
    }

    /**
     * @param string $offset
     * @return Api
     */
    public function offset(string $offset) : Api {
        $this->setOffset($offset);

        return $this;
    }

    /**
     * @return array [
     *      'fields'     => (array) Fields in select query.
     *      'sort' => (array) Sorting field and direction.
     *      'filterByFormula'     => (string) Filter query.
     *      'maxRecords'     => (int) Max count of records.
     *      'pageSize'         => (int) Page size
     *      'offset'         => (string) Next page ID.
     *    ]
     */
    public function getParams() : array {
        return [
            "fields" => $this->getFields(),
            "sort" => $this->getSort(),
            "filterByFormula" => implode('&', $this->getFilters()),
            "maxRecords" => $this->getMaxRecords(),
            "pageSize" => $this->getPageSize(),
            "offset" => $this->getOffset()
        ];
    }

    /**
     * @return array
     */
    public function getHeaders() : array {
        $headers = [
            'Authorization' => "Bearer {$this->getToken()}",
            'content-type' => 'application/json',
        ];

        return $headers;
    }
}