<?php
/**
 * Created for IG Client.
 * User: jakim <pawel@jakimowski.info>
 * Date: 16.03.2018
 */

namespace Jakim\Base;


use Jakim\Helper\ArrayHelper;

abstract class Mapper
{
    /**
     * Attributes map.
     *
     * @return array
     */
    abstract public function config(): array;

    public function getData(array $content, array $config)
    {
        $envelopeKey = ArrayHelper::getValue($config, 'envelope');
        if ($envelopeKey) {
            $content = ArrayHelper::getValue($content, $envelopeKey, []);
        }

        return $content;
    }

    public function createModel(array $data, array $config, bool $relations = false)
    {
        $modelClass = ArrayHelper::getValue($config, 'class');
        $propertiesMap = ArrayHelper::getValue($config, 'properties', []);

        $model = new $modelClass();
        foreach ($propertiesMap as $to => $from) {
            $model->$to = ArrayHelper::getValue($data, $from);
        }

        if ($relations === true) {
            $relationsConfig = ArrayHelper::getValue($config, 'relations', []);
            $this->createRelations($model, $data, $relationsConfig, $relations);
        }

        return $model;
    }

    /**
     * @param $model
     * @param array $data
     * @param array $relationsConfig
     * @param bool $relations
     */
    protected function createRelations($model, array $data, array $relationsConfig, bool $relations = false): void
    {
        foreach ($relationsConfig as $property => $config) {
            $relationData = $this->getData($data, $config);
            if ($relationData) {
                if (ArrayHelper::getValue($config, 'multiple', false)) {
                    foreach ($relationData as $rData) {
                        $model->$property[] = $this->createModel($rData, $config, $relations);
                    }
                } else {
                    $model->$property = $this->createModel($relationData, $config, $relations);
                }
            }
        }
    }

}