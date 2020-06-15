<?php

namespace App\Contracts;

interface ElasticSearchClient
{
    /**
     * 获取 ElasticSearch 客户端
     * @return mixed
     */
    public function getClient();

    /**
     * 添加日志
     * @param array $document
     * @return mixed
     */
    public function addDocument(array $document);

    /**
     * 获取所有已添加日志
     * @return mixed
     */
    public function getDocuments();
}