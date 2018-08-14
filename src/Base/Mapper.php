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
    abstract protected function map(): array;

    public function normalizeData(string $class, array $data)
    {
        $envelopeKey = ArrayHelper::getValue($this->map(), "$class.envelope");
        if ($envelopeKey) {
            $data = ArrayHelper::getValue($data, $envelopeKey, []);
        }

        return $data;
    }

    public function populate(string $class, array $data, $relations = false)
    {
        $itemMap = ArrayHelper::getValue($this->map(), "$class.item", []);

        $model = new $class();
        foreach ($itemMap as $to => $from) {
            $model->$to = ArrayHelper::getValue($data, $from);
        }

        if ($relations) {
            $this->populateRelations($model, $data);
        }

        return $model;
    }

    /**
     * @param array $data
     * @param $model
     */
    private function populateRelations($model, array $data): void
    {
        $class = get_class($model);
        $relationsMap = ArrayHelper::getValue($this->map(), "$class.relations", []);
        foreach ($relationsMap as $to => $class) {
            $relationData = $this->normalizeData($class, $data);
            if ($relationData) {
                $model->$to = $this->populate($class, $relationData);
            }
        }
    }

}