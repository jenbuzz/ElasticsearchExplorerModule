<?php

namespace ElasticsearchExplorer\Service;

class ElasticsearchManager
{
    protected $client = false;
    protected $isConnected = false;
    protected $config = array();

    public function __construct(array $config)
    {
        $this->config = $config;
        if (!isset($config['hosts'])) {
            // Add default hosts if none is passed
            $this->config['hosts'] = 'localhost:9200';
        }

        try {
            $this->client = new \Elasticsearch\Client($this->getConfiguration());
            if ($this->client->ping()) {
                $this->isConnected = true;
            }
        } catch (\Elasticsearch\Common\Exceptions\Curl\CouldNotConnectToHost $e) {
        }
    }

    /**
     * Get the configuration as an array.
     *
     * @return array arrConfiguration
     */
    public function getConfiguration()
    {
        $arrDefaultConfiguration = array(
            'hosts' => array($this->config['hosts']),
        );

        return $arrDefaultConfiguration;
    }

    /**
     * Get the current indexes on the host with some statistics.
     *
     * @return array arrIndexes
     */
    public function getIndexStats()
    {
        if ($this->isConnected) {
            $objIndexes = $this->client->indices();
            $arrStats = $objIndexes->stats();
            $arrIndexesStats = $arrStats['indices'];

            $arrIndexes = array();
            foreach ($arrIndexesStats as $indexKey => $indexValues) {
                $arrIndexes[] = array(
                    'name' => $indexKey,
                    'total_docs' => $indexValues['total']['docs']['count'],
                    'total_size' => $indexValues['total']['store']['size_in_bytes'],
                );
            }

            return $arrIndexes;
        } else {
            return array();
        }
    }

    /**
     * Get the types in the specified index.
     *
     * @param string $index
     *
     * @return array $arrMappingTypes
     */
    public function getIndexMappingTypes($index)
    {
        if ($this->isConnected) {
            $objIndexes = $this->client->indices();
            $arrMappings = $objIndexes->getMapping(array('index' => $index));

            $arrMappingTypes = array();
            if (isset($arrMappings[$index]['mappings']) && !empty($arrMappings[$index]['mappings'])) {
                foreach ($arrMappings[$index]['mappings'] as $typeKey => $typeValue) {
                    $arrMappingTypes[] = array(
                        'name' => $typeKey,
                    );
                }
            }

            return $arrMappingTypes;
        } else {
            return array();
        }
    }

    /**
     * Get the fields in the specified type.
     *
     * @param string $index
     * @param string $type
     *
     * @return array $arrFields
     */
    public function getFieldsInIndexType($index, $type)
    {
        if ($this->isConnected) {
            $objIndexes = $this->client->indices();
            $arrMappings = $objIndexes->getMapping(array('index' => $index));

            $arrFields = array();
            if (isset($arrMappings[$index]['mappings'][$type]['properties']) && !empty($arrMappings[$index]['mappings'][$type]['properties'])) {
                foreach ($arrMappings[$index]['mappings'][$type]['properties'] as $typeKey => $typeValue) {
                    $arrFields[] = array(
                        'name' => $typeKey,
                        'type' => $typeValue['type'],
                        'index' => isset($typeValue['index']) ? $typeValue['index'] : '',
                    );
                }
            }

            return $arrFields;
        } else {
            return array();
        }
    }

    /**
     * Execute a search for a term in the specified fields, index, and type.
     *
     * @param string $index
     * @param string $type
     * @param string $fields
     * @param string $term
     *
     * @return array $results
     */
    public function search($index, $type, $fields, $term)
    {
        if ($this->isConnected) {
            try {
                $arrFields = $this->convertSearchfieldsToArray($fields);

                $params = array(
                    'index' => $index,
                    'type' => $type,
                    'body' => array(
                        'query' => array(
                            'bool' => array(
                                'should' => array(
                                    'multi_match' => array(
                                        'query' => $term,
                                        'operator' => 'or',
                                        'fields' => $arrFields,
                                    ),
                                ),
                            ),
                        ),
                    ),
                );

                $results = $this->client->search($params);
                if (isset($results['hits']) && isset($results['hits']['hits']) && !empty($results['hits']['hits'])) {
                    return $results['hits']['hits'];
                } else {
                    return array();
                }
            } catch (\Elasticsearch\Common\Exceptions\BadRequest400Exception $e) {
                return array();
            }
        } else {
            return array();
        }
    }

    /**
     * Get plugins installed on specified elasticsearch node.
     *
     * @return array $arrPlugins
     */
    public function getPlugins()
    {
        if ($this->isConnected) {
            $arrStatsCluster = $this->client->cluster()->stats();
            $arrPlugins = $arrStatsCluster['nodes']['plugins'];

            return $arrPlugins;
        } else {
            return array();
        }
    }

    /**
     * Convert a string of searchfields to an array as expected by the elasticsearch client.
     *
     * @param string $searchfields
     *
     * @return array $arrFields
     */
    public function convertSearchfieldsToArray($searchfields)
    {
        if (strpos($searchfields, ',') !== false) {
            $arrFields = explode(',', $searchfields);
        } else {
            $arrFields = array($searchfields);
        }

        return $arrFields;
    }
}
