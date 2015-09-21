<?php


class Collection extends \Phalcon\Mvc\Collection
{

    /**
     * Returns a collection resultset
     *
     * @param array $params
     * @param \Phalcon\Mvc\Collection $collection
     * @param \MongoDb $connection
     * @param boolean $unique
     * @return array
     */
    protected static function _getResultset($params, \Phalcon\Mvc\CollectionInterface $collection, $connection, $unique)
    {

        if (\Phalcon\DI::getDefault()->get('profiler')) {
            $sql = 'SELECT ';

            /**
             * Perform the find
             */
            if (isset($params['fields'])) {
                $sql .= implode(', ', $params['fields']);
            } else {
                $sql .= ' * ';
            }

            $sql .= ' FROM ' . $collection->getSource() . ' ';

            /**
             * Get where condition
             */
            foreach ($params as $where) {
                if (is_array($where)) {
                    $sql .= ' WHERE ' . implode(', ', array_map(function ($v, $k) {
                            return sprintf("%s='%s'", $k, $v);
                        }, $where, array_keys($where))) . ' ';
                }
            }

            /**
             * Check if a "sort" clause was defined
             */
            if (isset($params['sort'])) {
                $sql .= ' ORDER BY ' . implode(', ', $params['sort']);
            }

            /**
             * Check if a "limit" clause was defined
             */
            if (isset($params['limit'])) {
                $sql .= ' LIMIT ' . $params['limit'];
            }

            /**
             * Check if a "skip" clause was defined
             */
            if (isset($params['skip'])) {
                $sql .= ' SKIP ' . $params['skip'];
            }

            $sql = str_replace('  ', ' ', $sql);

            $metadata = [
                'query' => $sql,
                'params' => $params,
                'types' => 'select',
            ];

            \Phalcon\DI::getDefault()->get('profiler')->start('MongoDB::query', $metadata, 'Database');
        }

        $rs = parent::_getResultset($params, $collection, $connection, $unique);

        if (\Phalcon\DI::getDefault()->get('profiler')) \Phalcon\DI::getDefault()->get('profiler')->stop();

        return $rs;
    }
}